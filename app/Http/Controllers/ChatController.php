<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $sessions = auth()->user()->chatSessions()->latest()->get();
        $currentSession = null;
        $chats = collect();
        
        if ($request->session_id) {
            $currentSession = auth()->user()->chatSessions()->find($request->session_id);
            if ($currentSession) {
                $chats = $currentSession->chats()->latest()->get();
            }
        } elseif ($sessions->isNotEmpty()) {
            $currentSession = $sessions->first();
            $chats = $currentSession->chats()->latest()->get();
        }
        
        return view('chat.index', compact('sessions', 'currentSession', 'chats'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // Menggunakan API gratis dari Hugging Face
        $rawResponse = $this->getAIResponse($request->message);
        $response = $this->formatAIResponse($rawResponse);

        $sessionId = $request->session_id;
        if (!$sessionId) {
            $title = $this->generateChatTitle($request->message);
            $session = ChatSession::create([
                'user_id' => auth()->id(),
                'title' => $title
            ]);
            $sessionId = $session->id;
        }

        $chat = Chat::create([
            'user_id' => auth()->id(),
            'session_id' => $sessionId,
            'message' => $request->message,
            'response' => $response,
        ]);
        
        // Update session title jika ini chat pertama
        $session = ChatSession::find($sessionId);
        if ($session && $session->chats()->count() === 1) {
            $session->update(['title' => $this->generateChatTitle($request->message)]);
        }

        return response()->json([
            'id' => $chat->id,
            'session_id' => $sessionId,
            'message' => $chat->message,
            'response' => $chat->response,
            'created_at' => $chat->created_at->format('H:i'),
        ]);
    }

    private function getAIResponse($message)
    {
        // Coba berbagai API gratis secara berurutan
        
        // 1. Groq API (sangat cepat dan gratis)
        if (env('GROQ_API_KEY')) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
                    'Content-Type' => 'application/json',
                ])->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'llama3-8b-8192',
                    'messages' => [
                        ['role' => 'system', 'content' => 'Anda adalah asisten AI yang ramah dan membantu. Berikan jawaban yang jelas, terstruktur, dan mudah dipahami dalam bahasa Indonesia. Gunakan format yang rapi dengan bullet points jika diperlukan.'],
                        ['role' => 'user', 'content' => $message]
                    ],
                    'max_tokens' => 300,
                    'temperature' => 0.7,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $aiResponse = $data['choices'][0]['message']['content'] ?? null;
                    return $aiResponse ? $this->cleanAIResponse($aiResponse) : null;
                }
            } catch (\Exception $e) {}
        }

        // 2. Cohere API (gratis dengan limit)
        if (env('COHERE_API_KEY')) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . env('COHERE_API_KEY'),
                    'Content-Type' => 'application/json',
                ])->post('https://api.cohere.ai/v1/generate', [
                    'model' => 'command-light',
                    'prompt' => "Sebagai asisten AI yang membantu, jawab pertanyaan berikut dengan jelas dan terstruktur dalam bahasa Indonesia:\n\n" . $message,
                    'max_tokens' => 200,
                    'temperature' => 0.7,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $aiResponse = trim($data['generations'][0]['text'] ?? '');
                    return $aiResponse ? $this->cleanAIResponse($aiResponse) : null;
                }
            } catch (\Exception $e) {}
        }

        // 3. Hugging Face API
        if (env('HUGGINGFACE_API_KEY')) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . env('HUGGINGFACE_API_KEY'),
                ])->post('https://api-inference.huggingface.co/models/microsoft/DialoGPT-medium', [
                    'inputs' => $message,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $aiResponse = $data[0]['generated_text'] ?? null;
                    return $aiResponse ? $this->cleanAIResponse($aiResponse) : null;
                }
            } catch (\Exception $e) {}
        }

        // 4. Ollama (lokal - jika terinstall)
        if (env('OLLAMA_ENABLED', false)) {
            try {
                $response = Http::post('http://localhost:11434/api/generate', [
                    'model' => 'llama2',
                    'prompt' => "Jawab pertanyaan berikut dengan jelas dan terstruktur dalam bahasa Indonesia:\n\n" . $message,
                    'stream' => false,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $aiResponse = $data['response'] ?? null;
                    return $aiResponse ? $this->cleanAIResponse($aiResponse) : null;
                }
            } catch (\Exception $e) {}
        }

        // Fallback: Smart responses berdasarkan kata kunci
        return $this->getSmartFallbackResponse($message);
    }

    private function cleanAIResponse($response)
    {
        // Bersihkan response dari karakter aneh dan format yang tidak diinginkan
        $response = trim($response);
        
        // Hapus karakter kontrol dan non-printable
        $response = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $response);
        
        // Bersihkan HTML tags jika ada
        $response = strip_tags($response);
        
        // Perbaiki encoding
        $response = mb_convert_encoding($response, 'UTF-8', 'UTF-8');
        
        // Hapus multiple spaces dan newlines berlebihan
        $response = preg_replace('/\s+/', ' ', $response);
        $response = preg_replace('/\n{3,}/', "\n\n", $response);
        
        // Perbaiki tanda baca
        $response = str_replace([' ,', ' .', ' !', ' ?'], [',', '.', '!', '?'], $response);
        
        // Kapitalisasi awal kalimat
        $response = ucfirst(trim($response));
        
        // Pastikan diakhiri dengan tanda baca
        if (!preg_match('/[.!?]$/', $response)) {
            $response .= '.';
        }
        
        return $response;
    }

    private function getSmartFallbackResponse($message)
    {
        $message = strtolower($message);
        
        // Respon berdasarkan kata kunci dengan format yang lebih baik
        if (str_contains($message, 'halo') || str_contains($message, 'hai') || str_contains($message, 'hello')) {
            return "Halo! 👋 Senang bertemu dengan Anda.\n\nAda yang bisa saya bantu hari ini? Saya siap membantu dengan berbagai topik seperti:\n• Programming dan teknologi\n• Resep masakan\n• Tips kesehatan\n• Saran bisnis\n• Dan masih banyak lagi!";
        }
        
        if (str_contains($message, 'apa kabar') || str_contains($message, 'bagaimana kabar')) {
            return "Kabar saya baik, terima kasih! 😊\n\nSaya siap membantu Anda kapan saja. Bagaimana dengan Anda? Ada yang ingin dibicarakan atau ditanyakan?";
        }
        
        if (str_contains($message, 'siapa') && str_contains($message, 'kamu')) {
            return "Saya adalah asisten AI yang dirancang untuk membantu Anda! 🤖\n\nSaya dapat membantu dengan:\n• Menjawab pertanyaan umum\n• Memberikan saran dan tips\n• Berdiskusi tentang berbagai topik\n• Membantu pemecahan masalah\n\nAda yang ingin Anda tanyakan?";
        }
        
        if (str_contains($message, 'terima kasih') || str_contains($message, 'thanks')) {
            return "Sama-sama! 😊 Senang bisa membantu Anda.\n\nJika ada pertanyaan lain atau topik yang ingin dibahas, jangan ragu untuk bertanya ya!";
        }
        
        if (str_contains($message, 'bantuan') || str_contains($message, 'help')) {
            return "Tentu! Saya di sini untuk membantu Anda. 💪\n\nSilakan ceritakan:\n• Apa yang Anda butuhkan?\n• Topik apa yang ingin dibahas?\n• Masalah apa yang perlu diselesaikan?\n\nSaya akan berusaha memberikan jawaban terbaik!";
        }
        
        // Deteksi topik spesifik
        if (str_contains($message, 'coding') || str_contains($message, 'programming') || str_contains($message, 'code')) {
            return "Wah, tertarik dengan programming! 💻\n\nSaya bisa membantu dengan:\n• Konsep dasar programming\n• Tips debugging\n• Rekomendasi bahasa pemrograman\n• Best practices\n\nAda yang spesifik ingin ditanyakan?";
        }
        
        if (str_contains($message, 'resep') || str_contains($message, 'masak') || str_contains($message, 'makanan')) {
            return "Suka memasak ya! 🍳\n\nSaya bisa membantu dengan:\n• Resep masakan sederhana\n• Tips memasak\n• Substitusi bahan\n• Teknik memasak dasar\n\nMau masak apa hari ini?";
        }
        
        if (str_contains($message, 'bisnis') || str_contains($message, 'usaha') || str_contains($message, 'marketing')) {
            return "Tertarik dengan dunia bisnis! 💼\n\nSaya bisa membantu dengan:\n• Tips memulai bisnis\n• Strategi marketing\n• Manajemen keuangan\n• Ide bisnis\n\nAda aspek bisnis tertentu yang ingin dibahas?";
        }
        
        // Response untuk pertanyaan
        if (str_contains($message, '?')) {
            $responses = [
                "Pertanyaan yang menarik! 🤔\n\nMenurut saya, hal ini tergantung pada konteks dan situasinya. Bisakah Anda memberikan detail lebih spesifik?",
                "Hmm, itu topik yang cukup kompleks.\n\nUntuk memberikan jawaban yang tepat, saya perlu tahu lebih banyak tentang situasi Anda. Bisa ceritakan lebih detail?",
                "Pertanyaan bagus! 💡\n\nSebelum saya jawab, apa pendapat atau pengalaman Anda sendiri tentang hal ini? Mungkin kita bisa diskusi lebih dalam.",
                "Saya senang Anda bertanya tentang ini!\n\nIni topik yang menarik untuk dibahas. Mari kita eksplorasi bersama-sama."
            ];
        } else {
            $responses = [
                "Saya mengerti maksud Anda. 💭\n\nItu poin yang menarik! Bisakah Anda ceritakan lebih detail tentang pengalaman atau pemikiran Anda?",
                "Terima kasih sudah berbagi! 😊\n\nSaya senang mendengar perspektif Anda. Ada aspek lain yang ingin dibahas?",
                "Setuju! Topik ini memang layak untuk didiskusikan lebih dalam.\n\nApa yang membuat Anda tertarik dengan hal ini?",
                "Wah, saya belajar sesuatu yang baru dari Anda! 🎆\n\nBagaimana jika kita bahas lebih lanjut? Ada pengalaman menarik yang bisa dibagikan?"
            ];
        }
        
        return $responses[array_rand($responses)];
    }

    private function generateChatTitle($message)
    {
        $message = strtolower(trim($message));
        
        // Deteksi topik berdasarkan kata kunci
        if (str_contains($message, 'coding') || str_contains($message, 'programming') || str_contains($message, 'code')) {
            return '💻 Programming & Coding';
        }
        if (str_contains($message, 'resep') || str_contains($message, 'masak') || str_contains($message, 'makanan')) {
            return '🍳 Resep & Masakan';
        }
        if (str_contains($message, 'kesehatan') || str_contains($message, 'sakit') || str_contains($message, 'obat')) {
            return '🏥 Kesehatan';
        }
        if (str_contains($message, 'belajar') || str_contains($message, 'sekolah') || str_contains($message, 'pelajaran')) {
            return '📚 Pendidikan';
        }
        if (str_contains($message, 'bisnis') || str_contains($message, 'usaha') || str_contains($message, 'marketing')) {
            return '💼 Bisnis & Marketing';
        }
        if (str_contains($message, 'travel') || str_contains($message, 'wisata') || str_contains($message, 'liburan')) {
            return '✈️ Travel & Wisata';
        }
        if (str_contains($message, 'teknologi') || str_contains($message, 'gadget') || str_contains($message, 'smartphone')) {
            return '📱 Teknologi';
        }
        if (str_contains($message, 'olahraga') || str_contains($message, 'fitness') || str_contains($message, 'gym')) {
            return '🏃‍♂️ Olahraga & Fitness';
        }
        if (str_contains($message, 'musik') || str_contains($message, 'lagu') || str_contains($message, 'band')) {
            return '🎵 Musik';
        }
        if (str_contains($message, 'film') || str_contains($message, 'movie') || str_contains($message, 'drama')) {
            return '🎬 Film & Entertainment';
        }
        
        // Fallback: ambil kata-kata penting dari pesan
        $words = explode(' ', $message);
        $importantWords = array_filter($words, function($word) {
            return strlen($word) > 3 && !in_array($word, ['yang', 'untuk', 'dengan', 'dari', 'pada', 'dalam', 'adalah', 'akan', 'bisa', 'dapat']);
        });
        
        if (!empty($importantWords)) {
            $title = ucwords(implode(' ', array_slice($importantWords, 0, 3)));
            return strlen($title) > 30 ? substr($title, 0, 30) . '...' : $title;
        }
        
        return 'Chat Baru';
    }

    private function formatAIResponse($response)
    {
        $response = trim($response);
        
        // Deteksi dan format code blocks
        if (preg_match('/```|`[^`]+`|def |import |print\(|for |if |while |#/', $response)) {
            return $this->formatCodeResponse($response);
        }
        
        // Format untuk list dan bullet points
        if (preg_match('/\d+\.|\*|\-|•/', $response)) {
            return $this->formatListResponse($response);
        }
        
        // Format paragraf panjang
        if (strlen($response) > 150) {
            return $this->formatParagraphResponse($response);
        }
        
        return $response;
    }
    
    private function formatCodeResponse($response)
    {
        $lines = explode("\n", $response);
        $formatted = [];
        $inCodeBlock = false;
        
        foreach ($lines as $line) {
            $line = rtrim($line);
            
            // Deteksi code block markers
            if (preg_match('/^```/', $line)) {
                $inCodeBlock = !$inCodeBlock;
                continue;
            }
            
            if (empty(trim($line))) {
                $formatted[] = '';
                continue;
            }
            
            // Format code lines
            if ($inCodeBlock || preg_match('/^(def |import |print\(|for |if |while |#|    )/', $line)) {
                $formatted[] = $line; // Keep original indentation
            } else {
                // Format headers
                if (preg_match('/^\*\*(.+)\*\*/', $line, $matches)) {
                    $formatted[] = "\n📝 **" . trim($matches[1]) . "**";
                } else {
                    $formatted[] = $line;
                }
            }
        }
        
        return implode("\n", $formatted);
    }
    
    private function formatListResponse($response)
    {
        $lines = explode("\n", $response);
        $formatted = [];
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (!empty($line)) {
                // Standardize bullet points
                $line = preg_replace('/^[\*\-]\s*/', '• ', $line);
                $line = preg_replace('/^\d+\.\s*/', '• ', $line);
                $formatted[] = $line;
            } else {
                $formatted[] = '';
            }
        }
        
        return implode("\n", $formatted);
    }
    
    private function formatParagraphResponse($response)
    {
        // Split berdasarkan kalimat untuk paragraf yang rapi
        $sentences = preg_split('/(?<=[.!?])\s+/', $response);
        $paragraphs = [];
        $currentParagraph = '';
        
        foreach ($sentences as $sentence) {
            $sentence = trim($sentence);
            if (strlen($sentence) > 5) {
                if (strlen($currentParagraph . ' ' . $sentence) > 120) {
                    if (!empty($currentParagraph)) {
                        $paragraphs[] = $currentParagraph;
                    }
                    $currentParagraph = $sentence;
                } else {
                    $currentParagraph .= (empty($currentParagraph) ? '' : ' ') . $sentence;
                }
            }
        }
        
        if (!empty($currentParagraph)) {
            $paragraphs[] = $currentParagraph;
        }
        
        return implode("\n\n", $paragraphs);
    }
    
    public static function formatForDisplay($response)
    {
        // Format untuk display di view dengan HTML
        $response = trim($response);
        
        // Convert **text** menjadi <strong>text</strong>
        $response = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $response);
        
        // Convert bullet points menjadi numbered list yang rapi
        $lines = explode("\n", $response);
        $formatted = [];
        $listCounter = 0;
        $inList = false;
        
        foreach ($lines as $line) {
            $line = trim($line);
            
            if (empty($line)) {
                if ($inList) {
                    $inList = false;
                    $listCounter = 0;
                }
                $formatted[] = '';
                continue;
            }
            
            // Deteksi bullet points
            if (preg_match('/^[•\*\-]\s*(.+)/', $line, $matches)) {
                if (!$inList) {
                    $inList = true;
                    $listCounter = 0;
                }
                $listCounter++;
                $formatted[] = "<span class='list-item'><strong>{$listCounter}.</strong> {$matches[1]}</span>";
            } else {
                if ($inList) {
                    $inList = false;
                    $listCounter = 0;
                }
                $formatted[] = $line;
            }
        }
        
        return nl2br(implode("\n", $formatted));
    }

    public function newSession()
    {
        $session = ChatSession::create([
            'user_id' => auth()->id(),
            'title' => 'New Chat'
        ]);
        
        return redirect()->route('chat.index', ['session_id' => $session->id]);
    }

    public function deleteSession($id)
    {
        $session = auth()->user()->chatSessions()->findOrFail($id);
        $session->delete();
        
        return redirect()->route('chat.index');
    }
}
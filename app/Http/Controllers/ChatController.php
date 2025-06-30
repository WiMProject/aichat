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
                        ['role' => 'user', 'content' => $message]
                    ],
                    'max_tokens' => 150,
                    'temperature' => 0.7,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['choices'][0]['message']['content'] ?? null;
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
                    'prompt' => $message,
                    'max_tokens' => 100,
                    'temperature' => 0.7,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return trim($data['generations'][0]['text'] ?? '');
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
                    return $data[0]['generated_text'] ?? null;
                }
            } catch (\Exception $e) {}
        }

        // 4. Ollama (lokal - jika terinstall)
        if (env('OLLAMA_ENABLED', false)) {
            try {
                $response = Http::post('http://localhost:11434/api/generate', [
                    'model' => 'llama2',
                    'prompt' => $message,
                    'stream' => false,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['response'] ?? null;
                }
            } catch (\Exception $e) {}
        }

        // Fallback: Smart responses berdasarkan kata kunci
        return $this->getSmartFallbackResponse($message);
    }

    private function getSmartFallbackResponse($message)
    {
        $message = strtolower($message);
        
        // Respon berdasarkan kata kunci
        if (str_contains($message, 'halo') || str_contains($message, 'hai') || str_contains($message, 'hello')) {
            return 'Halo! Senang bertemu dengan Anda. Ada yang bisa saya bantu hari ini?';
        }
        
        if (str_contains($message, 'apa kabar') || str_contains($message, 'bagaimana kabar')) {
            return 'Kabar saya baik, terima kasih! Bagaimana dengan Anda? Ada yang ingin dibicarakan?';
        }
        
        if (str_contains($message, 'siapa') && str_contains($message, 'kamu')) {
            return 'Saya adalah asisten AI yang siap membantu Anda. Saya bisa berdiskusi tentang berbagai topik!';
        }
        
        if (str_contains($message, 'terima kasih') || str_contains($message, 'thanks')) {
            return 'Sama-sama! Senang bisa membantu. Ada lagi yang ingin ditanyakan?';
        }
        
        if (str_contains($message, 'bantuan') || str_contains($message, 'help')) {
            return 'Tentu! Saya di sini untuk membantu. Silakan ceritakan apa yang Anda butuhkan.';
        }
        
        if (str_contains($message, '?')) {
            $responses = [
                'Pertanyaan yang menarik! Menurut saya, hal ini tergantung pada konteks dan situasinya.',
                'Hmm, itu topik yang kompleks. Bisakah Anda memberikan lebih banyak detail?',
                'Saya perlu memikirkan ini lebih dalam. Apa pendapat Anda sendiri tentang hal ini?',
                'Pertanyaan bagus! Mari kita bahas ini lebih lanjut.',
            ];
        } else {
            $responses = [
                'Saya mengerti apa yang Anda maksud. Ceritakan lebih lanjut!',
                'Itu menarik! Bagaimana pengalaman Anda dengan hal tersebut?',
                'Terima kasih sudah berbagi. Saya senang mendengar perspektif Anda.',
                'Saya setuju, topik ini memang layak untuk didiskusikan lebih dalam.',
                'Wah, saya belajar sesuatu yang baru dari Anda hari ini!',
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
        // Format response dengan bullet points dan line breaks
        $response = trim($response);
        
        // Jika response panjang, buat paragraf
        if (strlen($response) > 100) {
            // Split kalimat panjang
            $sentences = preg_split('/[.!?]+/', $response);
            $formatted = [];
            
            foreach ($sentences as $sentence) {
                $sentence = trim($sentence);
                if (strlen($sentence) > 10) {
                    $formatted[] = $sentence;
                }
            }
            
            // Jika ada beberapa poin, format sebagai list
            if (count($formatted) > 2) {
                $result = array_shift($formatted) . ".\n\n";
                foreach ($formatted as $point) {
                    $result .= "• " . $point . ".\n";
                }
                return trim($result);
            }
        }
        
        return $response;
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
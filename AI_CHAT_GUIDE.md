# AI Chat Laravel - Panduan Penggunaan

## Fitur
- ✅ Login & Register
- ✅ Chat dengan AI
- ✅ Simpan riwayat chat
- ✅ UI yang menarik dan responsif
- ✅ Multiple AI API support

## API AI Gratis yang Didukung

### 1. Groq (Recommended) 🚀
- **Kecepatan**: Sangat cepat
- **Model**: Llama3-8B
- **Limit**: Gratis dengan batas harian
- **Cara daftar**: https://console.groq.com/
- **Setting**: `GROQ_API_KEY=your_key`

### 2. Cohere 
- **Model**: Command-Light
- **Limit**: Gratis dengan batas bulanan
- **Cara daftar**: https://cohere.ai/
- **Setting**: `COHERE_API_KEY=your_key`

### 3. Hugging Face
- **Model**: DialoGPT-Medium
- **Limit**: Gratis dengan rate limit
- **Cara daftar**: https://huggingface.co/
- **Setting**: `HUGGINGFACE_API_KEY=your_key`

### 4. Ollama (Lokal)
- **Requirement**: Install Ollama di komputer
- **Model**: Llama2, Mistral, dll
- **Keuntungan**: Tidak perlu internet, unlimited
- **Setting**: `OLLAMA_ENABLED=true`

## Cara Setup

1. **Clone & Install**
```bash
git clone [repo]
cd deepsea
composer install
```

2. **Database**
```bash
php artisan migrate
```

3. **Pilih AI Provider**
- Daftar di salah satu provider di atas
- Copy API key ke file `.env`
- Atau gunakan tanpa API key (fallback responses)

4. **Jalankan**
```bash
php artisan serve
```

## Cara Mendapatkan API Key

### Groq (Tercepat & Gratis)
1. Kunjungi https://console.groq.com/
2. Daftar dengan email
3. Buat API key baru
4. Copy ke `.env`: `GROQ_API_KEY=gsk_...`

### Cohere
1. Kunjungi https://cohere.ai/
2. Sign up gratis
3. Dashboard > API Keys
4. Copy ke `.env`: `COHERE_API_KEY=...`

### Hugging Face
1. Kunjungi https://huggingface.co/
2. Daftar akun
3. Settings > Access Tokens
4. Create new token
5. Copy ke `.env`: `HUGGINGFACE_API_KEY=hf_...`

## Tanpa API Key
Aplikasi tetap berfungsi tanpa API key dengan smart fallback responses yang menyesuaikan dengan input user.

## Struktur Database
- `users`: Data user (name, email, password)
- `chats`: Riwayat chat (user_id, message, response, timestamps)

## UI Features
- Gradient background yang menarik
- Chat bubbles dengan warna berbeda untuk user dan AI
- Real-time chat tanpa refresh
- Responsive design
- Loading states
- Empty state yang informatif

Selamat menggunakan! 🎉
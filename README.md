# 🤖 AI Chat - Asisten Cerdas Anda

<div align="center">
  <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind">
  <img src="https://img.shields.io/badge/SQLite-07405E?style=for-the-badge&logo=sqlite&logoColor=white" alt="SQLite">
</div>

<div align="center">
  <h3>🚀 Aplikasi Chat AI Modern dengan Laravel</h3>
  <p>Asisten AI cerdas yang siap membantu Anda dalam berbagai topik - dari coding hingga resep masakan!</p>
</div>

---

## ✨ **Fitur Unggulan**

### 🎯 **Core Features**
- 🔐 **Authentication System** - Login & Register yang aman
- 💬 **Real-time Chat** - Percakapan langsung dengan AI
- 📚 **Session Management** - Kelola multiple chat sessions
- 💾 **Auto-save History** - Riwayat chat tersimpan otomatis
- 🏷️ **Smart Titles** - Auto-generate judul berdasarkan topik

### 🤖 **AI Integration**
- ⚡ **Groq API** - Response super cepat dengan Llama3-8B
- 🔄 **Multiple Providers** - Fallback ke Cohere, Hugging Face
- 🎨 **Smart Formatting** - Response AI dengan bullet points
- 🧠 **Context Aware** - Memahami berbagai topik percakapan

### 🎨 **UI/UX Excellence**
- 🌙 **Dark Theme** - Design modern dengan gradient
- 📱 **Responsive** - Perfect di desktop dan mobile
- ✨ **Glassmorphism** - Efek blur dan transparansi
- 🎭 **Smooth Animations** - Transisi yang halus

---

## 🚀 **Quick Start**

### 📋 **Requirements**
- PHP 8.1+
- Composer
- Node.js & NPM

### ⚡ **Installation**

```bash
# Clone repository
git clone https://github.com/WiMProject/aichat.git
cd aichat

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate

# Start development server
php artisan serve
```

### 🔑 **API Configuration**

Tambahkan API key di file `.env`:

```env
# Pilih salah satu atau beberapa untuk fallback
GROQ_API_KEY=your_groq_key_here          # Recommended - Super Fast!
COHERE_API_KEY=your_cohere_key_here      # Alternative
HUGGINGFACE_API_KEY=your_hf_key_here     # Backup option
```

---

## 🎯 **Supported AI Providers**

| Provider | Speed | Model | Free Tier | Get API Key |
|----------|-------|-------|-----------|-------------|
| **🚀 Groq** | ⚡ Super Fast | Llama3-8B | ✅ Yes | [console.groq.com](https://console.groq.com) |
| **🔮 Cohere** | 🏃 Fast | Command-Light | ✅ Yes | [cohere.ai](https://cohere.ai) |
| **🤗 Hugging Face** | 🚶 Medium | DialoGPT | ✅ Yes | [huggingface.co](https://huggingface.co) |
| **🏠 Ollama** | 🐌 Local | Custom | ♾️ Unlimited | [ollama.ai](https://ollama.ai) |

---

## 📱 **Screenshots**

### 🏠 Landing Page
<div align="center">
  <img src="https://via.placeholder.com/800x400/6366f1/ffffff?text=Beautiful+Landing+Page" alt="Landing Page" width="600">
</div>

### 💬 Chat Interface
<div align="center">
  <img src="https://via.placeholder.com/800x400/8b5cf6/ffffff?text=Modern+Chat+Interface" alt="Chat Interface" width="600">
</div>

---

## 🎨 **Topik yang Didukung**

<div align="center">

| 💻 **Tech** | 🍳 **Food** | 🏥 **Health** | 💼 **Business** |
|-------------|-------------|---------------|-----------------|
| Programming | Resep Masakan | Kesehatan | Marketing |
| Web Dev | Cooking Tips | Medical Info | Strategy |
| Debugging | Nutrition | Fitness | Finance |

| 📚 **Education** | ✈️ **Travel** | 🎵 **Music** | 🏃 **Sports** |
|------------------|---------------|--------------|---------------|
| Learning | Destinations | Songs | Fitness |
| Tutorials | Planning | Artists | Training |
| Courses | Reviews | Genres | Equipment |

</div>

---

## 🛠️ **Tech Stack**

### 🔧 **Backend**
- **Laravel 11** - PHP Framework
- **SQLite** - Lightweight Database
- **Eloquent ORM** - Database Relations

### 🎨 **Frontend**
- **Tailwind CSS** - Utility-first CSS
- **Vanilla JavaScript** - Real-time interactions
- **Blade Templates** - Server-side rendering

### 🤖 **AI Integration**
- **Groq API** - Primary AI provider
- **HTTP Client** - API communications
- **Smart Fallbacks** - Multiple provider support

---

## 📁 **Project Structure**

```
aichat/
├── 🎨 resources/views/
│   ├── welcome.blade.php      # Landing page
│   ├── auth/                  # Login & Register
│   └── chat/                  # Chat interface
├── 🔧 app/
│   ├── Models/                # User, Chat, ChatSession
│   └── Http/Controllers/      # Auth & Chat logic
├── 🗄️ database/
│   └── migrations/            # Database schema
└── 🌐 routes/
    └── web.php               # Application routes
```

---

## 🚀 **Deployment**

### 🌐 **Production Setup**

```bash
# Optimize for production
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set environment
APP_ENV=production
APP_DEBUG=false
```

### ☁️ **Hosting Options**
- **Shared Hosting** - Upload via FTP
- **VPS** - Full server control
- **Laravel Forge** - Automated deployment
- **Heroku** - Easy cloud deployment

---

## 🤝 **Contributing**

Kontribusi sangat diterima! Silakan:

1. 🍴 Fork repository ini
2. 🌿 Buat feature branch (`git checkout -b feature/amazing-feature`)
3. 💾 Commit changes (`git commit -m 'Add amazing feature'`)
4. 📤 Push ke branch (`git push origin feature/amazing-feature`)
5. 🔄 Buat Pull Request

---

## 📄 **License**

Project ini menggunakan [MIT License](LICENSE) - bebas digunakan untuk keperluan apapun.

---

## 🙏 **Acknowledgments**

- **Laravel Team** - Framework yang luar biasa
- **Groq** - AI API yang super cepat
- **Tailwind CSS** - Styling yang efisien
- **Community** - Dukungan dan feedback

---

<div align="center">
  <h3>⭐ Jika project ini membantu, berikan star ya!</h3>
  <p>Made with ❤️ by <a href="https://github.com/WiMProject">WiM Project</a></p>
  
  <a href="https://github.com/WiMProject/aichat/stargazers">
    <img src="https://img.shields.io/github/stars/WiMProject/aichat?style=social" alt="Stars">
  </a>
  <a href="https://github.com/WiMProject/aichat/network/members">
    <img src="https://img.shields.io/github/forks/WiMProject/aichat?style=social" alt="Forks">
  </a>
</div>

---

## 📞 **Support**

Butuh bantuan? Hubungi kami:
- 📧 Email: wildanmiladji53@gmail.com

**Happy Chatting! 🚀**
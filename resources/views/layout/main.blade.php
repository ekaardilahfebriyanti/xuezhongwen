<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title', 'Xue ZhongWen')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
  <meta name="description" content="Mulai belajar Mandarin dari nol, HSK, percakapan, dan budaya Tiongkok. Materi lengkap, interaktif, dan mudah dipahami. Jelajahi bahasa Tiongkok bersama XueZhongWen!">
    
  <meta name="keywords" content="belajar mandarin, bahasa mandarin online, kursus HSK, percakapan mandarin, kosakata hanyu, XueZhongWen, , budaya Tiongkok, codellillah">
  
  <link rel="canonical" href="https://xuezhongwen.codellillah.com/">
  
  <meta property="og:title" content="Belajar Bahasa Mandarin Mudah & Praktis -  Xue ZhongWen">
  <meta property="og:description" content="Kuasai HSK dan percakapan sehari-hari. Dapatkan materi lengkap, latihan interaktif, dan tips budaya Tiongkok di sini!">
  <meta property="og:url" content="https://xuezhongwen.codellillah.com/">
  <meta property="og:image" content="{{ asset('img/logo.png') }}">
  <meta property="og:type" content="website">
  <meta property="og:locale" content="id_ID">

</head>
<body>

  <div class="page-wrapper d-flex flex-column min-vh-100">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
      <div class="container position-relative">
        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="navbar-logo">
        <a class="navbar-brand" href="{{ url('/') }}">
          Xue ZhongWen
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarNav" aria-controls="navbarNav" 
                aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto align-items-center">
            <li class="nav-item dropdown mx-2">
              <a class="nav-link dropdown-toggle" href="#" id="listeningDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Listening
              </a>
              <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="listeningDropdown">
                <li><a class="dropdown-item" href="/listening">Listening</a></li>
                <li><a class="dropdown-item" href="/podcast">Podcast</a></li>
              </ul>
            </li>
            <li class="nav-item dropdown mx-2">
              <a class="nav-link dropdown-toggle" href="#" id="speakingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Speaking
              </a>
              <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="speakingDropdown">
                <li><a class="dropdown-item" href="/speaking">Speaking</a></li>
                <li><a class="dropdown-item" href="/pinyin-interactive-chart">Pinyin Chart</a></li>
              </ul>
            </li>
            <li class="nav-item dropdown mx-2">
              <a class="nav-link dropdown-toggle" href="#" id="readingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Reading
              </a>
              <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="readingDropdown">
                <li><a class="dropdown-item" href="/modul">Modul</a></li>
                <li><a class="dropdown-item" href="/book">Reading</a></li>
              </ul>
            </li>
            <li class="nav-item mx-2">
              <a class="nav-link" href="/write-pinyin">Writing</a>
            </li>
            <li class="nav-item dropdown mx-2">
              <a class="nav-link dropdown-toggle" href="#" id="testDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Test & Quiz
              </a>
              <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="testDropdown">
                <li><a class="dropdown-item" href="/flashcard">Flashcard</a></li>
                <li><a class="dropdown-item" href="/quiz">Quiz</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Konten Utama -->
    <main class="flex-grow-1">
      @yield('content')
    </main>

    <!-- Footer -->
    <footer class="text-center py-4 bg-dark text-light">
      <p>© 2025 Xue ZhongWen | Belajar Mandarin lebih Mudah</p>
    </footer>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  @yield('js')

</body>
</html>

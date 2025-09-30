@extends('layout.main')

@section('title', 'Xue ZhongWen')

@section('content')

  <!-- Hero Slider -->
  <div id="mandarinSlider" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">

      <!-- Slide 1 -->
      <div class="carousel-item active">
        <img src="{{ asset('img/img1.jpg') }}" class="d-block w-100" alt="Belajar Mandarin">
        <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
          <h1 class="fw-bold">Belajar Mandarin lebih Mudah</h1>
          <p>Mendengarkan, Berbicara, Membaca, Menulis, dan Kuis semua dalam satu tempat.</p>
          <a href="#listening" class="btn btn-light btn-lg mt-3">Mulai Belajar</a>
        </div>
      </div>

      <!-- Slide 2 -->
      <div class="carousel-item">
        <img src="{{ asset('img/img3.png') }}" class="d-block w-100" alt="Latihan Mendengarkan">
        <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
          <h1 class="fw-bold">🎧 Latihan Mendengarkan</h1>
          <p>Tingkatkan pemahaman dengan audio & dialog asli bahasa Mandarin.</p>
        </div>
      </div>

      <!-- Slide 3 -->
      <div class="carousel-item">
        <img src="{{ asset('img/img4.jpg') }}" class="d-block w-100" alt="Menulis Hanzi">
        <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
          <h1 class="fw-bold">Belajar Menulis Hanzi</h1>
          <p>Kuasi urutan goresan dan pembentukan karakter langkah demi langkah.</p>
        </div>
      </div>

    </div>

    <!-- Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#mandarinSlider" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#mandarinSlider" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
  </div>

  <!-- Features Section -->
  <section class="container my-5">
    <div class="row g-4">
      
      <!-- Listening -->
      <div class="col-md-6" id="listening">
        <a href="{{ url('/listening') }}" class="text-decoration-none">
          <div class="card p-4 text-center h-100">
            <h3>🎧 Listening</h3>
            <p>Latih kemampuan mendengarkan dengan contoh audio asli bahasa Mandarin.</p>
          </div>
        </a>
      </div>

      <!-- Speaking -->
      <div class="col-md-6" id="speaking">
        <a href="{{ url('/speaking') }}" class="text-decoration-none">
          <div class="card p-4 text-center h-100">
            <h3>🗣️ Speaking</h3>
            <p>Tingkatkan pengucapan dengan latihan berbicara dan percakapan.</p>
          </div>
        </a>
      </div>

      <!-- Reading -->
      <div class="col-md-6" id="reading">
        <a href="{{ url('/book') }}" class="text-decoration-none">
          <div class="card p-4 text-center h-100">
            <h3>📖 Reading</h3>
            <p>Baca teks Mandarin dari tingkat sederhana hingga lanjutan dengan pinyin dan terjemahan.</p>
          </div>
        </a>
      </div>

      <!-- Writing -->
      <div class="col-md-6" id="writing">
        <a href="{{ url('/write-pinyin') }}" class="text-decoration-none">
          <div class="card p-4 text-center h-100">
            <h3>Writing</h3>
            <p>Pelajari cara menulis karakter Hanzi langkah demi langkah dengan urutan goresan.</p>
          </div>
        </a>
      </div>

      <!-- Test & Quiz -->
      <div class="col-md-12" id="test">
        <a href="{{ url('/quiz') }}" class="text-decoration-none">
          <div class="card p-4 text-center h-100">
            <h3>📝 Tes & Kuis</h3>
            <p>Tantang dirimu dengan kuis interaktif dan flashcard.</p>
          </div>
        </a>
      </div>
      
    </div>
  </section>

@endsection

@extends('layout.main')

@section('title', 'Belajar Bahasa Mandarin Lewat Podcast')

@section('content')

<h1 class="learn-chinese-slider-title">Belajar Bahasa Mandarin Lewat Podcast</h1>

<div class="learn-chinese-slider-container" role="list">
  <div class="learn-chinese-slider-card" role="listitem" tabindex="0" aria-label="Tantangan Mendengarkan Bahasa Mandarin – 5 Menit Sehari">
    <h2>🎧 Tantangan Mendengarkan Bahasa Mandarin – 5 Menit Sehari</h2>
    <iframe 
      src="https://www.youtube.com/embed/videoseries?list=PLUDu13smv-2RPVlajf6i0TDo9KTDrQsu0" 
      allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
      allowfullscreen
      title="Tantangan Mendengarkan Bahasa Mandarin – 5 Menit Sehari"
      allow="autoplay"
    ></iframe>
  </div>

  <div class="learn-chinese-slider-card" role="listitem" tabindex="0" aria-label="Percakapan Sehari-hari dalam Bahasa Mandarin">
    <h2>🎧 Percakapan Sehari-hari dalam Bahasa Mandarin</h2>
    <iframe 
      src="https://www.youtube.com/embed/videoseries?list=PLUDu13smv-2SoqqI6n8Hp5IuJ4INlXvQv" 
      allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
      allowfullscreen
      title="Percakapan Sehari-hari dalam Bahasa Mandarin"
      allow="autoplay"
    ></iframe>
  </div>

  <div class="learn-chinese-slider-card" role="listitem" tabindex="0" aria-label="Belajar Bahasa Mandarin dengan Pola Pikir Positif | 成长型思维 + 中文学习">
    <h2>🎧 Belajar Bahasa Mandarin dengan Pola Pikir Positif | 成长型思维 + 中文学习</h2>
    <iframe 
      src="https://www.youtube.com/embed/videoseries?list=PLUDu13smv-2RYI6y4NKL9TsMxM60ieBVj" 
      allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
      allowfullscreen
      title="Belajar Bahasa Mandarin dengan Pola Pikir Positif"
      allow="autoplay"
    ></iframe>
  </div>
  
  <div class="learn-chinese-slider-card" role="listitem" tabindex="0" aria-label="Podcast Pembelajaran Bahasa Mandarin untuk Pemula">
    <h2>🎧 Podcast Pembelajaran Bahasa Mandarin untuk Pemula</h2>
    <iframe 
      src="https://www.youtube.com/embed/videoseries?list=PL7Gww2wKyYSkB1GRZCgTk5QjqDBq3KxyX" 
      allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
      allowfullscreen
      title="Podcast Pembelajaran Bahasa Mandarin untuk Pemula"
      allow="autoplay"
    ></iframe>
  </div>
</div>
@endsection
@section('js')
<script>
  const cards = document.querySelectorAll('.learn-chinese-slider-card');
  
  function deactivateAll() {
    cards.forEach(card => {
      card.classList.remove('active');
      card.querySelector('iframe').style.pointerEvents = 'none';
    });
  }
  
  cards.forEach(card => {
    card.addEventListener('click', () => {
      if(card.classList.contains('active')) {
        deactivateAll();
      } else {
        deactivateAll();
        card.classList.add('active');
        card.querySelector('iframe').style.pointerEvents = 'auto';
        card.scrollIntoView({behavior: 'smooth', block: 'center', inline: 'center'});
      }
    });

    card.addEventListener('keydown', e => {
      if(e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        card.click();
      }
    });
  });

  document.body.addEventListener('click', e => {
    if (!e.target.closest('.learn-chinese-slider-card')) {
      deactivateAll();
    }
  });
</script>
@endsection

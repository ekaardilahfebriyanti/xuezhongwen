@extends('layout.main')

@section('title', 'Daftar Buku Bacaan Mandarin')

@section('content')
  <div class="mandarin-book-reader my-5">
    <div class="book-embed"> 
      <div class="book-title">Chinese Made Super Easy</div> 
      <button onclick="showBook(this, 'https://archive.org/embed/171363051-chinese-made-super-easy-a-super')">📖 Buka Buku</button> 
      <iframe></iframe> </div> <div class="book-embed"> <div class="book-title">Easy Steps To Chinese Pinyin</div> <button onclick="showBook(this, 'https://archive.org/embed/EasyStepsToChinesePinyin')">📖 Buka Buku</button> <iframe></iframe> 
    </div> 
    <div class="book-embed"> 
      <div class="book-title">15-Minute Mandarin Chinese</div> 
      <button onclick="showBook(this, 'https://archive.org/embed/15minutemandarin0000chen')">📖 Buka Buku</button> <iframe></iframe> 
    </div> 
    <div class="book-embed"> 
      <div class="book-title">Chinese Made Easy, Level 2 Workbook</div> 
      <button onclick="showBook(this, 'https://archive.org/embed/chinesemadeeasyl0000maya')">📖 Buka Buku</button> <iframe></iframe> 
    </div> 
    <div class="book-embed"> 
      <div class="book-title">Learning Taiwanese Mandarin. Practical Audio-Visual Chinese Vol.1</div> <button onclick="showBook(this, 'https://archive.org/embed/00_20231027_202310')">📖 Buka Buku</button> <iframe></iframe> 
    </div> 
    <div class="book-embed"> 
      <div class="book-title">Chinese for Beginners: Mastering Conversational Chinese</div> <button onclick="showBook(this, 'https://archive.org/embed/chineseforbeginn0000reny')">📖 Buka Buku</button> <iframe></iframe> 
    </div> 
    <div class="book-embed"> 
      <div class="book-title">Integrated Chinese: 中文听说读写 (Textbook Simplified Characters)</div> <button onclick="showBook(this, 'https://archive.org/embed/integratedchines11liuy')">📖 Buka Buku</button> <iframe></iframe> 
    </div> 
    <div class="book-embed"> 
      <div class="book-title">Speak Mandarin in Five Hundred Words (Simplified Chinese Edition)</div> <button onclick="showBook(this, 'https://archive.org/embed/SpeakMandarinInFiveHundredWordsSimplifiedChineseEdition')">📖 Buka Buku</button> <iframe></iframe> </div> 
    <div class="book-embed"> 
      <div class="book-title">The Chinese language and how to learn it; a manual for beginners</div> <button onclick="showBook(this, 'https://archive.org/embed/chineselanguageh00hilliala')">📖 Buka Buku</button> <iframe></iframe> 
    </div>
  </div>
@endsection

@section('js')
  <script>
    function showBook(button, url) {
      const iframe = button.nextElementSibling;

      if (iframe.style.display === "block") {
        iframe.src = "";
        iframe.style.display = "none";
        button.textContent = "📖 Buka Buku";
        return;
      }

      document.querySelectorAll(".mandarin-book-reader .book-embed iframe").forEach(f => {
        f.src = "";
        f.style.display = "none";
      });
      document.querySelectorAll(".mandarin-book-reader .book-embed button").forEach(btn => {
        btn.textContent = "📖 Buka Buku";
      });

      iframe.src = url;
      iframe.style.display = "block";
      button.textContent = "⬆️ Tutup Buku";
    }
  </script>
@endsection

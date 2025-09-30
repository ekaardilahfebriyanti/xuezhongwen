@extends('layout.main')

@section('title', 'Modul Belajar Mandarin')

@section('content')
  <div class="modul my-5">
    <div class="book-embed">
      <div class="book-title">HSK 1 – Workbook</div>
      <button onclick="showBook(this, 'https://drive.google.com/file/d/1bUmuCQh7pReBr14s9jMBHqmhAag8rDGd/preview')">📖 Buka Buku</button>
      <iframe></iframe>
    </div>

    <div class="book-embed">
    <div class="book-title">HSK 2 – Textbook</div>
    <button onclick="showBook(this, 'https://drive.google.com/file/d/1zCwMBG2SORIVY_cfD5sWJ41cUwi5VQeg/preview')">📖 Buka Buku</button>
    <iframe></iframe>
    </div>

    <div class="book-embed">
    <div class="book-title">HSK 2 – Workbook</div>
    <button onclick="showBook(this, 'https://drive.google.com/file/d/1ZPK9jtbn3IQEgk1OCd_cw9TYBbBW52RG/preview')">📖 Buka Buku</button>
    <iframe></iframe>
    </div>

    <div class="book-embed">
    <div class="book-title">HSK 3 – Textbook</div>
    <button onclick="showBook(this, 'https://drive.google.com/file/d/1nIZq1sEMn_Izn8MoKDzAkZSZ6jx7ok2i/preview')">📖 Buka Buku</button>
    <iframe></iframe>
    </div>

    <div class="book-embed">
    <div class="book-title">HSK 4 – Textbook Level 1</div>
    <button onclick="showBook(this, 'https://drive.google.com/file/d/1v5JweAWtw_GgNcX5t1DRK4DSqX2rtM61/preview')">📖 Buka Buku</button>
    <iframe></iframe>
    </div>

    <div class="book-embed">
    <div class="book-title">HSK 4 – Textbook Level 2</div>
    <button onclick="showBook(this, 'https://drive.google.com/file/d/1Lmw72n58cVoA9BaGMcWUmlzyeBxnyYtD/preview')">📖 Buka Buku</button>
    <iframe></iframe>
    </div>

    <div class="book-embed">
    <div class="book-title">HSK 4 – Workbook Level 1</div>
    <button onclick="showBook(this, 'https://drive.google.com/file/d/1fVInJgbB7lEC_bpJSLmBEu59uOMqV6a8/preview')">📖 Buka Buku</button>
    <iframe></iframe>
    </div>

    <div class="book-embed">
    <div class="book-title">HSK 4 – Workbook Level 2</div>
    <button onclick="showBook(this, 'https://drive.google.com/file/d/1QucxA2vN74KhhWGYxPk098bpczqm5vO1/preview')">📖 Buka Buku</button>
    <iframe></iframe>
    </div>

    <div class="book-embed">
    <div class="book-title">HSK 5 – Textbook Level 1</div>
    <button onclick="showBook(this, 'https://drive.google.com/file/d/12wzwm56Z2j6Y5G1jHB1GADHTdEGLJ526/preview')">📖 Buka Buku</button>
    <iframe></iframe>
    </div>

    <div class="book-embed">
    <div class="book-title">HSK 5 – Textbook Level 2</div>
    <button onclick="showBook(this, 'https://drive.google.com/file/d/135rfGZo3urR8SdRqR2Fwq7WEbA8Rjd5P/preview')">📖 Buka Buku</button>
    <iframe></iframe>
    </div>

    <div class="book-embed">
    <div class="book-title">HSK 5 – Workbook 1</div>
    <button onclick="showBook(this, 'https://drive.google.com/file/d/12Ut5p3Y1-_6VzwVmKIPmVLsX-BzabcjA/preview')">📖 Buka Buku</button>
    <iframe></iframe>
    </div>

    <div class="book-embed">
    <div class="book-title">HSK 5 – Workbook 2</div>
    <button onclick="showBook(this, 'https://drive.google.com/file/d/12_FMUQGa1NtSHmM6wHuD5-UBA6keUSG6/preview')">📖 Buka Buku</button>
    <iframe></iframe>
    </div>

    <div class="book-embed">
    <div class="book-title">HSK 6 – Textbook Level 1</div>
    <button onclick="showBook(this, 'https://drive.google.com/file/d/1LNIiM_7dM4hJncOC4Rko_WNTmAwL4GjZ/preview')">📖 Buka Buku</button>
    <iframe></iframe>
    </div>
    <div class="book-embed">
    <div class="book-title">HSK 6 – Workbook 1</div>
    <button onclick="showBook(this, 'https://drive.google.com/file/d/11Qgogc3spN6NCEZXHHk-Jotvr6CwJrRd/preview')">📖 Buka Buku</button>
    <iframe></iframe>
    </div>

    <div class="book-embed">
    <div class="book-title">HSK 6 – Workbook 2</div>
    <button onclick="showBook(this, 'https://drive.google.com/file/d/1yunrigvr3Coj4MQhzl8rAjPeHBWSdnWq/preview')">📖 Buka Buku</button>
    <iframe></iframe>
    </div>


    <div class="book-embed">
      <div class="book-title">Elementary Chinese Course (Han yu chu ji jiao cheng)</div>
      <button onclick="showBook(this, 'https://archive.org/embed/hanyuchujijiaoch0000unse_n9w2')">📖 Buka Buku</button>
      <iframe></iframe>
    </div>

    <div class="book-embed">
      <div class="book-title">Developing Chinese (Elementary Comprehensive Course)</div>
      <button onclick="showBook(this, 'https://archive.org/embed/fazhanhanyuchuji0000unse')">📖 Buka Buku</button>
      <iframe></iframe>
    </div>

    <div class="book-embed">
      <div class="book-title">Integrated Chinese (听说读写)</div>
      <button onclick="showBook(this, 'https://archive.org/embed/integratedchines11liuy')">📖 Buka Buku</button>
      <iframe></iframe>
    </div>

    <div class="book-embed">
      <div class="book-title">Comprehensive Chinese (Han yu zong he jiao cheng)</div>
      <button onclick="showBook(this, 'https://archive.org/embed/hanyuzonghejiaoc0000unse')">📖 Buka Buku</button>
      <iframe></iframe>
    </div>

    <div class="book-embed">
      <div class="book-title">Hanyu Yuedu Jiaocheng 1 (Reading Course)</div>
      <button onclick="showBook(this, 'https://archive.org/embed/hanyuyinianjiyuedujiaocheng11')">📖 Buka Buku</button>
      <iframe></iframe>
    </div>
    <div class="book-embed">
      <div class="book-title">Integrated Chinese (Volume) </div>
      <button onclick="showBook(this, 'https://archive.org/embed/integratedchines12liuy')">📖 Buka Buku</button>
      <iframe></iframe>
    </div>

    <div class="book-embed">
      <div class="book-title">Far East Everyday Chinese Book 1</div>
      <button onclick="showBook(this, 'https://archive.org/embed/yehtehmingfareasteverydaychinesebook1traditionalcharacter')">📖 Buka Buku</button>
      <iframe></iframe>
    </div>

    <div class="book-embed">
      <div class="book-title">Teach Yourself Chinese</div>
      <button onclick="showBook(this, 'https://archive.org/embed/in.ernet.dli.2015.125070')">📖 Buka Buku</button>
      <iframe></iframe>
    </div>

    <div class="book-embed">
      <div class="book-title">Integrated Chinese Level 2, Part 1</div>
      <button onclick="showBook(this, 'https://archive.org/embed/integrated-chinese-level-2-part-1')">📖 Buka Buku</button>
      <iframe></iframe>
    </div>

    <div class="book-embed">
      <div class="book-title">Modern Chinese 2B</div>
      <button onclick="showBook(this, 'https://archive.org/embed/modernchinesexia0000unse')">📖 Buka Buku</button>
      <iframe></iframe>
    </div>

    <div class="book-embed">
      <div class="book-title">Modern Chinese 1A (Simplified)</div>
      <button onclick="showBook(this, 'https://archive.org/embed/modernchinesesim0000bett')">📖 Buka Buku</button>
      <iframe></iframe>
    </div>
  </div>
@endsection

@section('js')
  <script>
    function showBook(button, url) {
      const iframe = button.nextElementSibling;

      // Jika iframe sudah terbuka, tutup
      if (iframe.style.display === "block") {
        iframe.src = "";
        iframe.style.display = "none";
        button.textContent = "📖 Buka Buku";
        return;
      }

      // Tutup semua dan reset tombol dulu
      document.querySelectorAll(".book-embed iframe").forEach(f => {
        f.src = "";
        f.style.display = "none";
      });
      document.querySelectorAll(".book-embed button").forEach(btn => {
        btn.textContent = "📖 Buka Buku";
      });

      // Buka iframe target
      iframe.src = url;
      iframe.style.display = "block";
      button.textContent = "⬆️ Tutup Buku";
    }
  </script>
@endsection

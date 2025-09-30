# 🍜 Jelajah Mandarin (Xue Zhongwen)

Aplikasi untuk belajar bahasa Mandarin yang dikembangkan dengan **Laravel** dan **Bootstrap**.

---

## 💡 Deskripsi Proyek

**Xue Zhongwen** (Jelajah Mandarin) adalah aplikasi interaktif yang dirancang untuk membantu pengguna menguasai bahasa Mandarin melalui empat keterampilan utama: speaking, listening, reading, dan writing. Aplikasi ini memanfaatkan teknologi **Google TTS (Text-to-Speech Engine)** untuk menyediakan pengalaman belajar yang dinamis dan otentik.

---

## 🛠️ Teknologi yang Digunakan

| Kategori     | Teknologi                | Keterangan                                         |
| ------------ | ----------------------- | ------------------------------------------------- |
| **Frontend** | Bootstrap                | CSS Framework untuk desain responsif dan UI.      |
| **Interaktif**| Google TTS              | Digunakan untuk fitur speaking dan listening audio.|
| **Dependencies** | Composer & NPM        | Pengelolaan paket PHP dan frontend (JS/CSS).      |

---

## ✨ Fitur Utama

Aplikasi ini menyediakan modul latihan fokus pada:

* **Speaking:** Latihan pengucapan didukung oleh audio Google TTS.  
* **Listening:** Pengenalan dan pemahaman audio pinyin dan kalimat Mandarin.  
* **Reading:** Menyediakan chart pinyin interaktif dan flashcards untuk memperkaya kosakata.  
* **Writing:** Modul sederhana untuk pengenalan dan latihan urutan coretan karakter Hanzi.
* **Tes dan Quiz:** Modul sederhana untuk quiz berisi flashcard dan menebak karakter hanzi.

---

## ⚙️ Setup Instructions (Instruksi Pemasangan)

Ikuti langkah-langkah berikut untuk menjalankan aplikasi secara lokal.

### Persyaratan

- PHP 7.4.19 (cli)  
- Laravel Installer 4.5.1  
- Composer  
- Node.js  
- MySQL/MariaDB

### Langkah-langkah

1. **Clone Repository:**  
    ```bash
    git clone <repo-url>
    cd <nama-folder>
    ```

2. **Install Dependencies Backend:**  
    ```bash
    composer install
    ```

3. **Konfigurasi Environment:**  
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Konfigurasi Database:**  
    Edit `.env` sesuai konfigurasi database Anda.

5. **Jalankan Migrasi:**  
    ```bash
    php artisan migrate
    ```

6. **(Opsional) Compile Assets Frontend:**  
    ```bash
    npm install
    npm run dev
    ```

7. **Jalankan Server:**  
    ```bash
    php artisan serve
    ```  
    Akses di: **http://127.0.0.1:8000**

---

## 🤝 Cara Berkontribusi

Kami sangat menyambut kontribusi dari siapa saja!  
Untuk berkontribusi:

1. Fork repository ini  
2. Buat branch fitur/bugfix kamu: `git checkout -b fitur-baru`  
3. Commit perubahan kamu: `git commit -m "Tambah fitur baru"`  
4. Push ke branch kamu: `git push origin fitur-baru`  
5. Buat Pull Request di repo utama

---

## 📜 Lisensi & Atribusi

Kecuali dinyatakan lain, proyek ini dilisensikan di bawah ketentuan **Lisensi MIT**.

Terima kasih kepada kontributor open-source berikut atas data yang digunakan dalam aplikasi ini:

* **Pinyin Chart Sound Files:** [mp3-chinese-pinyin-sound](https://github.com/davinfifield/mp3-chinese-pinyin-sound)  
* **Listening Audio Files:** [learn-chinese-by-listening](https://github.com/bandinopla/learn-chinese-by-listening)  
* **Flashcard Data:** [HSK-Flashcards](https://github.com/michcqge/HSK-Flashcards)  

---

## 📬 Kontak

Jika ada pertanyaan atau diskusi, silakan hubungi saya di:  
**Email:** ardilaheka@gmail.com  

---

## 🤖 Dukungan AI

Pengembangan aplikasi ini dibantu oleh **AI** dalam berbagai aspek, termasuk desain aplikasi, pembuatan kode, perbaikan fungsi, dan optimasi kode program.
"# xuezhongwen" 

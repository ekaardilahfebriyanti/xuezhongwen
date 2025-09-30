@extends('layout.main')

@section('title', 'Kuis Input Hanzi')

@section('content')
    <div class="quiz my-5">
        <div class="container">

            <div id="levelSelector">
                <h1>Pilih Level HSK</h1>
                <div id="levelButtonContainer"></div>
            </div>

            <div id="quizContainer">
                <div id="wordDisplay">
                    <div id="hanzi">…</div>
                    <div id="pinyin">Pinyin akan muncul setelah menjawab</div>
                    <div class="translation-info">Indonesia: <span id="indonesianTranslation">…</span></div>
                    <button id="ttsBtn" onclick="readWord()" title="Dengarkan suara kata" aria-label="Dengarkan suara kata" disabled>🔊</button>
                </div>

                <div id="result">Ketik Hanzi yang tepat</div>

                <div id="inputContainer">
                    <input
                        type="text"
                        id="answerInput"
                        placeholder="Ketik Hanzi di sini"
                        oninput="filterNonHanzi(this)"
                        autocomplete="off"
                        spellcheck="false"
                    />
                    <button id="submitBtn" onclick="submitAnswer()">Cek</button>
                </div>

                <button id="nextBtn" disabled onclick="nextQuestion()">Pertanyaan Selanjutnya →</button>

                <div id="scoreDisplay">Skor: 0 / 0</div>

                <button id="restartBtn" onclick="showLevelSelector()">Ganti Level</button>
            </div>

        </div>
    </div>
@endsection

@section('js')
    <script>
        let vocabData = [];
        let currentWords = [];
        let currentIndex = 0;
        let score = 0;
        let answered = false;

        const levelSelectorEl = document.getElementById('levelSelector');
        const levelButtonContainerEl = document.getElementById('levelButtonContainer');
        const quizContainerEl = document.getElementById('quizContainer');
        const hanziEl = document.getElementById('hanzi');
        const pinyinEl = document.getElementById('pinyin');
        // const englishTranslationEl = document.getElementById('englishTranslation');
        const resultEl = document.getElementById('result');
        const answerInput = document.getElementById('answerInput');
        const submitBtn = document.getElementById('submitBtn');
        const nextBtn = document.getElementById('nextBtn');
        const scoreDisplay = document.getElementById('scoreDisplay');
        const ttsBtn = document.getElementById('ttsBtn');
        const indonesianTranslationEl = document.getElementById('indonesianTranslation');

        // Data fallback (kalau mau)
        const FALLBACK_DATA = {
            1: [
                { "other": "你", "pinyin": "nǐ", "english": "you" },
                { "other": "好", "pinyin": "hǎo", "english": "good/well" },
                { "other": "我", "pinyin": "wǒ", "english": "I/me" }
            ]
        };

        function filterNonHanzi(inputEl) {
            inputEl.value = inputEl.value.replace(/[^\u4e00-\u9fa5]/g, '');
        }

        function initLevelButtons() {
            for (let lvl = 1; lvl <= 6; lvl++) {
                const btn = document.createElement('button');
                btn.textContent = `HSK ${lvl}`;
                btn.setAttribute('aria-label', `Pilih level HSK ${lvl}`);
                btn.onclick = () => loadLevel(lvl);
                levelButtonContainerEl.appendChild(btn);
            }
        }

        async function loadLevel(lvl) {
            const path = `{{ asset('HSK-Flashcards/public/hsk') }}${lvl}.json`;

            try {
                const resp = await fetch(path);
                if (!resp.ok) {
                    throw new Error(`Gagal memuat file HSK ${lvl}.`);
                }
                vocabData = await resp.json();
                startQuiz();
            } catch (error) {
                console.error('Error saat load data:', error.message);
                if (lvl === 1 && FALLBACK_DATA[1]) {
                    alert(`Gagal memuat file JSON dari server. Menggunakan data HSK 1 fallback: ${FALLBACK_DATA[1].length} kata.`);
                    vocabData = FALLBACK_DATA[1];
                    startQuiz();
                } else {
                    alert('Error: Gagal memuat data dan tidak ada data fallback yang tersedia.');
                    showLevelSelector();
                }
            }
        }

        function showLevelSelector() {
            quizContainerEl.style.display = 'none';
            levelSelectorEl.style.display = 'block';
            currentIndex = 0;
            score = 0;
            scoreDisplay.textContent = `Skor: 0 / 0`;
            ttsBtn.disabled = true;
            answerInput.disabled = false;
            submitBtn.disabled = false;
            nextBtn.disabled = true;
            resultEl.textContent = "Ketik Hanzi yang tepat";
            hanziEl.textContent = "…";
            pinyinEl.textContent = "Pinyin akan muncul setelah menjawab";
            // englishTranslationEl.textContent = "…";
            answerInput.value = "";
        }

        function startQuiz() {
            currentWords = [...vocabData];
            shuffle(currentWords);
            currentIndex = 0;
            score = 0;

            levelSelectorEl.style.display = 'none';
            quizContainerEl.style.display = 'block';

            loadQuestion();
        }

        function shuffle(arr) {
            for (let i = arr.length -1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i+1));
                [arr[i], arr[j]] = [arr[j], arr[i]];
            }
        }

        function loadQuestion() {
            if (currentIndex >= currentWords.length) {
                hanziEl.textContent = `🎉 Kuis selesai! Skor: ${score}/${currentWords.length}`;
                pinyinEl.textContent = "";
                // englishTranslationEl.textContent = "";
                answerInput.disabled = true;
                submitBtn.disabled = true;
                nextBtn.disabled = true;
                ttsBtn.disabled = true;
                resultEl.textContent = "";
                return;
            }
            const word = currentWords[currentIndex];

            // Menampilkan terjemahan (English) sebagai clue
            hanziEl.textContent = word.english || "Terjemahan...";
            pinyinEl.textContent = "Tebak Hanzi…";
            // englishTranslationEl.textContent = word.english || "…";
            indonesianTranslationEl.textContent = word.indonesia || "…";

            resultEl.textContent = "Ketik Hanzi yang tepat";
            answerInput.value = "";
            answerInput.disabled = false;
            submitBtn.disabled = false;
            nextBtn.disabled = true;
            ttsBtn.disabled = true;
            answered = false;
            answerInput.focus();
        }

        function submitAnswer() {
            if (answered) return;

            const word = currentWords[currentIndex];
            const userAns = answerInput.value.trim();

            answered = true;
            answerInput.disabled = true;
            submitBtn.disabled = true;
            nextBtn.disabled = false;
            ttsBtn.disabled = false;

            const correctHanzi = word.other || "";
            hanziEl.textContent = correctHanzi;
            pinyinEl.textContent = word.pinyin || "";
            // englishTranslationEl.textContent = `English/Indonesia: ${word.english || "Tidak ada terjemahan"}`;

            if (userAns === correctHanzi) {
                resultEl.innerHTML = "✅ <b>Benar!</b>";
                score++;
                readWord();
            } else {
                resultEl.innerHTML = `❌ <b>Salah.</b> Jawaban benar: <b>${correctHanzi}</b>`;
            }
            scoreDisplay.textContent = `Skor: ${score} / ${currentWords.length}`;
        }

        function nextQuestion() {
            currentIndex++;
            loadQuestion();
        }

        const synth = window.speechSynthesis;
        let chineseVoice = null;
        let voicesLoaded = false;

        function findChineseVoice() {
            const voices = synth.getVoices();
            chineseVoice = voices.find(v => v.lang.startsWith('zh-CN')) || voices.find(v => v.lang.startsWith('zh')) || null;
            voicesLoaded = !!chineseVoice;
            if (!chineseVoice) {
                console.warn("Suara Mandarin tidak ditemukan. Cek pengaturan browser/OS Anda.");
            }
        }

        if (synth.onvoiceschanged !== undefined) {
            synth.onvoiceschanged = findChineseVoice;
        }
        findChineseVoice();

        function readWord() {
            if (!currentWords.length || currentIndex >= currentWords.length || !answered) return;

            if (!voicesLoaded) findChineseVoice();
            if (!chineseVoice) {
                alert("Suara bahasa Mandarin belum tersedia. Coba klik tombol '🔊' ini secara manual sekali, atau cek pengaturan bahasa di browser/OS Anda.");
                return;
            }

            if (synth.speaking) synth.cancel();

            const word = currentWords[currentIndex];
            const utter = new SpeechSynthesisUtterance(word.other);
            utter.voice = chineseVoice;
            utter.lang = 'zh-CN';
            utter.rate = 0.9;

            setTimeout(() => synth.speak(utter), 100);
        }

        answerInput.addEventListener('keypress', e => {
            if (e.key === 'Enter') {
                e.preventDefault();
                if (!submitBtn.disabled) {
                    submitAnswer();
                } else if (!nextBtn.disabled) {
                    nextQuestion();
                }
            }
        });

        window.addEventListener('DOMContentLoaded', () => {
            initLevelButtons();
            showLevelSelector();
        });
    </script>
@endsection

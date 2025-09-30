<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Chinese Reading Practice Tool</title>
<style>
/* ... (CSS Anda tetap sama) ... */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f7f6;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    margin: 0;
}
.container {
    background-color: #ffffff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    width: 90%;
    max-width: 600px;
    text-align: center;
}
h1 {
    color: #333;
    margin-bottom: 25px;
    font-size: 1.8em;
}
.input-area {
    margin-bottom: 30px;
    display: flex;
    flex-direction: column;
    align-items: center;
}
#hanziInput {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    font-size: 18px;
    border: 2px solid #ccc;
    border-radius: 8px;
    box-sizing: border-box;
    resize: vertical;
}
#hanziInput:focus {
    border-color: #007bff;
    outline: none;
}
.input-label {
    margin-bottom: 10px;
    font-weight: bold;
    color: #555;
}
.sentence {
    border: 1px solid #ddd;
    padding: 20px;
    border-radius: 8px;
    background-color: #f9f9f9;
    min-height: 150px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    align-items: center;
}
.chinese {
    font-size: 2.2em;
    font-weight: bold;
    color: #007bff;
}
.pinyin {
    font-size: 1.1em;
    color: #28a745;
}
.translation {
    font-style: italic;
    color: #6c757d;
    margin-bottom: 15px;
}
.speak-btn {
    padding: 10px 20px;
    font-size: 1em;
    cursor: pointer;
    background-color: #6c757d;
    color: white;
    border: none;
    border-radius: 5px;
    transition: background-color 0.2s;
}
.speak-btn:hover {
    background-color: #5a6268;
}
</style>

<script>
// Pinyin Converter INLINE
function simplePinyinConverter(inputString) {
    const pinyinMap = {
        '我': 'Wǒ', '你': 'nǐ', '他': 'tā', '她': 'tā',
        '今': 'jīn', '天': 'tiān', '很': 'hěn', '开': 'kāi', '心': 'xīn',
        '早': 'zǎo', '上': 'shàng', '去': 'qù', '商': 'shāng', '店': 'diàn',
        '买': 'mǎi', '杂': 'zá', '货': 'huò', '吗': 'ma', '好': 'hǎo',
        '爱': 'ài', '习': 'xí', '汉': 'hàn', '语': 'yǔ',
        '有': 'yǒu', '意': 'yì', '思': 'si', '是': 'shì', '的': 'de',
        '您': 'Nín', '将': 'jiāng', '受': 'shòu', '到': 'dào', 
        '热': 'rè', '烈': 'liè', '欢': 'huān', '迎': 'yíng',
        '会': 'huì', '列': 'liè'
    };
    
    // Karena input sudah difilter menjadi Hanzi murni, kita hanya perlu memprosesnya.
    let pinyinResult = [];
    
    for (const char of inputString) {
        const pinyin = pinyinMap[char];
        
        if (pinyin) {
            pinyinResult.push(pinyin);
        } else {
            // Jika Hanzi tidak ada di database, tandai sebagai error
            pinyinResult.push('???'); 
        }
    }

    return {
        pinyin: pinyinResult.join(' ').trim(),
        hanzi: inputString // Sudah Hanzi murni
    };
}
</script>
</head>
<body>

<div class="container">
    <h1>Chinese Reading Practice</h1>
    
    <div class="input-area">
        <label for="hanziInput" class="input-label">Masukkan Kalimat **Hanzi SAJA** di sini:</label>
        <textarea id="hanziInput" rows="3" placeholder="Contoh: 我今天很开心"></textarea>
        <button class="speak-btn" onclick="updateDisplay()">Tampilkan & Konversi</button>
    </div>

    <div class="sentence">
        <div class="chinese" id="chinese-text"></div>
        <div class="pinyin" id="pinyin-text"></div>
        <div class="translation" id="translation-text"></div>
        <button class="speak-btn" onclick="speakText()">🔊 Ucapkan Teks</button>
    </div>
</div>

<div id="widget" style="width:540px;height:450px;margin:0 auto;"><iframe src="https://www.innovativelanguage.com/widgets/wotd/embed.php?language=Chinese&type=large&bg=%23FFFFFF&content=%23000&header=%23EB2A2E&highlight=%23F9F9FA&opacity=1&scrollbg=%2300CAED&sound=%2300ACED&text=%2300ACED&quiz=N" width="540" height="450" frameborder="0" scrolling="no"></iframe><div style="font:bold 9px/16px Verdana; padding:0; height:16px;"><div style="float:left; margin:0;"><a href="https://www.chineseclass101.com/chinese-phrases/" target="_parent" title="Get Chinese Phrases Widget" style="font-family: Helvetica, Arial, sans-serif;font-size: 11px;color: #00ACED;" rel="nofollow">Get Chinese Phrases Widget</a></div><div style="float:right; margin:0;"><a href="https://www.chineseclass101.com" target="_parent" title="Learn Chinese" style="font-family: Helvetica, Arial, sans-serif;font-size: 11px;color: #00ACED;" rel="nofollow">Learn Chinese</a></div></div></div>

<script>
// 1. Ambil elemen DOM
const hanziInput = document.getElementById('hanziInput');
const chineseText = document.getElementById('chinese-text');
const pinyinText = document.getElementById('pinyin-text');
const translationText = document.getElementById('translation-text');

// 💡 FUNGSI BARU: FILTER HANZI HANYA
function filterHanziOnly(event) {
    const textarea = event.target;
    // Regex untuk HANYA MEMILIH karakter Hanzi (Unicode CJK Unified Ideographs)
    // Semua yang BUKAN Hanzi (\u4e00-\u9fff) akan dihapus.
    const hanziOnly = textarea.value.replace(/[^\u4e00-\u9fff]/g, '');
    textarea.value = hanziOnly;
    
    // Perbarui tampilan secara instan (opsional, tapi disarankan)
    // updateDisplay();
}

// Tambahkan event listener untuk memfilter input secara real-time
hanziInput.addEventListener('input', filterHanziOnly);

// --- FUNGSI TRANSLASI ASINKRON (MENGGUNAKAN API PUBLIK) ---
async function fetchTranslation(hanziOnly) {
    if (hanziOnly === "") return "Tidak ada teks Hanzi untuk diterjemahkan.";

    const url = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=zh-CN&tl=id&dt=t&q=${encodeURIComponent(hanziOnly)}`;

    translationText.textContent = "Menerjemahkan secara real-time...";
    
    try {
        const response = await fetch(url);
        
        if (!response.ok) {
            // Jika API gagal karena batasan atau masalah lain
            return "[Gagal terhubung ke API terjemahan. Coba lagi nanti.]";
        }
        
        const data = await response.json();
        
        if (data && data[0] && data[0][0] && data[0][0][0]) {
            return data[0][0][0];
        } else {
            return "Terjemahan tidak ditemukan atau format API berubah.";
        }
    } catch (error) {
        console.error("Kesalahan API Terjemahan:", error);
        return `[ERROR: Terjemahan gagal. (${error.message}). Coba lagi. ]`;
    }
}

// 2. FUNGSI KONVERSI & TAMPILAN UTAMA
async function updateDisplay() {
    // Karena input sudah difilter oleh filterHanziOnly, hanziInputVal adalah Hanzi murni.
    const hanziInputVal = hanziInput.value.trim();
    
    if (hanziInputVal === "") {
        chineseText.textContent = "";
        pinyinText.textContent = "";
        translationText.textContent = "Silakan masukkan Hanzi.";
        return;
    }

    const conversionResult = simplePinyinConverter(hanziInputVal);

    // A. Tampilkan Hanzi murni
    chineseText.textContent = conversionResult.hanzi;

    // B. Tampilkan Pinyin
    pinyinText.textContent = conversionResult.pinyin; 

    // C. Terjemahan Otomatis (Menggunakan API Nyata)
    const translation = await fetchTranslation(conversionResult.hanzi);
    translationText.textContent = translation;
}

// 3. FUNGSI UCAPKAN (SPEAK)
function speakText() {
    const textToSpeak = chineseText.textContent;
    if (textToSpeak === "" || textToSpeak.includes("Silakan")) {
        alert("Tidak ada teks Hanzi yang bisa diucapkan.");
        return;
    }

    const utterance = new SpeechSynthesisUtterance(textToSpeak);
    const voices = window.speechSynthesis.getVoices();
    const chineseVoice = voices.find(voice => voice.lang.includes('zh-'));

    if (chineseVoice) {
        utterance.voice = chineseVoice;
    } else {
        utterance.lang = 'zh-CN'; 
    }

    utterance.rate = 0.9; 
    window.speechSynthesis.speak(utterance);
}

// 4. Inisialisasi
window.onload = () => {
    if ('speechSynthesis' in window) {
        window.speechSynthesis.onvoiceschanged = () => {};
    }
    updateDisplay();
};

// Panggil updateDisplay saat tombol Enter ditekan di textarea
hanziInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault(); 
        updateDisplay();
    }
});
</script>
</body>
</html>
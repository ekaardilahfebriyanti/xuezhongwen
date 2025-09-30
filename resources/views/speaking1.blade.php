<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan Percakapan Mandarin (Dengan Paginasi)</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f8ff; text-align: center; padding: 30px; }
        h2 { color: #1e3a8a; margin-bottom: 25px; }
        
        /* Gaya Dropdown */
        select {
            padding: 10px 15px;
            font-size: 16px;
            border-radius: 8px;
            border: 2px solid #007bff;
            background: #fff;
            margin: 15px 0;
            cursor: pointer;
            min-width: 300px;
            appearance: none; 
            background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23007bff%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13.6-6.4H19a17.6%2017.6%200%200%200-13.6%206.4%2017.6%2017.6%200%200%200%200%2025.3l129%20129.5a17.6%2017.6%200%200%200%2025.3%200l129-129.5a17.6%2017.6%200%200%200%200-25.3z%22%2F%3E%3C%2Fsvg%3E');
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 12px;
            text-align: left;
        }

        .dialog-container { 
            max-width: 750px; 
            margin: 20px auto; 
            padding: 20px; 
            border: 2px solid #007bff; 
            border-radius: 10px; 
            background: #ffffff;
            text-align: left;
        }
        
        .dialog-line {
            padding: 15px;
            margin: 10px 0;
            border-bottom: 1px dashed #ccc;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .dialog-line:last-child { border-bottom: none; }
        .dialog-line:hover { background-color: #f0f8ff; }

        .dialog-line.active {
            border-left: 5px solid #ff4500;
            background-color: #fff0f0;
            font-weight: bold;
        }

        .line-hanzi { font-size: 28px; font-weight: bold; color: #1e3a8a; }
        .line-pinyin { font-size: 16px; color: #555; margin: 3px 0; }
        .line-translation { font-size: 14px; color: #777; font-style: italic; }

        .controls { text-align: center; margin-top: 20px; }
        
        #paginationControls {
            margin: 10px auto;
            max-width: 400px;
        }
        #paginationControls button {
            padding: 8px 15px;
            margin: 0 5px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.2s;
        }
        #paginationControls button:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        #statusMessage { margin-top: 15px; font-size: 18px; color: #555; }
        
        #listenBtn { 
            padding: 12px 25px; 
            background: #1e3a8a; 
            color: white; 
            border: none; 
            font-size: 18px; 
            opacity: 1; 
            cursor: pointer;
        }
        
        /* Gaya loading visual (spinner sederhana) */
        .loading::after {
            content: ' ⏳'; 
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% { opacity: 0.5; }
            50% { opacity: 1; }
            100% { opacity: 0.5; }
        }
    </style>
</head>
<body>
    <h2>📖 Latihan Percakapan Mandarin (Baca & Dengar)</h2>

    <div class="controls">
        <select id="dialogSelector" onchange="loadDialogue(this.value)">
            </select>
        
        <div id="paginationControls">
            <button id="prevPageBtn" onclick="changePage(currentPage - 1)" disabled>← Sebelumnya</button>
            <span id="pageInfo">Halaman 1/5</span>
            <button id="nextPageBtn" onclick="changePage(currentPage + 1)">Selanjutnya →</button>
        </div>
    </div>

    <div class="dialog-container">
        <h3>Dialog Aktif: <span id="dialogTitle">Pilih Dialog dari Dropdown</span></h3>
        
        <div id="dialogDisplay">
            </div>
    </div>
    
    <div id="statusMessage">Mencari suara Mandarin terbaik...</div>

    <div class="controls">
        <button id="listenBtn" onclick="readActiveTarget()">Dengarkan Baris Aktif 🎧</button>
    </div>

    <script>
        // Caching elements
        const listenBtn = document.getElementById('listenBtn');
        const statusMessage = document.getElementById('statusMessage');
        const dialogSelector = document.getElementById('dialogSelector');
        const prevPageBtn = document.getElementById('prevPageBtn');
        const nextPageBtn = document.getElementById('nextPageBtn');
        const pageInfo = document.getElementById('pageInfo');
        const dialogDisplay = document.getElementById('dialogDisplay');
        const dialogTitle = document.getElementById('dialogTitle');

        // Global state
        let currentTopicKey = 'greeting'; // Default topic key
        let activeLineIndex = 0; // Index of the line currently highlighted/to be read
        
        // Paginasi state
        const itemsPerPage = 10;
        let currentPage = 1;

        // Inisialisasi Speech Synthesis
        const synth = window.speechSynthesis;
        let chineseVoice = null;

        // --- Data Dialog (46 Set) ---
        const allTopics = [
            // KELOMPOK DASAR & SOSIAL
            { key: "greeting", title: "Sapaan & Perkenalan 👋", group: "Dasar & Sosial", lines: [
                { hanzi: "王先生，您好。认识您很高兴。", pinyin: "Wáng xiānshēng, nín hǎo. Rènshí nín hěn gāoxìng.", translation: "Halo, Tuan Wang. Senang bertemu dengan Anda." },
                { hanzi: "我也很高兴认识你。", pinyin: "Wǒ yě hěn gāoxìng rènshí nǐ.", translation: "Saya juga senang bertemu denganmu." },
                { hanzi: "你叫什么名字？", pinyin: "Nǐ jiào shénme míngzì?", translation: "Siapa namamu?" },
                { hanzi: "我叫李明。请多关照。", pinyin: "Wǒ jiào Lǐ Míng. Qǐng duō guānzhào.", translation: "Nama saya Li Ming. Mohon bimbingannya." }
            ]},
            { key: "name", title: "Nama & Kebangsaan 🌍", group: "Dasar & Sosial", lines: [
                { hanzi: "请问，您贵姓大名？", pinyin: "Qǐngwèn, nín guìxìng dàmíng?", translation: "Permisi, siapa nama lengkap Anda?" },
                { hanzi: "我姓张，叫张伟。你是哪国人？", pinyin: "Wǒ xìng Zhāng, jiào Zhāng Wěi. Nǐ shì nǎ guó rén?", translation: "Nama belakang saya Zhang, nama saya Zhang Wei. Anda dari negara mana?" },
                { hanzi: "我是印度尼西亚人。", pinyin: "Wǒ shì Yìndùníxīyà rén.", translation: "Saya orang Indonesia." },
                { hanzi: "欢迎来到中国！", pinyin: "Huānyíng láidào Zhōngguó!", translation: "Selamat datang di Tiongkok!" }
            ]},
            { key: "hobbies", title: "Hobi & Minat ⚽", group: "Dasar & Sosial", lines: [
                { hanzi: "你周末喜欢做什么？", pinyin: "Nǐ zhōumò xǐhuān zuò shénme?", translation: "Apa yang kamu suka lakukan di akhir pekan?" },
                { hanzi: "我喜欢看电影和听音乐。", pinyin: "Wǒ xǐhuān kàn diànyǐng hé tīng yīnyuè.", translation: "Saya suka menonton film dan mendengarkan musik." },
                { hanzi: "你常去旅游吗？", pinyin: "Nǐ cháng qù lǚyóu ma?", translation: "Apakah kamu sering bepergian?" },
                { hanzi: "不常去，但是我很想去中国。", pinyin: "Bù cháng qù, dànshì wǒ hěn xiǎng qù Zhōngguó.", translation: "Tidak sering, tapi saya sangat ingin pergi ke Tiongkok." }
            ]},
            { key: "family", title: "Keluarga 👨‍👩‍👧‍👦", group: "Dasar & Sosial", lines: [
                { hanzi: "你家有几口人？", pinyin: "Nǐ jiā yǒu jǐ kǒu rén?", translation: "Ada berapa anggota keluarga di rumahmu?" },
                { hanzi: "我家有五口人：爸爸、妈妈、哥哥、姐姐和我。", pinyin: "Wǒ jiā yǒu wǔ kǒu rén: bàba, māma, gēge, jiějie hé wǒ.", translation: "Keluargaku ada lima orang: Ayah, Ibu, kakak laki-laki, kakak perempuan, dan saya." },
                { hanzi: "你哥哥是做什么的？", pinyin: "Nǐ gēge shì zuò shénme de?", translation: "Apa pekerjaan kakak laki-lakimu?" },
                { hanzi: "他是医生，在一家大医院工作。", pinyin: "Tā shì yīshēng, zài yī jiā dà yīyuàn gōngzuò.", translation: "Dia adalah dokter, bekerja di rumah sakit besar." }
            ]},
            { key: "opinion", title: "Menanyakan Pendapat 🤔", group: "Dasar & Sosial", lines: [
                { hanzi: "你对这个新规定有什么看法？", pinyin: "Nǐ duì zhège xīn guīdìng yǒu shénme kànfǎ?", translation: "Apa pandangan/pendapatmu tentang peraturan baru ini?" },
                { hanzi: "我认为利大于弊，虽然有些麻烦。", pinyin: "Wǒ rènwéi lì dàyú bì, suīrán yǒuxiē máfan.", translation: "Saya pikir manfaatnya lebih besar daripada kekurangannya, meskipun agak merepotkan." },
                { hanzi: "听起来很有道理。你觉得我们应该支持吗？", pinyin: "Tīng qǐlái hěn yǒu dàolǐ. Nǐ juéde wǒmen yīnggāi zhīchí ma?", translation: "Kedengarannya masuk akal. Menurutmu kita harus mendukungnya?" },
                { hanzi: "如果能带来更好的未来，为什么不呢？", pinyin: "Rúguǒ néng dàilái gèng hǎo de wèilái, wèishénme bù ne?", translation: "Jika bisa membawa masa depan yang lebih baik, mengapa tidak?" }
            ]},
            { key: "compliment_person", title: "Memberi Pujian Pribadi 😊", group: "Dasar & Sosial", lines: [
                { hanzi: "你今天看起来气色真好。", pinyin: "Nǐ jīntiān kàn qǐlái qìsè zhēn hǎo.", translation: "Anda terlihat sangat segar hari ini (warna kulit/wajah bagus)." },
                { hanzi: "谢谢，我昨晚睡得很好。", pinyin: "Xièxie, wǒ zuówǎn shuì de hěn hǎo.", translation: "Terima kasih, saya tidur nyenyak tadi malam." },
                { hanzi: "你的新衣服很漂亮，在哪里买的？", pinyin: "Nǐ de xīn yīfu hěn piàoliang, zài nǎlǐ mǎi de?", translation: "Baju barumu sangat cantik, beli di mana?" },
                { hanzi: "这是朋友送的，我很喜欢它的款式。", pinyin: "Zhè shì péngyǒu sòng de, wǒ hěn xǐhuān tā de kuǎnshì.", translation: "Ini hadiah dari teman, saya sangat suka modelnya." }
            ]},
            { key: "ask_favor", title: "Meminta Bantuan Personal 🤝", group: "Dasar & Sosial", lines: [
                { hanzi: "我能请你帮个忙吗？", pinyin: "Wǒ néng qǐng nǐ bāng gè máng ma?", translation: "Bolehkah saya meminta bantuan Anda?" },
                { hanzi: "当然可以，是什么事？", pinyin: "Dāngrán kěyǐ, shì shénme shì?", translation: "Tentu saja, ada urusan apa?" },
                { hanzi: "我需要把这个箱子搬到楼上。", pinyin: "Wǒ xūyào bǎ zhège xiāngzi bān dào lóu shàng.", translation: "Saya perlu memindahkan kotak ini ke lantai atas." },
                { hanzi: "没问题，我们一起抬。", pinyin: "Méi wèntí, wǒmen yīqǐ tái.", translation: "Tidak masalah, mari kita angkat bersama." }
            ]},
            { key: "emotion", title: "Mengungkapkan Perasaan 😞", group: "Dasar & Sosial", lines: [
                { hanzi: "你看上去不太开心，发生什么了？", pinyin: "Nǐ kàn shàngqù bú tài kāixīn, fāshēng shénme le?", translation: "Anda terlihat tidak terlalu senang, apa yang terjadi?" },
                { hanzi: "我最近压力很大，有点累。", pinyin: "Wǒ zuìjìn yālì hěn dà, yǒudiǎn lèi.", translation: "Saya sangat stres akhir-akhir ini, agak lelah." },
                { hanzi: "别担心，一切都会好起来的。", pinyin: "Bié dānxīn, yīqiè dōu huì hǎo qǐlái de.", translation: "Jangan khawatir, semuanya akan baik-baik saja." },
                { hanzi: "谢谢你的鼓励，我感觉好多了。", pinyin: "Xièxie nǐ de gǔlì, wǒ gǎnjué hǎo duō le.", translation: "Terima kasih atas dorongan Anda, saya merasa lebih baik." }
            ]},
            { key: "dating", title: "Kencan & Hubungan ❤️", group: "Dasar & Sosial", lines: [
                { hanzi: "你周末有约会吗？", pinyin: "Nǐ zhōumò yǒu yuēhuì ma?", translation: "Apakah kamu punya janji kencan akhir pekan ini?" },
                { hanzi: "是的，我们计划去公园散步。", pinyin: "Shì de, wǒmen jìhuà qù gōngyuán sànbù.", translation: "Ya, kami berencana pergi jalan-jalan di taman." },
                { hanzi: "你觉得他是一个浪漫的人吗？", pinyin: "Nǐ juéde tā shì yī gè làngmàn de rén ma?", translation: "Apakah menurutmu dia orang yang romantis?" },
                { hanzi: "他不太会说甜言蜜语，但很细心。", pinyin: "Tā bú tài huì shuō tiányán mì yǔ, dàn hěn xìxīn.", translation: "Dia tidak pandai merangkai kata-kata manis, tapi dia sangat perhatian." }
            ]},
            // KELOMPOK KEHIDUPAN SEHARI-HARI
            { key: "health", title: "Kesehatan & Sakit 🤒", group: "Kehidupan Sehari-hari", lines: [
                { hanzi: "你看起来不太舒服，怎么了？", pinyin: "Nǐ kàn qǐlái bù tài shūfú, zěnme le?", translation: "Kamu terlihat kurang sehat, ada apa?" },
                { hanzi: "我有点头疼，可能感冒了。", pinyin: "Wǒ yǒudiǎn tóuténg, kěnéng gǎnmào le.", translation: "Saya agak sakit kepala, mungkin masuk angin." },
                { hanzi: "你需要去医院看看医生。", pinyin: "Nǐ xūyào qù yīyuàn kànkan yīshēng.", translation: "Kamu perlu ke rumah sakit menemui dokter." },
                { hanzi: "谢谢你的关心。我会多休息。", pinyin: "Xièxie nǐ de guānxīn. Wǒ huì duō xiūxi.", translation: "Terima kasih atas perhatianmu. Saya akan banyak istirahat." }
            ]},
            { key: "weather", title: "Cuaca & Musim ☀️", group: "Kehidupan Sehari-hari", lines: [
                { hanzi: "今天天气怎么样？", pinyin: "Jīntiān tiānqì zěnmeyàng?", translation: "Bagaimana cuaca hari ini?" },
                { hanzi: "今天天气很热，有三十五度。", pinyin: "Jīntiān tiānqì hěn rè, yǒu sānshíwǔ dù.", translation: "Cuaca hari ini sangat panas, 35 derajat." },
                { hanzi: "明天会下雨吗？", pinyin: "Míngtiān huì xià yǔ ma?", translation: "Apakah besok akan turun hujan?" },
                { hanzi: "天气预报说不会。你应该带伞。", pinyin: "Tiānqì yùbào shuō bú huì. Nǐ yīnggāi dài sǎn.", translation: "Prakiraan cuaca bilang tidak. Anda harus membawa payung." }
            ]},
            { key: "time", title: "Waktu & Jadwal ⏰", group: "Kehidupan Sehari-hari", lines: [
                { hanzi: "现在几点了？", pinyin: "Xiànzài jǐ diǎn le?", translation: "Sekarang jam berapa?" },
                { hanzi: "现在是下午四点半。", pinyin: "Xiànzài shì xiàwǔ sì diǎn bàn.", translation: "Sekarang jam setengah lima sore." },
                { hanzi: "我们得快点，电影五点就开始了。", pinyin: "Wǒmen děi kuài diǎn, diànyǐng wǔ diǎn jiù kāishǐ le。", translation: "Kita harus cepat, filmnya sudah mulai jam lima." },
                { hanzi: "没关系，我们还有时间。", pinyin: "Méi guānxi, wǒmen hái yǒu shíjiān。", translation: "Tidak masalah, kita masih punya waktu." }
            ]},
            { key: "party", title: "Undangan & Pesta 🎉", group: "Kehidupan Sehari-hari", lines: [
                { hanzi: "周末我有一个生日派对，你能来吗？", pinyin: "Zhōumò wǒ yǒu yīgè shēngrì pàiduì, nǐ néng lái ma?", translation: "Saya ada pesta ulang tahun akhir pekan, bisakah kamu datang?" },
                { hanzi: "太好了！派对在哪里举行？", pinyin: "Tài hǎo le! Pàiduì zài nǎlǐ jǔxíng?", translation: "Hebat! Pesta diadakan di mana?" },
                { hanzi: "在我家。时间是周六晚上七点。", pinyin: "Zài wǒ jiā. Shíjiān shì zhōu liù wǎnshàng qī diǎn.", translation: "Di rumah saya. Waktunya Sabtu malam jam tujuh." },
                { hanzi: "我一定会准时到的，谢谢你邀请我！", pinyin: "Wǒ yīdìng huì zhǔnshí dào de, xièxie nǐ yāoqǐng wǒ!", translation: "Saya pasti akan datang tepat waktu, terima kasih sudah mengundang saya!" }
            ]},
            { key: "attend_party", title: "Menghadiri Pesta Makan Malam 🥂", group: "Kehidupan Sehari-hari", lines: [
                { hanzi: "很高兴你来参加我的聚会。", pinyin: "Hěn gāoxìng nǐ lái cānjiā wǒ de jùhuì.", translation: "Senang sekali kamu datang ke pesta saya." },
                { hanzi: "谢谢你的邀请。这个派对很热闹。", pinyin: "Xièxie nǐ de yāoqǐng. Zhège pàiduì hěn rènào.", translation: "Terima kasih atas undangannya. Pesta ini sangat meriah." },
                { hanzi: "请随便吃喝，不用客气。", pinyin: "Qǐng suíbiàn chī hē, bú yòng kèqi.", translation: "Silakan makan dan minum sepuasnya, jangan sungkan." },
                { hanzi: "主人太热情了。下次换我请客。", pinyin: "Zhǔrén tài rèqíng le. Xià cì huàn wǒ qǐngkè.", translation: "Tuan rumah terlalu ramah. Lain kali biar saya yang traktir." }
            ]},
            { key: "new_topic_2", title: "Olahraga & Kebugaran 🏃", group: "Kehidupan Sehari-hari", lines: [
                { hanzi: "你平时喜欢做什么运动？", pinyin: "Nǐ píngshí xǐhuān zuò shénme yùndòng?", translation: "Olahraga apa yang biasa kamu lakukan?" },
                { hanzi: "我每天早上都跑步。", pinyin: "Wǒ měitiān zǎoshang dōu pǎobù.", translation: "Saya lari setiap pagi." },
                { hanzi: "跑步对身体真的很好。", pinyin: "Pǎobù duì shēntǐ zhēnde hěn hǎo.", translation: "Lari sangat baik untuk tubuh." },
                { hanzi: "是的，你也应该开始。", pinyin: "Shì de, nǐ yě yīnggāi kāishǐ.", translation: "Ya, kamu juga harus mulai." }
            ]},
            { key: "daily_routine", title: "Rutinitas Harian 🛌", group: "Kehidupan Sehari-hari", lines: [
                { hanzi: "你早上几点起床？", pinyin: "Nǐ zǎoshang jǐ diǎn qǐchuáng?", translation: "Jam berapa kamu bangun di pagi hari?" },
                { hanzi: "我通常六点半起床，七点吃早餐。", pinyin: "Wǒ tōngcháng liù diǎn bàn qǐchuáng, qī diǎn chī zǎocān.", translation: "Saya biasanya bangun jam setengah tujuh, dan sarapan jam tujuh." },
                { hanzi: "你每天都自己做饭吗？", pinyin: "Nǐ měitiān dōu zìjǐ zuò fàn ma?", translation: "Apakah kamu memasak sendiri setiap hari?" },
                { hanzi: "不一定，有时候也在外面吃。", pinyin: "Bù yīdìng, yǒu shíhou yě zài wàimiàn chī.", translation: "Tidak selalu, terkadang makan di luar juga." }
            ]},
            // KELOMPOK BELANJA & MAKANAN
            { key: "shopping", title: "Belanja & Harga 🛍️", group: "Belanja & Makanan", lines: [
                { hanzi: "老板，这个苹果怎么卖？", pinyin: "Lǎobǎn, zhège píngguǒ zěnme mài?", translation: "Bos, apel ini dijual berapa?" },
                { hanzi: "三块五一斤。你要多少？", pinyin: "Sān kuài wǔ yī jīn. Nǐ yào duōshao?", translation: "Tiga setengah Yuan per setengah kilogram. Kamu mau berapa?" },
                { hanzi: "太贵了，两块五可以吗？", pinyin: "Tài guì le, liǎng kuài wǔ kěyǐ ma?", translation: "Terlalu mahal, dua setengah Yuan boleh?" },
                { hanzi: "好吧，给你，总共七块钱。", pinyin: "Hǎo ba, gěi nǐ, zǒnggòng qī kuài qián。", translation: "Baiklah, ini untukmu, total tujuh Yuan." }
            ]},
            { key: "ordering", title: "Pesan Makanan 🍜", group: "Belanja & Makanan", lines: [
                { hanzi: "服务员，点菜。我想要一份牛肉面。", pinyin: "Fúwùyuán, diǎn cài. Wǒ xiǎng yào yī fèn niúròu miàn.", translation: "Pelayan, pesan. Saya mau satu porsi mie daging sapi." },
                { hanzi: "好的。要辣的吗？", pinyin: "Hǎo de. Yào là de ma?", translation: "Baik. Mau yang pedas?" },
                { hanzi: "不要太辣。再来一杯冰水，谢谢。", pinyin: "Bú yào tài là. Zài lái yī bēi bīng shuǐ, xièxie.", translation: "Jangan terlalu pedas. Tambah satu cangkir air es, terima kasih." },
                { hanzi: "好的，马上就好。", pinyin: "Hǎo de, mǎshàng jiù hǎo。", translation: "Baik, sebentar lagi siap." }
            ]},
            { key: "fruit", title: "Membeli Buah 🍎", group: "Belanja & Makanan", lines: [
                { hanzi: "这些橘子新鲜吗？", pinyin: "Zhèxiē júzi xīnxiān ma?", translation: "Apakah jeruk ini segar?" },
                { hanzi: "非常新鲜，今天早上刚到的。", pinyin: "Fēicháng xīnxiān, jīntiān zǎoshang gāng dào de.", translation: "Sangat segar, baru tiba pagi ini." },
                { hanzi: "我要一公斤。多少钱？", pinyin: "Wǒ yào yī gōngjīn. Duōshao qián?", translation: "Saya mau satu kilogram. Berapa harganya?" },
                { hanzi: "一共十二块钱。谢谢惠顾。", pinyin: "Yīgòng shí'èr kuài qián. Xièxie huìgù。", translation: "Total dua belas Yuan. Terima kasih telah berbelanja." }
            ]},
            // KELOMPOK KEHIDUPAN SEHARI-HARI (Lanjutan)
            { key: "compliment", title: "Pujian Makanan 👍", group: "Belanja & Makanan", lines: [
                { hanzi: "你做的饭真好吃！", pinyin: "Nǐ zuò de fàn zhēn hào chī!", translation: "Masakan yang kamu buat benar-benar enak!" },
                { hanzi: "谢谢，你喜欢就好。", pinyin: "Xièxie, nǐ xǐhuān jiù hǎo.", translation: "Terima kasih, senang jika kamu suka." },
                { hanzi: "特别是这个鱼，味道太棒了。", pinyin: "Tèbié shì zhège yú, wèidào tài bàng le.", translation: "Terutama ikan ini, rasanya luar biasa." },
                { hanzi: "请多吃一点。多喝点茶。", pinyin: "Qǐng duō chī yīdiǎn. Duō hē diǎn chá。", translation: "Silakan makan lebih banyak. Minum teh lebih banyak." }
            ]},
            { key: "cooking", title: "Memasak & Resep 🍳", group: "Makanan & Kuliner", lines: [
                { hanzi: "你最拿手的菜是什么？", pinyin: "Nǐ zuì náshǒu de cài shì shénme?", translation: "Apa hidangan andalanmu?" },
                { hanzi: "我会做麻婆豆腐，味道很正宗。", pinyin: "Wǒ huì zuò mápó dòufu, wèidào hěn zhèngzōng.", translation: "Saya bisa membuat Mapo Tofu, rasanya sangat otentik." },
                { hanzi: "这个汤是怎么做的？教教我吧。", pinyin: "Zhège tāng shì zěnme zuò de? Jiāo jiāo wǒ ba.", translation: "Bagaimana cara membuat sup ini? Ajari saya." },
                { hanzi: "很简单，主要用鸡肉和一些草药。", pinyin: "Hěn jiǎndān, zhǔyào yòng jīròu hé yīxiē cǎoyào.", translation: "Sangat mudah, utamanya menggunakan daging ayam dan beberapa rempah-rempah." }
            ]},
            { key: "tea_coffee", title: "Memesan Minuman (Kopi/Teh) ☕", group: "Makanan & Kuliner", lines: [
                { hanzi: "您想喝点什么？咖啡还是茶？", pinyin: "Nín xiǎng hē diǎn shénme? Kāfēi háishì chá?", translation: "Anda mau minum apa? Kopi atau teh?" },
                { hanzi: "请给我一杯热拿铁，少放糖。", pinyin: "Qǐng gěi wǒ yī bēi rè nátiě, shǎo fàng táng.", translation: "Tolong beri saya satu cangkir *latte* panas, dengan sedikit gula." },
                { hanzi: "您要大杯、中杯还是小杯？", pinyin: "Nín yào dà bēi, zhōng bēi háishì xiǎo bēi?", translation: "Anda mau ukuran besar, sedang, atau kecil?" },
                { hanzi: "中杯就好。谢谢。", pinyin: "Zhōng bēi jiù hǎo. Xièxie.", translation: "Ukuran sedang saja. Terima kasih." }
            ]},
            // KELOMPOK PERJALANAN & ARAH
            { key: "direction", title: "Tanya Arah 🗺️", group: "Perjalanan & Arah", lines: [
                { hanzi: "请问，去火车站怎么走？", pinyin: "Qǐngwèn, qù huǒchēzhàn zěnme zǒu?", translation: "Permisi, bagaimana cara ke stasiun kereta?" },
                { hanzi: "你一直往前走，在第二个路口左转。", pinyin: "Nǐ yīzhí wǎng qián zǒu, zài dì èr ge lùkǒu zuǒ zhuǎn.", translation: "Kamu lurus terus, belok kiri di perempatan kedua." },
                { hanzi: "远吗？我走路可以到吗？", pinyin: "Yuǎn ma? Wǒ zǒulù kěyǐ dào ma?", translation: "Apakah jauh? Saya bisa sampai dengan jalan kaki?" },
                { hanzi: "有点远，坐公交车比较快。", pinyin: "Yǒudiǎn yuǎn, zuò gōngjiāo chē bǐjiào kuài。", translation: "Agak jauh, naik bus lebih cepat." }
            ]},
            { key: "transport", title: "Transportasi 🚇", group: "Perjalanan & Arah", lines: [
                { hanzi: "请问，这趟车去北京吗？", pinyin: "Qǐngwèn, zhè tàng chē qù Běijīng ma?", translation: "Permisi, apakah kereta/bus ini ke Beijing?" },
                { hanzi: "对，这是去北京的高铁。", pinyin: "Duì, zhè shì qù Běijīng de gāotiě.", translation: "Ya, ini adalah kereta cepat (High-speed rail) ke Beijing." },
                { hanzi: "票价是多少钱？", pinyin: "Piàojià shì duōshao qián?", translation: "Berapa harga tiketnya?" },
                { hanzi: "二等座是三百块。", pinyin: "Èr děng zuò shì sānbǎi kuài。", translation: "Kursi kelas dua harganya tiga ratus Yuan." }
            ]},
            { key: "hotel", title: "Memesan Hotel 🏨", group: "Perjalanan & Arah", lines: [
                { hanzi: "你好，请问有空房间吗？", pinyin: "Nǐ hǎo, qǐngwèn yǒu kòng fángjiān ma?", translation: "Halo, permisi, apakah ada kamar kosong?" },
                { hanzi: "有的。您需要单人房还是双人房？", pinyin: "Yǒu de. Nín xūyào dānrén fáng háishì shuāngrén fáng?", translation: "Ada. Anda butuh kamar single atau double?" },
                { hanzi: "我要一间双人房，住两个晚上。", pinyin: "Wǒ yào yī jiān shuāngrén fáng, zhù liǎng ge wǎnshàng.", translation: "Saya mau satu kamar double, menginap dua malam." },
                { hanzi: "好的，请出示您的护照，这是您的房卡。", pinyin: "Hǎo de, qǐng chūshì nín de hùzhào, zhè shì nín de fángkǎ。", translation: "Baik, tolong tunjukkan paspor Anda, ini kartu kamar Anda." }
            ]},
            { key: "sightseeing", title: "Wisata/Pemandangan 🏞️", group: "Perjalanan & Arah", lines: [
                { hanzi: "这个公园的风景真美。", pinyin: "Zhège gōngyuán de fēngjǐng zhēn měi.", translation: "Pemandangan di taman ini benar-benar indah." },
                { hanzi: "是啊，特别是湖边，空气很新鲜。", pinyin: "Shì a, tèbié shì húbiān, kōngqì hěn xīnxiān.", translation: "Benar, terutama di tepi danau, udaranya sangat segar." },
                { hanzi: "你拍了很多照片吗？", pinyin: "Nǐ pāi le hěn duō zhàopiàn ma?", translation: "Apakah kamu mengambil banyak foto?" },
                { hanzi: "对，我想把这些美景都记下来。", pinyin: "Duì, wǒ xiǎng bǎ zhèxiē měijǐng dōu jì xiàlái。", translation: "Ya, saya ingin mengabadikan semua pemandangan indah ini." }
            ]},
            { key: "plane_ticket", title: "Pesan Tiket Pesawat ✈️", group: "Perjalanan & Arah", lines: [
                { hanzi: "我要预订一张去上海的往返机票。", pinyin: "Wǒ yào yùdìng yī zhāng qù Shànghǎi de wǎngfǎn jīpiào.", translation: "Saya ingin memesan tiket pesawat pulang pergi ke Shanghai." },
                { hanzi: "您希望乘坐哪家航空公司的航班？", pinyin: "Nín xīwàng chéngzuò nǎ jiā hángkōng gōngsī de hángbān?", translation: "Anda ingin naik maskapai penerbangan yang mana?" },
                { hanzi: "有经济舱的特价票吗？", pinyin: "Yǒu jīngjì cāng de tèjià piào ma?", translation: "Apakah ada tiket diskon untuk kelas ekonomi?" },
                { hanzi: "请稍等，我帮您查询。最便宜的是三千块。", pinyin: "Qǐng shāo děng, wǒ bāng nín cháxún. Zuì piányi de shì sān qiān kuài.", translation: "Mohon tunggu sebentar, saya bantu Anda cek. Yang termurah adalah tiga ribu Yuan." }
            ]},
            { key: "customs", title: "Di Bea Cukai (Customs) 🛂", group: "Perjalanan & Arah", lines: [
                { hanzi: "请把您的护照和登机牌给我。", pinyin: "Qǐng bǎ nín de hùzhào hé dēngjī pái gěi wǒ.", translation: "Tolong berikan paspor dan *boarding pass* Anda kepada saya." },
                { hanzi: "好的，我需要申报任何物品吗？", pinyin: "Hǎo de, wǒ xūyào shēnbào rènhé wùpǐn ma?", translation: "Baik, apakah saya perlu mendeklarasikan barang apa pun?" },
                { hanzi: "您携带了多少现金？", pinyin: "Nín xiédài le duōshǎo xiànjīn?", translation: "Berapa banyak uang tunai yang Anda bawa?" },
                { hanzi: "我只带了五千美元。没有需要申报的东西。", pinyin: "Wǒ zhǐ dài le wǔ qiān měiyuán. Méiyǒu xūyào shēnbào de dōngxi.", translation: "Saya hanya membawa lima ribu Dolar AS. Tidak ada yang perlu dideklarasikan." }
            ]},
            { key: "holiday", title: "Merencanakan Liburan 🏖️", group: "Perjalanan & Arah", lines: [
                { hanzi: "我们寒假去哪里旅行比较好？", pinyin: "Wǒmen hánjià qù nǎlǐ lǚxíng bǐjiào hǎo?", translation: "Ke mana sebaiknya kita pergi berlibur selama liburan musim dingin?" },
                { hanzi: "去海南吧，那里天气暖和，可以游泳。", pinyin: "Qù Hǎinán ba, nàlǐ tiānqì nuǎnhuo, kěyǐ yóuyǒng.", translation: "Pergi ke Hainan saja, cuaca di sana hangat, bisa berenang." },
                { hanzi: "需要提前订好酒店和机票吗？", pinyin: "Xūyào tíqián dìng hǎo jiǔdiàn hé jīpiào ma?", translation: "Apakah perlu memesan hotel dan tiket pesawat jauh-jauh hari?" },
                { hanzi: "当然，越早订越便宜。", pinyin: "Dāngrán, yuè zǎo dìng yuè piányi.", translation: "Tentu saja, semakin cepat Anda memesan, semakin murah." }
            ]},
            // KELOMPOK PEKERJAAN & PENDIDIKAN
            { key: "appointment", title: "Membuat Janji 📅", group: "Pekerjaan & Pendidikan", lines: [
                { hanzi: "你明天下午有空吗？我想和你讨论报告。", pinyin: "Nǐ míngtiān xiàwǔ yǒu kòng ma? Wǒ xiǎng hé nǐ tǎolùn bàogào.", translation: "Apakah kamu ada waktu besok sore? Saya ingin mendiskusikan laporan denganmu." },
                { hanzi: "对不起，我明天下午要开会。", pinyin: "Duìbùqǐ, wǒ míngtiān xiàwǔ yào kāihuì.", translation: "Maaf, besok sore saya ada rapat." },
                { hanzi: "那我们什么时候见面最好？", pinyin: "Nà wǒmen shénme shíhou jiànmiàn zuì hǎo?", translation: "Kalau begitu, kapan waktu terbaik bagi kita untuk bertemu?" },
                { hanzi: "后天上午十点，在我办公室见。", pinyin: "Hòutiān shàngwǔ shí diǎn, zài wǒ bàngōngshì jiàn。", translation: "Lusa pagi jam sepuluh, bertemu di kantor saya." }
            ]},
            { key: "work", title: "Pekerjaan Kantor 💼", group: "Pekerjaan & Pendidikan", lines: [
                { hanzi: "请问，您是哪个部门的？", pinyin: "Qǐngwèn, nín shì nǎ ge bùmén de?", translation: "Permisi, Anda dari departemen mana?" },
                { hanzi: "我是市场部的经理。", pinyin: "Wǒ shì shìchǎng bù de jīnglǐ.", translation: "Saya adalah manajer departemen pemasaran." },
                { hanzi: "这个项目进展怎么样了？", pinyin: "Zhège xiàngmù jìnzhǎn zěnme yàng le?", translation: "Bagaimana kemajuan proyek ini?" },
                { hanzi: "一切顺利，我们下周会完成。", pinyin: "Yīqiè shùnlì, wǒmen xià zhōu huì wánchéng。", translation: "Semuanya lancar, kami akan menyelesaikannya minggu depan." }
            ]},
            { key: "school", title: "Sekolah/Kuliah 📚", group: "Pekerjaan & Pendidikan", lines: [
                { hanzi: "你学的专业是什么？", pinyin: "Nǐ xué de zhuānyè shì shénme?", translation: "Jurusan apa yang kamu pelajari?" },
                { hanzi: "我学的是国际贸易。", pinyin: "Wǒ xué de shì guójì màoyì.", translation: "Saya belajar perdagangan internasional." },
                { hanzi: "你觉得汉语难学吗？", pinyin: "Nǐ juéde Hànyǔ nán xué ma?", translation: "Menurutmu bahasa Mandarin sulit dipelajari?" },
                { hanzi: "语法不难，但是汉字很难记。", pinyin: "Yǔfǎ bù nán, dànshì Hànzì hěn nán jì。", translation: "Tata bahasanya tidak sulit, tapi Hanzi (karakter) sulit dihafal." }
            ]},
            { key: "language", title: "Belajar Bahasa 🗣️", group: "Pekerjaan & Pendidikan", lines: [
                { hanzi: "你的汉语说得真流利。", pinyin: "Nǐ de Hànyǔ shuō de zhēn liúlì.", translation: "Bahasa Mandarinmu diucapkan dengan sangat fasih." },
                { hanzi: "谢谢，我每天都练习。", pinyin: "Xièxie, wǒ měitiān dōu liànxí.", translation: "Terima kasih, saya berlatih setiap hari." },
                { hanzi: "你学了多久了？", pinyin: "Nǐ xué le duōjiǔ le?", translation: "Sudah berapa lama kamu belajar?" },
                { hanzi: "大约两年了。现在我正在学习成语。", pinyin: "Dàyuē liǎng nián le. Xiànzài wǒ zhèngzài xuéxí chéngyǔ。", translation: "Kira-kira dua tahun. Sekarang saya sedang belajar *chengyu* (idiom empat karakter)." }
            ]},
            { key: "teacher_query", title: "Bertanya kepada Guru 👩‍🏫", group: "Pekerjaan & Pendidikan", lines: [
                { hanzi: "老师，请问这个词怎么用？", pinyin: "Lǎoshī, qǐngwèn zhège cí zěnme yòng?", translation: "Guru, permisi, bagaimana cara menggunakan kata ini?" },
                { hanzi: "这个词通常用在正式场合，表示'segera'.", pinyin: "Zhège cí tōngcháng yòng zài zhèngshì chǎnghé, biǎoshì 'segera'.", translation: "Kata ini biasanya digunakan dalam situasi formal, berarti 'segera'." },
                { hanzi: "我还是不太明白，能举个例子吗？", pinyin: "Wǒ hái shì bú tài míngbái, néng jǔ gè lìzi ma?", translation: "Saya masih kurang mengerti, bisakah Anda berikan contoh?" },
                { hanzi: "当然可以。请看黑板上的句子。", pinyin: "Dāngrán kěyǐ. Qǐng kàn hēibǎn shàng de jùzi.", translation: "Tentu saja bisa. Silakan lihat kalimat di papan tulis." }
            ]},
            { key: "library", title: "Di Perpustakaan 📚", group: "Pekerjaan & Pendidikan", lines: [
                { hanzi: "请问这本书在哪里可以找到？", pinyin: "Qǐngwèn zhè běn shū zài nǎlǐ kěyǐ zhǎodào?", translation: "Boleh tanya, di mana buku ini bisa ditemukan?" },
                { hanzi: "它在二楼的文学区。", pinyin: "Tā zài èr lóu de wénxué qū.", translation: "Buku itu ada di bagian sastra di lantai dua." },
                { hanzi: "这本书可以借多久？", pinyin: "Zhè běn shū kěyǐ jiè duōjiǔ?", translation: "Buku ini bisa dipinjam berapa lama?" },
                { hanzi: "两周。如果你需要，可以续借。", pinyin: "Liǎng zhōu. Rúguǒ nǐ xūyào, kěyǐ xùjiè.", translation: "Dua minggu. Anda bisa memperpanjangnya jika perlu." }
            ]},
            { key: "exam", title: "Ujian & Hasil Belajar 📝", group: "Pekerjaan & Pendidikan", lines: [
                { hanzi: "你为这次考试做了充分准备吗？", pinyin: "Nǐ wèi zhè cì kǎoshì zuò le chōngfèn zhǔnbèi ma?", translation: "Apakah kamu sudah mempersiapkan diri dengan baik untuk ujian ini?" },
                { hanzi: "我复习了好几天，希望可以及格。", pinyin: "Wǒ fùxí le hǎo jǐ tiān, xīwàng kěyǐ jí gé.", translation: "Saya sudah belajar selama beberapa hari, semoga bisa lulus." },
                { hanzi: "考试结果什么时候公布？", pinyin: "Kǎoshì jiéguǒ shénme shíhou gōngbù?", translation: "Kapan hasil ujiannya akan diumumkan?" },
                { hanzi: "下周三，到时候你可以上网查询。", pinyin: "Xià zhōu sān, dào shíhou nǐ kěyǐ shàngwǎng cháxún.", translation: "Rabu depan, Anda bisa mengeceknya secara online." }
            ]},
            { key: "meeting", title: "Rapat & Presentasi 📊", group: "Kantor & Bisnis", lines: [
                { hanzi: "今天的会议主题是什么？", pinyin: "Jīntiān de huìyì zhǔtí shì shénme?", translation: "Apa topik rapat hari ini?" },
                { hanzi: "我们将讨论下个季度的预算。", pinyin: "Wǒmen jiāng tǎolùn xià ge jìdù de yùsuàn.", translation: "Kita akan membahas anggaran kuartal berikutnya." },
                { hanzi: "请你用五分钟介绍一下你的方案。", pinyin: "Qǐng nǐ yòng wǔ fēnzhōng jièshào yīxià nǐ de fāng'àn.", translation: "Tolong presentasikan proposal Anda dalam lima menit." },
                { hanzi: "没问题，这是我准备好的报告。", pinyin: "Méi wèntí, zhè shì wǒ zhǔnbèi hǎo de bàogào。", translation: "Tidak masalah, ini laporan yang sudah saya siapkan." }
            ]},
            { key: "salary", title: "Gaji & Keuangan 💰", group: "Kantor & Bisnis", lines: [
                { hanzi: "这个职位的工资待遇怎么样？", pinyin: "Zhège zhíwèi de gōngzī dàiyù zěnmeyàng?", translation: "Bagaimana gaji dan tunjangan untuk posisi ini?" },
                { hanzi: "基本工资不错，还有年底奖金。", pinyin: "Jīběn gōngzī búcuò, hái yǒu niándǐ jiǎngjīn.", translation: "Gaji pokoknya lumayan, dan ada bonus akhir tahun." },
                { hanzi: "请问什么时候发工资？", pinyin: "Qǐngwèn shénme shíhou fā gōngzī?", translation: "Boleh tanya, kapan gajinya dibayarkan?" },
                { hanzi: "每月十五号准时到账。", pinyin: "Měi yuè shíwǔ hào zhǔnshí dàozhàng.", translation: "Tepat waktu pada tanggal lima belas setiap bulan." }
            ]},
            { key: "colleague", title: "Minta Bantuan Rekan Kerja 🙏", group: "Kantor & Bisnis", lines: [
                { hanzi: "小李，你能帮我复印一下这份合同吗？", pinyin: "Xiǎolǐ, nǐ néng bāng wǒ fùyìn yīxià zhè fèn hétóng ma?", translation: "Xiao Li, bisakah kamu bantu saya memfotokopi kontrak ini?" },
                { hanzi: "当然可以，你需要复印几份？", pinyin: "Dāngrán kěyǐ, nǐ xūyào fùyìn jǐ fèn?", translation: "Tentu saja, Anda perlu berapa rangkap?" },
                { hanzi: "十份，谢谢你。这很紧急。", pinyin: "Shí fèn, xièxie nǐ. Zhè hěn jǐnjí.", translation: "Sepuluh rangkap, terima kasih. Ini mendesak." },
                { hanzi: "不客气，五分钟后给你送过去。", pinyin: "Bú kèqi, wǔ fēnzhōng hòu gěi nǐ sòng guòqu.", translation: "Sama-sama, saya akan mengantarkannya dalam lima menit." }
            ]},
            { key: "business_trip", title: "Perjalanan Bisnis 💼", group: "Kantor & Bisnis", lines: [
                { hanzi: "你下周要去北京出差吗？", pinyin: "Nǐ xià zhōu yào qù Běijīng chūchāi ma?", translation: "Apakah kamu akan pergi ke Beijing untuk perjalanan bisnis minggu depan?" },
                { hanzi: "是的，为期三天，去和客户见面。", pinyin: "Shì de, wéiqī sān tiān, qù hé kèhù jiànmiàn.", translation: "Ya, selama tiga hari, untuk bertemu dengan klien." },
                { hanzi: "祝你一切顺利！别忘了带防寒衣物。", pinyin: "Zhù nǐ yīqiè shùnlì! Bié wàng le dài fánghán yīwù.", translation: "Semoga semua berjalan lancar! Jangan lupa bawa pakaian hangat." },
                { hanzi: "谢谢提醒。我会注意的。", pinyin: "Xièxie tíxǐng. Wǒ huì zhùyì de。", translation: "Terima kasih sudah mengingatkan. Saya akan perhatikan." }
            ]},
            // KELOMPOK RUMAH & LINGKUNGAN
            { key: "renting", title: "Mencari Sewa Rumah 🏠", group: "Rumah & Kehidupan", lines: [
                { hanzi: "我想租一套两室一厅的公寓。", pinyin: "Wǒ xiǎng zū yī tào liǎng shì yī tīng de gōngyù.", translation: "Saya ingin menyewa apartemen dua kamar tidur dan satu ruang tamu." },
                { hanzi: "您希望在哪个区域租房？", pinyin: "Nín xīwàng zài nǎge qūyù zū fáng?", translation: "Anda ingin menyewa di area mana?" },
                { hanzi: "最好离地铁站近一点，房租是多少？", pinyin: "Zuì hǎo lí dìtiě zhàn jìn yīdiǎn, fángzū shì duōshao?", translation: "Sebaiknya dekat stasiun kereta bawah tanah, berapa harga sewanya?" },
                { hanzi: "那套每月四千块，押一付三。", pinyin: "Nà tào měi yuè sì qiān kuài, yā yī fù sān.", translation: "Apartemen itu empat ribu Yuan per bulan, bayar tiga bulan di muka dan satu bulan deposit." }
            ]},
            { key: "neighbors", title: "Berbicara dengan Tetangga 🏘️", group: "Rumah & Kehidupan", lines: [
                { hanzi: "李太太，您的花种得真漂亮！", pinyin: "Lǐ tàitai, nín de huā zhòng de zhēn piàoliang!", translation: "Nyonya Li, bunga yang Anda tanam sungguh indah!" },
                { hanzi: "哪里哪里，只是随便种种而已。", pinyin: "Nǎlǐ nǎlǐ, zhǐshì suíbiàn zhǒng zhǒng éryǐ.", translation: "Tidak seberapa, hanya menanam biasa saja." },
                { hanzi: "您家孩子学习怎么样？", pinyin: "Nín jiā háizi xuéxí zěnmeyàng?", translation: "Bagaimana kabar belajar anak Anda?" },
                { hanzi: "他最近很努力，成绩进步了不少。", pinyin: "Tā zuìjìn hěn nǔlì, chéngjì jìnbù le bù shǎo.", translation: "Dia sangat rajin akhir-akhir ini, nilainya meningkat banyak." }
            ]},
            { key: "environment", title: "Isu Lingkungan Hidup ♻️", group: "Masyarakat & Isu Global", lines: [
                { hanzi: "我们应该如何减少塑料垃圾？", pinyin: "Wǒmen yīnggāi rúhé jiǎnshǎo sùliào lājī?", translation: "Bagaimana seharusnya kita mengurangi sampah plastik?" },
                { hanzi: "出门带环保袋，少用一次性餐具。", pinyin: "Chūmén dài huánbǎo dài, shǎo yòng yīcì xìng cānjù.", translation: "Bawa tas belanja saat keluar, dan kurangi penggunaan peralatan makan sekali pakai." },
                { hanzi: "你觉得气候变化严重吗？", pinyin: "Nǐ juéde qìhòu biànhuà yánzhòng ma?", translation: "Apakah menurutmu perubahan iklim itu serius?" },
                { hanzi: "非常严重，它影响着我们每一个人的生活。", pinyin: "Fēicháng yánzhòng, tā yǐngxiǎng zhe wǒmen měi yīgè rén de shēnghuó.", translation: "Sangat serius, itu memengaruhi kehidupan kita masing-masing." }
            ]},
            { key: "city_life", title: "Kehidupan Kota 🏙️", group: "Masyarakat & Isu Global", lines: [
                { hanzi: "你觉得大城市生活怎么样？", pinyin: "Nǐ juéde dà chéngshì shēnghuó zěnmeyàng?", translation: "Menurutmu bagaimana kehidupan di kota besar?" },
                { hanzi: "生活很方便，但是节奏很快。", pinyin: "Shēnghuó hěn fāngbiàn, dànshì jiézòu hěn kuài.", translation: "Hidup sangat nyaman, tetapi ritmenya sangat cepat." },
                { hanzi: "你习惯了这里的交通堵塞吗？", pinyin: "Nǐ xíguàn le zhèlǐ de jiāotōng dǔsè ma?", translation: "Apakah kamu sudah terbiasa dengan kemacetan di sini?" },
                { hanzi: "很难习惯，我尽量乘坐地铁。", pinyin: "Hěn nán xíguàn, wǒ jǐnliàng chéngzuò dìtiě。", translation: "Sulit untuk terbiasa, saya sebisa mungkin naik kereta bawah tanah." }
            ]},
            // KELOMPOK HIBURAN & MEDIA
            { key: "movie", title: "Diskusi Film & Acara TV 🎬", group: "Hiburan & Media", lines: [
                { hanzi: "你最近看了什么好看的电影？", pinyin: "Nǐ zuìjìn kàn le shénme hǎokàn de diànyǐng?", translation: "Film bagus apa yang kamu tonton baru-baru ini?" },
                { hanzi: "我看了《流浪地球》，特效非常棒。", pinyin: 'Wǒ kàn le "Liúlàng Dìqiú", tèxiào fēicháng bàng.', translation: "Saya menonton 'The Wandering Earth', efek spesialnya luar biasa." },
                { hanzi: "你觉得那个男主角演得怎么样？", pinyin: "Nǐ juéde nàge nán zhǔjué yǎn de zěnmeyàng?", translation: "Menurutmu bagaimana akting aktor utamanya?" },
                { hanzi: "他把角色演活了，很有代入感。", pinyin: "Tā bǎ juésè yǎn huó le, hěn yǒu dàirù gǎn.", translation: "Dia menghidupkan karakter itu, sangat bisa dirasakan." }
            ]},
            { key: "social_media", title: "Media Sosial & Berita 📱", group: "Hiburan & Media", lines: [
                { hanzi: "你经常刷朋友圈或微博吗？", pinyin: "Nǐ jīngcháng shuā péngyǒu quān huò wēibó ma?", translation: "Apakah kamu sering *scroll* Momen atau Weibo?" },
                { hanzi: "偶尔看看，主要用来关注新闻。", pinyin: "Ǒu'ěr kànkan, zhǔyào yòng lái guānzhù xīnwén.", translation: "Sesekali melihat, terutama digunakan untuk mengikuti berita." },
                { hanzi: "网上有很多假新闻，你要注意分辨。", pinyin: "Wǎngshàng yǒu hěn duō jiǎ xīnwén, nǐ yào zhùyì fēnbiàn.", translation: "Ada banyak berita palsu di internet, kamu harus hati-hati membedakannya." },
                { hanzi: "你说得对，我会多加思考的。", pinyin: "Nǐ shuō de duì, wǒ huì duō jiā sīkǎo de.", translation: "Anda benar, saya akan lebih banyak berpikir." }
            ]},
            { key: "music_concert", title: "Musik & Konser 🎶", group: "Hiburan & Media", lines: [
                { hanzi: "你喜欢听流行音乐还是古典音乐？", pinyin: "Nǐ xǐhuān tīng liúxíng yīnyuè háishì gǔdiǎn yīnyuè?", translation: "Kamu suka mendengarkan musik pop atau musik klasik?" },
                { hanzi: "我更喜欢流行音乐，因为它很放松。", pinyin: "Wǒ gèng xǐhuān liúxíng yīnyuè, yīnwèi tā hěn fàngsōng.", translation: "Saya lebih suka musik pop, karena sangat menenangkan." },
                { hanzi: "周末有一场演唱会，我们一起去吧？", pinyin: "Zhōumò yǒu yī chǎng yǎnchànghuì, wǒmen yīqǐ qù ba?", translation: "Ada konser akhir pekan ini, bagaimana kalau kita pergi bersama?" },
                { hanzi: "好主意！我马上订票。", pinyin: "Hǎo zhǔyì! Wǒ mǎshàng dìng piào.", translation: "Ide bagus! Saya akan segera memesan tiket." }
            ]},
            { key: "photo", title: "Mengambil Foto 📸", group: "Waktu Luang", lines: [
                { hanzi: "这里的景色太美了，能帮我拍张照吗？", pinyin: "Zhèlǐ de jǐngsè tài měi le, néng bāng wǒ pāi zhāng zhào ma?", translation: "Pemandangan di sini sangat indah, bisakah Anda ambilkan foto saya?" },
                { hanzi: "当然，请站在那个花坛旁边。", pinyin: "Dāngrán, qǐng zhàn zài nàge huātán pángbiān.", translation: "Tentu, tolong berdiri di samping hamparan bunga itu." },
                { hanzi: "这张照片拍得真好，谢谢你！", pinyin: "Zhè zhāng zhàopiàn pāi de zhēn hǎo, xièxie nǐ!", translation: "Foto ini sangat bagus, terima kasih!" },
                { hanzi: "不客气，很高兴能帮到你。", pinyin: "Bú kèqi, hěn gāoxìng néng bāng dào nǐ.", translation: "Sama-sama, senang bisa membantu Anda." }
            ]},
            { key: "hobby_detail", title: "Mendalami Hobi 🎨", group: "Waktu Luang", lines: [
                { hanzi: "你最近在学画画是吗？", pinyin: "Nǐ zuìjìn zài xué huà huà shì ma?", translation: "Kamu sedang belajar melukis ya belakangan ini?" },
                { hanzi: "是的，我喜欢水彩画，它让我平静。", pinyin: "Shì de, wǒ xǐhuān shuǐcǎi huà, tā ràng wǒ píngjìng.", translation: "Ya, saya suka lukisan cat air, itu membuat saya tenang." },
                { hanzi: "你每个星期花多少时间在上面？", pinyin: "Nǐ měi ge xīngqī huā duōshǎo shíjiān zài shàngmian?", translation: "Berapa banyak waktu yang kamu habiskan untuk itu setiap minggu?" },
                { hanzi: "大约六个小时，这是我放松的方式。", pinyin: "Dàyuē liù gè xiǎoshí, zhè shì wǒ fàngsōng de fāngshì。", translation: "Sekitar enam jam, ini adalah cara saya bersantai." }
            ]},
            // KELOMPOK KESEHATAN & PERAWATAN
            { key: "doctor", title: "Mengunjungi Dokter 🏥", group: "Kesehatan & Perawatan", lines: [
                { hanzi: "我嗓子疼，还有点发烧。", pinyin: "Wǒ sǎngzi téng, hái yǒudiǎn fāshāo.", translation: "Tenggorokan saya sakit dan sedikit demam." },
                { hanzi: "请张开嘴，我看看。你感冒了。", pinyin: "Qǐng zhāng kāi zuǐ, wǒ kànkan. Nǐ gǎnmào le.", translation: "Tolong buka mulut Anda, saya periksa. Anda terkena flu." },
                { hanzi: "需要打针吃药吗？", pinyin: "Xūyào dǎ zhēn chī yào ma?", translation: "Apakah perlu disuntik atau minum obat?" },
                { hanzi: "先吃药吧，多喝水，多休息。", pinyin: "Xiān chī yào ba, duō hē shuǐ, duō xiūxi.", translation: "Minum obat dulu, banyak minum air, dan banyak istirahat." }
            ]},
            { key: "visiting_sick", title: "Mengunjungi Orang Sakit 😷", group: "Kesehatan & Perawatan", lines: [
                { hanzi: "听说你病了，现在感觉好点了吗？", pinyin: "Tīngshuō nǐ bìng le, xiànzài gǎnjué hǎo diǎn le ma?", translation: "Dengar-dengar kamu sakit, apakah sekarang sudah merasa lebih baik?" },
                { hanzi: "好多了，谢谢你来看我。", pinyin: "Hǎo duō le, xièxie nǐ lái kàn wǒ.", translation: "Jauh lebih baik, terima kasih sudah menjenguk saya." },
                { hanzi: "这是我给你买的水果，你慢慢吃。", pinyin: "Zhè shì wǒ gěi nǐ mǎi de shuǐguǒ, nǐ mànmàn chī.", translation: "Ini buah-buahan yang saya belikan untukmu, makan pelan-pelan." },
                { hanzi: "谢谢你，让你费心了。我很快就会康复的。", pinyin: "Xièxie nǐ, ràng nǐ fèixīn le. Wǒ hěn kuài jiù huì kāngfù de.", translation: "Terima kasih, maaf sudah merepotkanmu. Saya akan segera pulih." }
            ]},
            { key: "lost_found", title: "Barang Hilang & Ditemukan 🔍", group: "Kehidupan Sehari-hari", lines: [
                { hanzi: "我的钱包好像丢了，该怎么办？", pinyin: "Wǒ de qiánbāo hǎoxiàng diū le, gāi zěnme bàn?", translation: "Dompet saya sepertinya hilang, apa yang harus dilakukan?" },
                { hanzi: "你最后一次看到它是在哪里？", pinyin: "Nǐ zuìhòu yī cì kàndào tā shì zài nǎlǐ?", translation: "Di mana terakhir kali Anda melihatnya?" },
                { hanzi: "在地铁站。我应该去失物招领处问问。", pinyin: "Zài dìtiě zhàn. Wǒ yīnggāi qù shīwù zhāolǐng chù wènwen.", translation: "Di stasiun kereta bawah tanah. Saya harus pergi ke bagian barang hilang untuk bertanya." },
                { hanzi: "祝你好运，希望它能被找到。", pinyin: "Zhù nǐ hǎo yùn, xīwàng tā néng bèi zhǎodào.", translation: "Semoga berhasil, semoga bisa ditemukan." }
            ]},
            { key: "emergency", title: "Situasi Darurat 🚨", group: "Kehidupan Sehari-hari", lines: [
                { hanzi: "快打120！这里有人晕倒了！", pinyin: "Kuài dǎ yāolínglíng! Zhèlǐ yǒu rén yūndǎo le!", translation: "Cepat hubungi 120 (Ambulans)! Ada orang pingsan di sini!" },
                { hanzi: "请保持冷静，告诉我你现在的位置。", pinyin: "Qǐng bǎochí lěngjìng, gàosù wǒ nǐ xiànzài de wèizhi.", translation: "Tolong tetap tenang, beritahu saya lokasi Anda sekarang." },
                { hanzi: "我们在市中心的大厦门口。", pinyin: "Wǒmen zài shì zhōngxīn de dàshà ménkǒu.", translation: "Kami berada di gerbang gedung besar pusat kota." },
                { hanzi: "救护车马上就到，请不要移动他。", pinyin: "Jiùhù chē mǎshàng jiù dào, qǐng bú yào yídòng tā。", translation: "Ambulans akan segera tiba, tolong jangan pindahkan dia." }
            ]},
            // KELOMPOK LAYANAN PUBLIK
            { key: "post_office", title: "Di Kantor Pos ✉️", group: "Layanan Publik", lines: [
                { hanzi: "我想寄一个包裹到国外。", pinyin: "Wǒ xiǎng jì yī gè bāoguǒ dào guówài.", translation: "Saya ingin mengirim paket ke luar negeri." },
                { hanzi: "请问您需要普通邮件还是特快专递？", pinyin: "Qǐngwèn nín xūyào pǔtōng yóujiàn háishì tèkuài zhuāndì?", translation: "Permisi, Anda memerlukan pos reguler atau pos kilat?" },
                { hanzi: "特快专递要多久才能到？", pinyin: "Tèkuài zhuāndì yào duōjiǔ cái néng dào?", translation: "Berapa lama pos kilat akan sampai?" },
                { hanzi: "大约五到七个工作日。请填写这张单子。", pinyin: "Dàyuē wǔ dào qī gè gōngzuò rì. Qǐng tiánxiě zhè zhāng dānzi.", translation: "Kira-kira lima sampai tujuh hari kerja. Silakan isi formulir ini." }
            ]},
            { key: "bank", title: "Di Bank 🏦", group: "Layanan Publik", lines: [
                { hanzi: "你好，我想开一个储蓄账户。", pinyin: "Nǐ hǎo, wǒ xiǎng kāi yī gè chúxù zhànghù.", translation: "Halo, saya ingin membuka rekening tabungan." },
                { hanzi: "请出示您的身份证或护照。", pinyin: "Qǐng chūshì nín de shēnfèn zhèng huò hùzhào.", translation: "Tolong tunjukkan kartu identitas atau paspor Anda." },
                { hanzi: "有没有网上银行服务？", pinyin: "Yǒu méiyǒu wǎngshàng yínháng fúwù?", translation: "Apakah ada layanan *internet banking*?" },
                { hanzi: "有的，我们可以帮您当场激活。", pinyin: "Yǒu de, wǒmen kěyǐ bāng nín dāngchǎng jīhuó。", translation: "Ada, kami bisa membantu Anda mengaktifkannya di tempat." }
            ]},
            { key: "complaint", title: "Mengajukan Keluhan 😠", group: "Layanan Publik", lines: [
                { hanzi: "对不起，我想投诉一下服务。", pinyin: "Duìbùqǐ, wǒ xiǎng tóusù yīxià fúwù.", translation: "Maaf, saya ingin mengajukan keluhan tentang layanan." },
                { hanzi: "请问具体发生了什么问题？", pinyin: "Qǐngwèn jùtǐ fāshēng le shénme wèntí?", translation: "Boleh tahu masalah spesifik apa yang terjadi?" },
                { hanzi: "我点的菜等了快一个小时了。", pinyin: "Wǒ diǎn de cài děng le kuài yī ge xiǎoshí le.", translation: "Pesanan makanan saya sudah menunggu hampir satu jam." },
                { hanzi: "非常抱歉，我们马上为您处理。", pinyin: "Fēicháng bàoqiàn, wǒmen mǎshàng wèi nín chǔlǐ。", translation: "Mohon maaf sekali, kami akan segera menanganinya untuk Anda." }
            ]},
            { key: "driving_license", title: "Izin Mengemudi 🚗", group: "Layanan Publik", lines: [
                { hanzi: "我想在中国考驾照，流程复杂吗？", pinyin: "Wǒ xiǎng zài Zhōngguó kǎo jiàzhào, liúchéng fùzá ma?", translation: "Saya ingin mengambil SIM di Tiongkok, apakah prosedurnya rumit?" },
                { hanzi: "有点复杂，你需要先通过理论考试。", pinyin: "Yǒudiǎn fùzá, nǐ xūyào xiān tōngguò lǐlùn kǎoshì.", translation: "Agak rumit, Anda harus lulus ujian teori terlebih dahulu." },
                { hanzi: "理论考试的题目是中文的吗？", pinyin: "Lǐlùn kǎoshì de tímù shì Zhōngwén de ma?", translation: "Apakah soal ujian teorinya dalam bahasa Mandarin?" },
                { hanzi: "你可以选择英文或中文。最好多做练习。", pinyin: "Nǐ kěyǐ xuǎnzé Yīngwén huò Zhōngwén. Zuì hǎo duō zuò liànxí。", translation: "Anda bisa memilih bahasa Inggris atau Mandarin. Sebaiknya banyak berlatih." }
            ]},
            // KELOMPOK TEKNOLOGI
            { key: "new_topic_1", title: "Menggunakan Teknologi Baru 📱", group: "Tren & Teknologi", lines: [
                { hanzi: "你买了最新的手机吗？", pinyin: "Nǐ mǎile zuìxīn de shǒujī ma?", translation: "Apakah kamu membeli ponsel terbaru?" },
                { hanzi: "是的，它的拍照功能很强大。", pinyin: "Shì de, tā de pāizhào gōngnéng hěn qiángdà.", translation: "Ya, fungsi kameranya sangat kuat." },
                { hanzi: "我觉得它的价格有点高。", pinyin: "Wǒ juéde tā de jiàgé yǒudiǎn gāo.", translation: "Menurut saya harganya agak mahal." },
                { hanzi: "但它值得。这是未来。", pinyin: "Dàn tā zhíde. Zhè shì wèilái.", translation: "Tapi itu layak. Ini adalah masa depan." }
            ]},
            { key: "technology", title: "Teknologi & Internet 💻", group: "Tren & Teknologi", lines: [
                { hanzi: "你的新电脑运行速度快吗？", pinyin: "Nǐ de xīn diànnǎo yùnxíng sùdù kuài ma?", translation: "Apakah kecepatan operasi komputer barumu cepat?" },
                { hanzi: "非常快，而且可以连接很多智能设备。", pinyin: "Fēicháng kuài, érqiě kěyǐ liánjiē hěn duō zhìnéng shèbèi.", translation: "Sangat cepat, dan bisa terhubung dengan banyak perangkat pintar." },
                { hanzi: "现在很多工作都离不开互联网了。", pinyin: "Xiànzài hěn duō gōngzuò dōu lí bù kāi hùliánwǎng le.", translation: "Banyak pekerjaan sekarang tidak bisa lepas dari internet." },
                { hanzi: "对。未来是人工智能和大数据时代。", pinyin: "Duì. Wèilái shì réngōng zhìnéng hé dà shùjù shídài.", translation: "Ya. Masa depan adalah era AI (kecerdasan buatan) dan *big data*." }
            ]},
            { key: "learning_tech", title: "Belajar Menggunakan Aplikasi 🌐", group: "Tren & Teknologi", lines: [
                { hanzi: "你用什么软件学中文？", pinyin: "Nǐ yòng shénme ruǎnjiàn xué Zhōngwén?", translation: "Aplikasi apa yang kamu gunakan untuk belajar bahasa Mandarin?" },
                { hanzi: "我用一个叫'HelloChinese'的应用，很有用。", pinyin: "Wǒ yòng yī gè jiào 'HelloChinese' de yìngyòng, hěn yǒuyòng.", translation: "Saya menggunakan aplikasi bernama 'HelloChinese', sangat berguna." },
                { hanzi: "你觉得在线课程效果好吗？", pinyin: "Nǐ juéde zàixiàn kèchéng xiàoguǒ hǎo ma?", translation: "Apakah menurutmu kursus online efektif?" },
                { hanzi: "不错，但需要很强的自律性。", pinyin: "Búcuò, dàn xūyào hěn qiáng de zìlǜ xìng。", translation: "Lumayan, tapi membutuhkan disiplin diri yang kuat." }
            ]}
        ];

        // --- Variabel dan Fungsi Paginasi ---
        const totalTopics = allTopics.length;
        const totalPages = Math.ceil(totalTopics / itemsPerPage);

        function getTopicsForPage(page) {
            const start = (page - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            return allTopics.slice(start, end);
        }

        function updatePaginationControls() {
            prevPageBtn.disabled = currentPage === 1;
            nextPageBtn.disabled = currentPage === totalPages;
            pageInfo.textContent = `Halaman ${currentPage}/${totalPages}`;
        }

        function populateDropdown() {
            // Hapus opsi lama
            dialogSelector.innerHTML = '';
            
            const topicsOnPage = getTopicsForPage(currentPage);
            let currentGroup = '';

            topicsOnPage.forEach(topic => {
                // Membuat label group (optgroup)
                if (topic.group && topic.group !== currentGroup) {
                    const optgroup = document.createElement('optgroup');
                    optgroup.label = `--- ${topic.group} ---`;
                    dialogSelector.appendChild(optgroup);
                    currentGroup = topic.group;
                }
                
                const option = document.createElement('option');
                option.value = topic.key;
                option.textContent = topic.title;
                dialogSelector.appendChild(option);
            });
            
            // Pilih topik yang sedang aktif jika ada di halaman ini
            dialogSelector.value = currentTopicKey;
            if (dialogSelector.value !== currentTopicKey) {
                // Jika topik aktif tidak ada di halaman baru, default ke yang pertama
                currentTopicKey = topicsOnPage[0].key;
                dialogSelector.value = currentTopicKey;
            }
            
            loadDialogue(currentTopicKey); // Muat dialog pertama/aktif di halaman baru
            updatePaginationControls();
        }
        
        function changePage(newPage) {
            if (newPage >= 1 && newPage <= totalPages) {
                currentPage = newPage;
                populateDropdown();
            }
        }

        // --- Fungsi Muat Dialog ---

        function loadDialogue(key) {
            currentTopicKey = key;
            const topic = allTopics.find(t => t.key === key);
            if (!topic) return;

            dialogTitle.textContent = topic.title;
            dialogDisplay.innerHTML = '';
            activeLineIndex = 0;

            topic.lines.forEach((line, index) => {
                const lineDiv = document.createElement('div');
                lineDiv.className = 'dialog-line';
                if (index === 0) {
                    lineDiv.classList.add('active'); // Baris pertama aktif secara default
                }
                lineDiv.setAttribute('data-index', index);
                lineDiv.onclick = () => selectLine(index);

                lineDiv.innerHTML = `
                    <div class="line-hanzi">${line.hanzi}</div>
                    <div class="line-pinyin">${line.pinyin}</div>
                    <div class="line-translation">(${line.translation})</div>
                `;
                dialogDisplay.appendChild(lineDiv);
            });
        }
        
        function selectLine(index) {
            activeLineIndex = index;
            document.querySelectorAll('.dialog-line').forEach(div => {
                div.classList.remove('active');
            });
            const selectedLine = document.querySelector(`.dialog-line[data-index="${index}"]`);
            if (selectedLine) {
                selectedLine.classList.add('active');
            }
        }
        
        // --- Speech Synthesis (TTS) Functions ---

        function initializeVoice() {
            if (synth.getVoices().length === 0) {
                synth.onvoiceschanged = findChineseVoice;
            } else {
                findChineseVoice();
            }
        }

        function findChineseVoice() {
            const voices = synth.getVoices();
            // Prioritaskan suara Mandarin (zh-CN) dengan kualitas terbaik jika tersedia
            chineseVoice = voices.find(voice => 
                voice.lang === 'zh-CN' && voice.name.includes('Google') || 
                voice.lang === 'zh-CN' || 
                voice.name.includes('Mandarin')
            );
            
            if (chineseVoice) {
                statusMessage.textContent = 'Suara Mandarin siap digunakan! 🎉';
                listenBtn.disabled = false;
            } else {
                statusMessage.textContent = '❌ Tidak dapat menemukan suara Mandarin di browser Anda. Audio mungkin menggunakan suara default.';
                listenBtn.disabled = false; // Tetap aktifkan, akan menggunakan suara default
            }
        }

        function readActiveTarget() {
            if (synth.speaking) {
                synth.cancel();
                listenBtn.textContent = 'Dengarkan Baris Aktif 🎧';
                listenBtn.classList.remove('loading');
                return;
            }

            const currentTopic = allTopics.find(t => t.key === currentTopicKey);
            if (!currentTopic || !currentTopic.lines[activeLineIndex]) {
                statusMessage.textContent = 'Tidak ada baris dialog yang ditemukan.';
                return;
            }

            const textToSpeak = currentTopic.lines[activeLineIndex].hanzi;
            const utterance = new SpeechSynthesisUtterance(textToSpeak);
            
            utterance.voice = chineseVoice || synth.getVoices().find(v => v.lang.startsWith('zh'));
            utterance.lang = 'zh-CN';
            utterance.rate = 0.95; 

            listenBtn.textContent = 'Mengucapkan...';
            listenBtn.classList.add('loading');
            
            utterance.onend = () => {
                listenBtn.textContent = 'Dengarkan Baris Aktif 🎧';
                listenBtn.classList.remove('loading');
                // Pindah ke baris berikutnya secara otomatis
                const nextIndex = activeLineIndex + 1;
                if (currentTopic.lines[nextIndex]) {
                    selectLine(nextIndex);
                }
            };
            utterance.onerror = (event) => {
                listenBtn.textContent = 'Dengarkan Baris Aktif 🎧';
                listenBtn.classList.remove('loading');
                statusMessage.textContent = `Error bicara: ${event.error}`;
            };

            synth.speak(utterance);
        }

        // --- Initial Load ---
        function loadInitialDialogue() {
            changePage(1); // Muat halaman pertama dan isi dropdown
            // loadDialogue('greeting'); // Dipanggil di dalam populateDropdown
            initializeVoice();
        }

        // Mulai aplikasi saat DOM dimuat
        document.addEventListener('DOMContentLoaded', loadInitialDialogue);
    </script>
</body>
</html>
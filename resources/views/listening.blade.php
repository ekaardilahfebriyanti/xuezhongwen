@extends('layout.main')

@section('title', 'Praktek Listening')

@section('content')
  <main class="container my-5">
    <div class="scoped-css2">
      <h1>Latihan Mendengarkan Mandarin</h1>

      <div id="list"></div>
    </div>
  </main>
@endsection

@section('js')
  <script>
    const sentences = [
      {
        text: '听 得 见 吗',
        pinyin: 'tīng de jiàn ma',
        english: 'Can you hear me?',
        indonesian: 'Apakah kamu bisa mendengarku?',
        audio: 'canuhearme.mp3',
        source: 'https://youtu.be/rXw367Uyxss?t=217'
      },
      {
        text: '记得 我 吗?',
        pinyin: 'jì de wǒ ma?',
        english: 'Do you remember me?',
        indonesian: 'Apakah kamu mengingatku?',
        audio: 'rememberme.mp3',
        source: 'https://youtu.be/PN4gd45kLas?t=1151'
      },
      {
        text: '会 做 饭 吗？',
        pinyin: 'huì zuò fàn ma?',
        english: 'Can you cook?',
        indonesian: 'Apakah kamu bisa memasak?',
        audio: 'cook.mp3',
        source: 'https://youtu.be/M_Z0_OVZDv8?t=286'
      },
      {
        text: '主要 真的 不怪我',
        pinyin: 'zhǔ yào zhēn de bú guài wǒ',
        english: 'It\'s truly not my fault',
        indonesian: 'Sebenarnya, itu benar-benar bukan salahku.',
        audio: 'notmyfault.mp3',
        source: 'https://youtu.be/OA-oZ5Y5Xx8?t=750'
      },
      {
        text: '你 早点 休息 吧',
        pinyin: 'nǐ zǎo diǎn xiū xi ba',
        english: 'You should rest early',
        indonesian: 'Kamu istirahatlah lebih awal.',
        audio: 'rest.mp3',
        source: 'https://youtu.be/eNv8saOz1Qk?t=344'
      },
      {
        text: '你 怎么 在 这儿?',
        pinyin: 'nǐ zěn me zài zhèr?',
        english: 'Why are you here?',
        indonesian: 'Kenapa kamu ada di sini?',
        audio: 'whathere.mp3',
        source: 'https://youtu.be/PN4gd45kLas?t=1155'
      },
      {
        text: '你 也是 来 相亲 的',
        pinyin: 'nǐ yě shì lái xiāng qīn de',
        english: 'You are also here for a blind date',
        indonesian: 'Kamu juga datang untuk kencan buta?',
        audio: 'blinddate.mp3',
        source: 'https://youtu.be/PN4gd45kLas?t=158'
      },
      {
        text: '这 是 我自己的 选择',
        pinyin: 'zhè shì wǒ zì jǐ de xuǎn zé',
        english: 'This is my own choice',
        indonesian: 'Ini adalah pilihanku sendiri.',
        audio: 'mychoice.mp3',
        source: 'https://youtu.be/kxxPHK1atkU?t=1768'
      },
      {
        text: '情报 组 在 里面 吗',
        pinyin: 'qíng bào zǔ zài lǐ miàn ma',
        english: 'Is the intelligence team inside?',
        indonesian: 'Apakah tim intelijen ada di dalam?',
        audio: 'intelligence.mp3',
        source: 'https://youtu.be/5GUvds-UbVU?t=187'
      },
      {
        text: '这儿 就 是 我们的 家',
        pinyin: 'zhèr jiù shì wǒ men de jiā',
        english: 'This is our home',
        indonesian: 'Di sinilah rumah kita.',
        audio: 'ourhome.mp3',
        source: 'https://youtu.be/4Bjk6ZvAVmw?t=90'
      },
      {
        text: '至于 他们 是 谁...',
        pinyin: 'zhì yú tā men shì shéi...',
        english: 'As for who they are...',
        indonesian: 'Mengenai siapa mereka...',
        audio: 'whotheyare.mp3',
        source: 'https://youtu.be/ldSAl8azgp8?t=293'
      },
      {
        text: '现在 是 我 有 疑问!',
        pinyin: 'xiàn zài shì wǒ yǒu yí wèn!',
        english: 'Now I have a question/doubt!',
        indonesian: 'Sekarang aku yang punya pertanyaan!',
        audio: 'doubts.mp3',
        source: 'https://youtu.be/svU2t3c1IL4?t=583'
      },
      {
        text: '二十 年 的 朋友了 吧',
        pinyin: 'èr shí nián de péng yǒu le ba',
        english: 'We have been friends for twenty years, right?',
        indonesian: 'Kita sudah berteman selama dua puluh tahun, kan?',
        audio: 'friends.mp3',
        source: 'https://youtu.be/svU2t3c1IL4?t=2784'
      },
      {
        text: '我 觉得 他 可能 ...',
        pinyin: 'wǒ jué de tā kě néng ...',
        english: 'I think he might...',
        indonesian: 'Aku rasa dia mungkin...',
        audio: 'hemight.mp3',
        source: 'https://youtu.be/4mNGsx9umI0?t=6310'
      },
      {
        text: '你 知道 我 是 谁 吗?',
        pinyin: 'nǐ zhī dào wǒ shì shéi ma?',
        english: 'Do you know who I am?',
        indonesian: 'Apakah kamu tahu siapa aku?',
        audio: 'whoiam.mp3',
        source: 'https://youtu.be/OlOgSHTG4sU?t=1400'
      },
      {
        text: '我 就 真 搞 不 清楚 了',
        pinyin: 'wǒ jiù zhēn gǎo bù qīng chǔ le',
        english: 'I really can\'t figure it out',
        indonesian: 'Aku benar-benar tidak mengerti/tidak tahu.',
        audio: 'cantfigureitout.mp3',
        source: 'https://www.youtube.com/watch?v=thqNmtaelkc'
      },
      {
        text: '你 真 的 喜欢 歌剧 吗？',
        pinyin: 'nǐ zhēn de xǐ huān gē jù ma?',
        english: 'Do you really like opera?',
        indonesian: 'Apakah kamu benar-benar menyukai opera?',
        audio: 'opera.mp3',
        source: 'https://youtu.be/M_Z0_OVZDv8?t=142'
      },
      {
        text: '可不可 以 喝 点 水 啊？',
        pinyin: 'kě bù kě yǐ hē diǎn shuǐ a?',
        english: 'Can I have some water?',
        indonesian: 'Bisakah saya/kami minum air?',
        audio: 'havewater.mp3',
        source: 'https://youtu.be/M_Z0_OVZDv8?t=339'
      },
      {
        text: '我 刚 也 在 说 这个 问题',
        pinyin: 'wǒ gāng yě zài shuō zhè ge wèn tí',
        english: 'I was just talking about this issue',
        indonesian: 'Aku baru saja membicarakan masalah ini.',
        audio: 'askingthesame.mp3',
        source: 'https://youtu.be/ldSAl8azgp8?t=1323'
      },
      {
        text: '导游 通知 的 是 七 点 半',
        pinyin: 'dǎo yóu tōng zhī de shì qī diǎn bàn',
        english: 'The tour guide said 7:30',
        indonesian: 'Pemandu wisata memberi tahu pukul setengah tujuh.',
        audio: 'meetat.mp3',
        source: 'https://youtu.be/vbLEDqfTxVI?t=233'
      },
      {
        text: '好 那 马上 视频 马上 视频',
        pinyin: 'hǎo nà mǎ shàng shì pín mǎ shàng shì pín',
        english: 'Okay, let\'s do a video call immediately',
        indonesian: 'Oke, segera lakukan panggilan video, segera lakukan panggilan video.',
        audio: 'doacall.mp3',
        source: 'https://youtu.be/rXw367Uyxss?t=69'
      },
      {
        text: '我 去 超市 买 了 点 东西。',
        pinyin: 'wǒ qù chāo shì mǎi le diǎn dōng xi.',
        english: 'I went to the supermarket and bought some things.',
        indonesian: 'Aku pergi ke supermarket dan membeli beberapa barang.',
        audio: 'a1.mp3',
        source: 'https://youtu.be/FN6iUHwieho?t=14'
      },
      {
        text: '你 买 什么 了 ？ 让 我 看看。',
        pinyin: 'nǐ mǎi shén me le ? ràng wǒ kàn kàn.',
        english: 'What did you buy? Let me see.',
        indonesian: 'Kamu membeli apa? Coba aku lihat.',
        audio: 'letmesee.mp3',
        source: 'https://youtu.be/FN6iUHwieho?t=16'
      },
      {
        text: '我 买 了 两盒 牛奶， 一些 水果',
        pinyin: 'wǒ mǎi le liǎng hé niú nǎi, yì xiē shuǐ guǒ',
        english: 'I bought two boxes of milk and some fruit',
        indonesian: 'Aku membeli dua kotak susu, dan beberapa buah.',
        audio: 'ibought.mp3',
        source: 'https://youtu.be/FN6iUHwieho?t=19'
      },
      {
        text: '你们 有人 跟 迪迪 联系 上了 吗',
        pinyin: 'nǐ men yǒu rén gēn dí dí lián xì shàng le ma',
        english: 'Did anyone contact Didi?',
        indonesian: 'Apakah ada di antara kalian yang sudah menghubungi Didi?',
        audio: 'contacted.mp3',
        source: 'https://youtu.be/vbLEDqfTxVI?t=615'
      },
      {
        text: '你 要 多少 钱 才能 离开 我 儿子',
        pinyin: 'nǐ yào duō shǎo qián cái néng lí kāi wǒ ér zi',
        english: 'How much money do you want to leave my son?',
        indonesian: 'Berapa banyak uang yang kamu inginkan agar meninggalkan putraku?',
        audio: 'leavemyson.mp3',
        source: 'https://youtu.be/TjWaLmTSaNw?t=263'
      },
      {
        text: '凭 你 自己的 真 本事... 打下我',
        pinyin: 'píng nǐ zì jǐ de zhēn běn shì... dǎ xià wǒ',
        english: 'Use your real skills... to defeat me',
        indonesian: 'Buktikan dengan kemampuanmu yang sebenarnya... kalahkan aku.',
        audio: 'letsfight.mp3',
        source: 'https://youtu.be/4Bjk6ZvAVmw?t=137'
      },
      {
        text: '什么 样 的 苦难 我 都 愿意 去 吃',
        pinyin: 'shén me yàng de kǔ nàn wǒ dōu yuàn yì qù chī',
        english: 'I am willing to endure any kind of suffering',
        indonesian: 'Aku rela menanggung penderitaan apa pun.',
        audio: 'suffering.mp3',
        source: 'https://youtu.be/5GUvds-UbVU?t=331'
      },
      {
        text: '当然 必须 坚持 这个 项目 要 全力以赴',
        pinyin: 'dāng rán bì xū jiān chí zhè ge xiàng mù yào quán lì yǐ fù',
        english: 'Of course we must persist in this project and go all out',
        indonesian: 'Tentu saja kita harus mempertahankan proyek ini dan mengerahkan seluruh kemampuan.',
        audio: 'project.mp3',
        source: 'https://youtu.be/thqNmtaelkc?t=458'
      },
      {
        text: '为什么 还 反复 将 她 拖 行了 几 下',
        pinyin: 'wèi shén me hái fǎn fù jiāng tā tuō xíng le jǐ xià',
        english: 'Why did (he) repeatedly drag her a few times',
        indonesian: 'Mengapa (dia) berulang kali menyeretnya beberapa kali?',
        audio: 'dragged.mp3',
        source: 'https://youtu.be/4p8cfG1O4JA?t=2299'
      },
      {
        text: '我 不 知道 你 有没有 害怕 失去 过 她',
        pinyin: 'wǒ bù zhī dào nǐ yǒu méi yǒu hài pà shī qù guò tā',
        english: 'I don\'t know if you\'ve ever been afraid of losing her',
        indonesian: 'Aku tidak tahu apakah kamu pernah takut kehilangannya.',
        audio: 'losingher.mp3',
        source: 'https://youtu.be/4mNGsx9umI0?t=3949'
      },
      {
        text: '第一页 可以 查 到 我, 你 去 了 解 一 下',
        pinyin: 'dì yī yè kě yǐ chá dào wǒ, nǐ qù liǎo jiě yí xià',
        english: 'You can find me on the first page, go check it out',
        indonesian: 'Kamu bisa mencari (informasiku) di halaman pertama, coba cari tahu.',
        audio: 'firstpage.mp3',
        source: 'https://youtu.be/OlOgSHTG4sU?t=1413'
      }
    ];

    const container = document.getElementById('list');

  sentences.forEach(item => {
    const div = document.createElement('div');
    div.className = 'item mb-4';

    // Container teks Hanzi, Pinyin, dst
    const textContainer = document.createElement('div');
    textContainer.className = 'listening-text';
    textContainer.style.display = 'none';

    const hanzi = document.createElement('div');
    hanzi.className = 'hanzi';
    hanzi.textContent = item.text;

    const pinyin = document.createElement('div');
    pinyin.className = 'pinyin';
    pinyin.textContent = item.pinyin;

    const english = document.createElement('div');
    english.className = 'english';
    english.textContent = item.english;

    const indonesian = document.createElement('div');
    indonesian.className = 'indonesian';
    indonesian.textContent = item.indonesian;

    textContainer.appendChild(hanzi);
    textContainer.appendChild(pinyin);
    textContainer.appendChild(english);
    textContainer.appendChild(indonesian);

    // Tombol Play dan Toggle
    const playBtn = document.createElement('button');
    playBtn.className = 'btn btn-primary btn-sm';
    playBtn.textContent = 'Putar';

    const toggleBtn = document.createElement('button');
    toggleBtn.className = 'btn btn-outline-secondary btn-sm';
    toggleBtn.textContent = 'Tampilkan Hanzi & Terjemahan';

    // Bungkus tombol dengan div Bootstrap
    const buttonGroup = document.createElement('div');
    buttonGroup.className = 'd-flex gap-2 mb-2';
    buttonGroup.appendChild(playBtn);
    buttonGroup.appendChild(toggleBtn);

    // Audio
    const audio = new Audio("{{ asset('learn-chinese-by-listening/public/audio/') }}/" + item.audio);

    playBtn.addEventListener('click', () => {
      document.querySelectorAll('audio').forEach(a => a.pause());
      if (audio.paused) {
        audio.play();
        playBtn.textContent = 'Jeda';
      } else {
        audio.pause();
        playBtn.textContent = 'Putar';
      }
    });

    audio.addEventListener('ended', () => {
      playBtn.textContent = 'Putar';
    });

    toggleBtn.addEventListener('click', () => {
      const isHidden = textContainer.style.display === 'none';
      textContainer.style.display = isHidden ? 'block' : 'none';
      toggleBtn.textContent = isHidden ? 'Sembunyikan Hanzi & Terjemahan' : 'Tampilkan Hanzi & Terjemahan';
    });

    // Link sumber YouTube
    const sourceDiv = document.createElement('div');
    sourceDiv.className = 'source mt-2';
    const link = document.createElement('a');
    link.href = item.source;
    link.target = '_blank';
    link.rel = 'noopener noreferrer';
    link.textContent = 'View on YouTube';
    sourceDiv.appendChild(document.createTextNode('Source: '));
    sourceDiv.appendChild(link);

    // Struktur akhir
    div.appendChild(textContainer);
    div.appendChild(buttonGroup);
    div.appendChild(sourceDiv);

    container.appendChild(div);
  });
  </script>
@endsection

@extends('layout.main')

@section('title', 'Pinyin Chart')

@section('content')
<div class="pinyin-audio-chart">
  <h2>Mandarin Pinyin Audio Chart</h2>
  <p style="text-align:center;">Klik pinyin → pilih nada → dengarkan audio 🔊</p>

  <div class="pinyin-grid" id="grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 10px;"></div>
</div>
@endsection

@section('js')
<script>
  // Full list of pinyins (your data)
  const rawPinyins = [
    "a","ai","an","ang","ao","ba","bai","ban","bang","bao","bei","ben","beng","bi","bian","biao","bie",
    "bin","bing","bo","bu","ca","cai","can","cang","cao","ce","cen","ceng","cha","chai","chan","chang",
    "chao","che","chen","cheng","chi","chong","chou","chu","chua","chuai","chuan","chuang","chui","chun",
    "chuo","ci","cong","cou","cu","cuan","cui","cun","cuo","da","dai","dan","dang","dao","de","dei","den",
    "deng","di","dian","diang","diao","die","ding","diu","dong","dou","du","duan","dui","dun","duo","e",
    "ei","en","er","fa","fan","fang","fei","fen","feng","fo","fou","fu","ga","gai","gan","gang","gao","ge",
    "gei","gen","geng","gong","gou","gu","gua","guai","guan","guang","gui","gun","guo","ha","hai","han",
    "hang","hao","he","hei","hen","heng","hong","hou","hu","hua","huai","huan","huang","hui","hun","huo",
    "ji","jia","jian","jiang","jiao","jie","jin","jing","jiong","jiu","ju","juan","jue","jun","ka","kai",
    "kan","kang","kao","ke","ken","keng","kong","kou","ku","kua","kuai","kuan","kuang","kui","kun","kuo",
    "la","lai","lan","lang","lao","le","lei","leng","li","lia","lian","liang","liao","lie","lin","ling",
    "liu","lo","long","lou","lu","luan","lun","luo","luu","luue","luun","ma","mai","man","mang","mao",
    "me","mei","men","meng","mi","mian","miao","mie","min","ming","miu","mo","mou","mu","muo","na","nai",
    "nan","nang","nao","ne","nei","nen","neng","ni","nia","nian","niang","niao","nie","nin","ning","niu",
    "nong","nou","nu","nuan","nue","nun","nuo","nuu","nuue","ou","pa","pai","pan","pang","pao","pei","pen",
    "peng","pi","pian","piao","pie","pin","ping","po","pou","pu","qi","qia","qian","qiang","qiao","qie",
    "qin","qing","qiong","qiu","qu","quan","que","qun","ran","rang","rao","re","rei","ren","reng","ri",
    "rong","rou","ru","ruan","rui","run","ruo","sa","sai","san","sang","sao","se","sei","sen","seng","sha",
    "shai","shan","shang","shao","she","shei","shen","sheng","shi","shong","shou","shu","shua","shuai",
    "shuan","shuang","shui","shun","shuo","si","song","sou","su","suan","sui","sun","suo","ta","tai","tan",
    "tang","tao","te","teng","ti","tian","tiao","tie","ting","tong","tou","tu","tuan","tui","tun","tuo",
    "wa","wai","wan","wang","wei","wen","weng","wo","wu","xi","xia","xian","xiang","xiao","xie","xin",
    "xing","xiong","xiu","xu","xuan","xue","xun","ya","yan","yang","yao","ye","yi","yin","ying","yong",
    "you","yu","yuan","yue","yun","za","zai","zan","zang","zao","ze","zei","zen","zeng","zha","zhai",
    "zhan","zhang","zhao","zhe","zhei","zhen","zheng","zhi","zhong","zhou","zhu","zhua","zhuai","zhuan",
    "zhuang","zhui","zhun","zhuo","zi","zong","zou","zu","zuan","zui","zun","zuo"
  ];

  // Ubah raw pinyins jadi bentuk: { text: "lüe", audio: "luue" }
  const pinyins = rawPinyins.map(p => {
    const text = p.replace(/uu/g, "ü").replace(/v/g, "ü");
    return { text, audio: p };
  });

  const tones = [1, 2, 3, 4];
  const container = document.getElementById("grid");

  // Fungsi mengaplikasikan tone pada huruf vokal utama
  function applyTone(pinyin, tone) {
    const map = {
      a: ["ā", "á", "ǎ", "à"],
      o: ["ō", "ó", "ǒ", "ò"],
      e: ["ē", "é", "ě", "è"],
      i: ["ī", "í", "ǐ", "ì"],
      u: ["ū", "ú", "ǔ", "ù"],
      "ü": ["ǖ", "ǘ", "ǚ", "ǜ"]
    };

    const vowelPriority = ["a", "o", "e", "i", "u", "ü"];
    let index = -1;

    for (const v of vowelPriority) {
      if ((index = pinyin.indexOf(v)) !== -1) break;
    }

    if (index === -1) return pinyin;
    const char = pinyin[index];
    return pinyin.slice(0, index) + map[char][tone - 1] + pinyin.slice(index + 1);
  }

  // Tampilkan setiap pinyin
  pinyins.forEach(({ text, audio }) => {
    const div = document.createElement("div");
    div.className = "pinyin-item";
    div.textContent = text;

    const menu = document.createElement("div");
    menu.className = "tone-menu";
    menu.style.display = "none";
    menu.style.position = "absolute";
    menu.style.background = "#fff";
    menu.style.border = "1px solid #ccc";
    menu.style.padding = "5px";
    menu.style.zIndex = "1000";

    tones.forEach(tone => {
      const btn = document.createElement("button");
      const display = applyTone(text, tone);
      const fileName = audio + tone;
      const path = `/pinyin-chart/mp3-chinese-pinyin-sound/mp3/${fileName}.mp3`;

      btn.textContent = `🔊 ${display}`;
      btn.onclick = (e) => {
        e.stopPropagation();
        new Audio(path).play().catch(() => alert(`Audio tidak ditemukan:\n${path}`));
      };
      menu.appendChild(btn);
    });

    div.appendChild(menu);
    div.onclick = (e) => {
      e.stopPropagation();
      document.querySelectorAll(".tone-menu").forEach(m => m.style.display = "none");
      menu.style.display = "block";
    };

    container.appendChild(div);
  });

  // Klik luar → tutup semua menu
  document.addEventListener("click", () => {
    document.querySelectorAll(".tone-menu").forEach(m => m.style.display = "none");
  });
</script>
@endsection

let data = [];
let current = 0;

// Fungsi shuffle array (acak urutan elemen)
function shuffle(array) {
  return array.sort(() => Math.random() - 0.5);
}

function loadData() {
  fetch(`/HSK-Flashcards/public/${LEVEL}.json`)
    .then(res => res.json())
    .then(json => {
      data = shuffle(json);  // <== Acak urutan data di sini
      current = 0;
      showCard();
    })
    .catch(err => {
      console.error("Gagal memuat data:", err);
      alert("Gagal memuat data flashcard.");
    });
}

function showCard() {
  const card = document.getElementById('card');
  const front = card.querySelector('.card-front');
  const back = card.querySelector('.card-back');
  const item = data[current];

  if (!item) {
    front.innerHTML = `<div class="text-danger">No data</div>`;
    back.innerHTML = `<div class="text-danger">No data</div>`;
    return;
  }

  front.innerHTML = `<div style="font-weight: bold; font-size: 3.5rem;">${item.other}</div>`;

  back.innerHTML = `
    <div style="text-align: center;">
      <div style="font-weight: bold; font-size: 3rem; margin-bottom: 0.5rem;">${item.other}</div>
      <div class="pinyin">${item.pinyin}</div>
      <div class="meaning">${item.english}</div>
      <div class="indonesian">${item.indonesia}</div>
    </div>
  `;

  card.classList.remove('flipped');
}

function flipCard() {
  const card = document.getElementById('card');
  card.classList.toggle('flipped');
}

function nextWord() {
  current = (current + 1) % data.length;
  const card = document.getElementById('card');
  card.classList.remove('flipped');
  setTimeout(showCard, 100);
}

function prevWord() {
  current = (current - 1 + data.length) % data.length;
  const card = document.getElementById('card');
  card.classList.remove('flipped');
  setTimeout(showCard, 100);
}

document.addEventListener('DOMContentLoaded', () => {
  const card = document.getElementById('card');
  card.addEventListener('click', flipCard);

  document.addEventListener('keydown', e => {
    if (e.code === 'ArrowRight') nextWord();
    else if (e.code === 'ArrowLeft') prevWord();
    else if (e.code === 'Space') {
      e.preventDefault();
      flipCard();
    }
  });

  loadData();
});

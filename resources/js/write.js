// resources/js/write.js

window.addEventListener("DOMContentLoaded", () => {
  const characterDiv = document.getElementById("writer");
  const inputChar = document.getElementById("inputChar");
  const updateBtn = document.getElementById("updateChar");
  const animateBtn = document.getElementById("animate");
  const quizBtn = document.getElementById("quiz");
  const resetBtn = document.getElementById("reset");
  const showOutlineCheckbox = document.getElementById("showOutline");

  let currentChar = '你';
  inputChar.value = currentChar;

  let writer = createWriter(currentChar);

  function createWriter(char) {
    characterDiv.innerHTML = '';
    const newWriter = HanziWriter.create(characterDiv, char, {
      width: 300,
      height: 300,
      padding: 20,
      showOutline: showOutlineCheckbox.checked,
      strokeAnimationSpeed: 1.5,
      delayBetweenStrokes: 200
    });

    setTimeout(() => {
      toggleOutlineVisibility(showOutlineCheckbox.checked);
    }, 300);

    return newWriter;
  }

  function toggleOutlineVisibility(show) {
    const svg = characterDiv.querySelector('svg');
    if (!svg) return;
    const outlines = svg.querySelectorAll('g.strokes path');
    outlines.forEach(path => {
      path.style.opacity = show ? '1' : '0';
    });
  }

  showOutlineCheckbox.addEventListener('change', (e) => {
    toggleOutlineVisibility(e.target.checked);
  });

  updateBtn.addEventListener('click', () => {
    const char = inputChar.value.trim();
    if (char.length === 1) {
      currentChar = char;
      writer = createWriter(currentChar);
    } else {
      alert("Masukkan satu karakter Tionghoa saja!");
    }
  });

  animateBtn.addEventListener('click', () => {
    writer.animateCharacter();
  });

  quizBtn.addEventListener('click', () => {
    toggleOutlineVisibility(showOutlineCheckbox.checked);
    writer.quiz();
  });

  resetBtn.addEventListener('click', () => {
    writer.hideCharacter();
  });
});

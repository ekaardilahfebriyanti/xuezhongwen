/******/ (() => { // webpackBootstrap
/*!*******************************!*\
  !*** ./resources/js/write.js ***!
  \*******************************/
// resources/js/write.js

window.addEventListener("DOMContentLoaded", function () {
  var characterDiv = document.getElementById("writer");
  var inputChar = document.getElementById("inputChar");
  var updateBtn = document.getElementById("updateChar");
  var animateBtn = document.getElementById("animate");
  var quizBtn = document.getElementById("quiz");
  var resetBtn = document.getElementById("reset");
  var showOutlineCheckbox = document.getElementById("showOutline");
  var currentChar = '你';
  inputChar.value = currentChar;
  var writer = createWriter(currentChar);
  function createWriter(_char) {
    characterDiv.innerHTML = '';
    var newWriter = HanziWriter.create(characterDiv, _char, {
      width: 300,
      height: 300,
      padding: 20,
      showOutline: showOutlineCheckbox.checked,
      strokeAnimationSpeed: 1.5,
      delayBetweenStrokes: 200
    });
    setTimeout(function () {
      toggleOutlineVisibility(showOutlineCheckbox.checked);
    }, 300);
    return newWriter;
  }
  function toggleOutlineVisibility(show) {
    var svg = characterDiv.querySelector('svg');
    if (!svg) return;
    var outlines = svg.querySelectorAll('g.strokes path');
    outlines.forEach(function (path) {
      path.style.opacity = show ? '1' : '0';
    });
  }
  showOutlineCheckbox.addEventListener('change', function (e) {
    toggleOutlineVisibility(e.target.checked);
  });
  updateBtn.addEventListener('click', function () {
    var _char2 = inputChar.value.trim();
    if (_char2.length === 1) {
      currentChar = _char2;
      writer = createWriter(currentChar);
    } else {
      alert("Masukkan satu karakter Tionghoa saja!");
    }
  });
  animateBtn.addEventListener('click', function () {
    writer.animateCharacter();
  });
  quizBtn.addEventListener('click', function () {
    toggleOutlineVisibility(showOutlineCheckbox.checked);
    writer.quiz();
  });
  resetBtn.addEventListener('click', function () {
    writer.hideCharacter();
  });
});
/******/ })()
;
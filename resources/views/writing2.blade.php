@extends('layout.main')

@section('title', 'Kuis & Animasi Hanzi Writer Stroke')

@section('content')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/hanzi-writer@3.5/dist/hanzi-writer.min.js"></script>
  <div class="hanzi-quiz-app">
    <h2>Hanzi Writer Stroke Quiz & Animation</h2>

    <form id="character-form" class="js-char-form">
      <input type="text" id="character-select" maxlength="1" value="你" />
      <button type="submit">Ganti Karakter</button>
    </form>

    <div class="char-section">
      <div class="writer-box">
        <div class="title">Animasi Goresan</div>
        <div id="animation-target" class="writer"></div>
        <div class="controls">
          <label><input type="checkbox" id="animation-show-outline" checked> Show Outline</label>
          <button id="animate">Tampilkan Animasi</button>
        </div>
      </div>

      <div class="writer-box">
        <div class="title">Latihan Menulis</div>
        <div id="quiz-target" class="writer"></div>
        <div class="controls">
          <label><input type="checkbox" id="quiz-show-outline" checked> Show Outline</label>
          <button id="quiz-reset">Reset Latihan</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('js')

  <script>
    var animationWriter;
    var quizWriter;

    function shouldShowOutline(type) {
      return $('#' + type + '-show-outline').prop('checked');
    }

    function updateCharacter() {
      const character = $('#character-select').val().trim();
      console.log("Karakter dipilih:", character); // Debug

      if (character.length !== 1) {
        alert("Masukkan satu karakter Hanzi.");
        return;
      }

      $('#animation-target').html('');
      $('#quiz-target').html('');

      animationWriter = HanziWriter.create('animation-target', character, {
        width: 300,
        height: 300,
        showOutline: shouldShowOutline('animation'),
        showCharacter: false
      });

      quizWriter = HanziWriter.create('quiz-target', character, {
        width: 300,
        height: 300,
        showOutline: shouldShowOutline('quiz'),
        showCharacter: false,
        showHintAfterMisses: 1
      });

      quizWriter.quiz();
    }

    $(function () {
      updateCharacter();

      $('.js-char-form').on('submit', function (e) {
        e.preventDefault();
        updateCharacter();
      });

      $('#animate').on('click', function (e) {
        e.preventDefault();
        animationWriter.animateCharacter();
      });

      $('#quiz-reset').on('click', function (e) {
        e.preventDefault();
        quizWriter.quiz();
      });

      $('#animation-show-outline').on('change', function () {
        const method = shouldShowOutline('animation') ? 'showOutline' : 'hideOutline';
        animationWriter[method]();
      });

      $('#quiz-show-outline').on('change', function () {
        const method = shouldShowOutline('quiz') ? 'showOutline' : 'hideOutline';
        quizWriter[method]();
      });
    });
  </script>
@endsection

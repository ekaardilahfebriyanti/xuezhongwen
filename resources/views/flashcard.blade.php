@extends('layout.main')

@section('title', 'Flashcard')

@section('content')
  <div class="flashcard-app">
    <h1>HSK Flashcard Test</h1>

    <div class="card-wrapper">
      <div id="card" class="flashcard">
        <div class="card-front"></div>
        <div class="card-back"></div>
      </div>
    </div>

    <div class="d-flex justify-content-center gap-3 mb-3">
      <button onclick="prevWord()" class="btn btn-outline-primary">Previous Word</button>
      <button onclick="nextWord()" class="btn btn-outline-primary">Next Word</button>
    </div>

    <a href="{{ url('/flashcard') }}" class="btn btn-secondary mb-3">Back to Home</a>
  </div>
@endsection

@section('js')
  <script>
    const LEVEL = "{{ $level }}";
  </script>
  <script src="{{ asset('flashcard.js') }}"></script>
@endsection

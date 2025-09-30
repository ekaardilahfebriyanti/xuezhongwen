@extends('layout.main')

@section('title', 'Pilih Level HSK')

@section('content')
  <div class="hsk-level-app">
    <div class="container text-center py-5">
      <h1>Select HSK Level</h1>
      <div class="row justify-content-center">
        @for ($i = 1; $i <= 6; $i++)
          <div class="col-6 col-sm-4 col-md-2 mb-3">
            <a href="{{ url('/flashcard/hsk' . $i) }}" class="btn btn-level w-100" role="button" tabindex="0">
              HSK{{ $i }}
            </a>
          </div>
        @endfor
      </div>
    </div>
  </div>
@endsection
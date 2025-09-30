<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/pinyin-interactive-chart', function () {
    return view('pinyin_chart');
});
Route::get('/write-pinyin', function () {
    return view('writing2');
});
Route::get('/listening', function () {
    return view('listening');
});
Route::get('/podcast', function () {
    return view('podcast');
});
Route::get('/sentence', function () {
    return view('sentence');
});
Route::get('/book', function () {
    return view('book');
});
Route::get('/modul', function () {
    return view('modul');
});
Route::get('/speaking', function () {
    return view('speaking');
});
Route::get('/quiz', function () {
    return view('quiz');
});
Route::get('/flashcard', function () {
    return view('home');
});
// Halaman flashcard berdasarkan level
Route::get('/flashcard/{level}', function ($level) {
    return view('flashcard', ['level' => $level]);
});
Route::get('/generate-valid-pinyin', function () {
    try {
        $path = public_path('pinyin-chart/mp3-chinese-pinyin-sound/mp3');

        // Check if the directory exists
        if (!is_dir($path)) {
            throw new \Exception("The specified directory does not exist.");
        }

        $files = scandir($path);

        $valid = [];
        foreach ($files as $file) {
            if (preg_match('/^([a-züv]+)[1-4]\.mp3$/u', $file, $m)) {
                $valid[] = $m[1];
            }
        }

        $valid = array_values(array_unique($valid));
        sort($valid);

        return response()->json($valid);
    } catch (\Exception $e) {
        // Log the error or send it to a monitoring service
        \Log::error($e->getMessage());

        // Return an appropriate error response
        return response()->json(['error' => 'An error occurred while processing the request.'], 500);
    }
});
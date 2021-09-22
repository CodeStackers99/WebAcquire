<?php

use App\Http\Controllers\AnswersController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\QuestionAndAnswersController;
use App\Http\Controllers\QuestionsController;
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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

//qna
Route::get('qna', [QuestionAndAnswersController::class, 'index'])->name('qna');

//qna Questions
Route::resource('qna/questions', QuestionsController::class)->except(['create', 'show']);

//qna Answers
Route::resource('qna/questions.answers', AnswersController::class);

//Questions Slug
Route::get('qna/questions/{slug}', [QuestionsController::class, 'show'])->name('questions.show');

//Mark Best/unbest/Vote
Route::put('answers/{answer}/mark-best-answer', [AnswersController::class, 'markbestAnswer'])->name('answers.markBestAnswer');
Route::put('answers/{answer}/unmark-best-answer', [AnswersController::class, 'unmarkBestAnswer'])->name('answers.unmarkBestAnswer');

//Courses
Route::get('courses', [CoursesController::class, 'index'])->name('courses.index');

//User Verify Email Token
Route::get('users/verify/{token}', [RegisterController::class, 'verify'])->name('users.verify');

<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\AutocompleteController;
use App\Http\Controllers\Admin\Gate\RoleController;
use App\Http\Controllers\Gate\PermissionController;

use App\Http\Controllers\PdfController;
use App\Http\Controllers\SettingController;

use App\Http\Controllers\Academic\InstituteController;
use App\Http\Controllers\Academic\ClassesController;
use App\Http\Controllers\Academic\SubjectController;
use App\Http\Controllers\Academic\ExamController;
use App\Http\Controllers\Academic\SubjectMappingController;
use App\Http\Controllers\Academic\StudentController;
use App\Http\Controllers\Academic\StudentPromotionController;
use App\Http\Controllers\Academic\Result\IndexController;
use App\Http\Controllers\Academic\Result\ResultController;

use App\Http\Controllers\Pdf\MarksheetController;
use App\Http\Controllers\Pdf\ResultsheetController;
use App\Http\Controllers\Pdf\DownloadController;
use App\Http\Controllers\Pdf\AdmitcardController;


use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});



Route::prefix('settings')->name('setting.')->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function(){
  Route::get('/', [SettingController::class, 'index'])->name('index');
  Route::post('/update-institute', [SettingController::class, 'update_institute'])->name('institute');
  Route::post('/update-logo', [SettingController::class, 'update_logo'])->name('logo');
  Route::post('/update-pass-mark', [SettingController::class, 'update_pass_mark'])->name('pass_mark');
  Route::post('/update-admit-template', [SettingController::class, 'update_admit_template'])->name('admit');
  Route::get('/test', [SettingController::class, 'getPdfFiles'])->name('hi');
});


Route::get('downloads/marksheet', [MarksheetController::class, 'index'])->name('marksheet');
Route::get('downloads/marksheets', [MarksheetController::class, 'print_all_marksheet'])->name('marksheets');
Route::get('downloads/resultsheet', [ResultsheetController::class, 'index'])->name('resultsheet');
Route::get('downloads/result-form', [DownloadController::class, 'result_entry_sheet'])->name('pdf.result.form');
Route::get('downloads/exam/plan/{exam_id}', [DownloadController::class, 'new_exam_plan'])->name('pdf.exam.form');
Route::get('downloads/admit', [AdmitcardController::class, 'index'])->name('pdf.admit');
Route::get('downloads/attendance_sheet', [AdmitcardController::class, 'attendance_sheet'])->name('pdf.attendance_sheet');


Route::get('downloads', function(){
  $exams = \DB::table('exams')->select('name', 'id')->get();
  $classes = \DB::table('classes')->select('name', 'id')->get();
  return inertia('Academic/Downloads', compact('exams', 'classes'));
})->name('downloads');

Route::prefix('sheet')->name('sheet.')->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function(){
  Route::get('/', [ResultController::class, 'index'])->name('index');
  Route::get('/marksheet', [ResultController::class, 'marksheet'])->name('individual');
  Route::get('/marksheet/data', [ResultController::class, 'marksheet_data'])->name('individual.data');
  Route::get('/form', [ResultController::class, 'sheet_data'])->name('data');
  Route::get('/get/students', [ResultController::class, 'get_students'])->name('get.student');
  
});
Route::prefix('result')->name('result.')->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function(){
  Route::get('/', [IndexController::class, 'index'])->name('index');
  Route::get('/create', [IndexController::class, 'create'])->name('create');
  Route::post('/store', [IndexController::class, 'store'])->name('store');
  Route::post('/{id}/update', [IndexController::class, 'update'])->name('update');
  Route::delete('/{id}/delete', [IndexController::class, 'destroy'])->name('delete');
  Route::get('/get/form', [IndexController::class, 'get_students'])->name('get.student');
  Route::get('/get/subject', [IndexController::class, 'get_subjects'])->name('get.subject');
  Route::get('/get/result', [ResultController::class, 'get_student_list'])->name('list');
});

Route::prefix('classes')->name('classes.')->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function(){
  Route::get('/', [ClassesController::class, 'index'])->name('index');
  Route::get('/create', [ClassesController::class, 'create'])->name('create');
  Route::post('/store', [ClassesController::class, 'store'])->name('store');
  Route::get('/edit/{id}', [ClassesController::class, 'edit'])->name('edit');
  Route::post('/{id}/update', [ClassesController::class, 'update'])->name('update');
  Route::delete('/{id}/delete', [ClassesController::class, 'destroy'])->name('delete');
  Route::get('get/select', [ClassesController::class, 'get_classes'])->name('get.select');
});

Route::prefix('subject')->name('subject.')->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function(){
  Route::get('/', [SubjectController::class, 'index'])->name('index');
  Route::post('/store', [SubjectController::class, 'store'])->name('store');
  Route::post('/{id}/update', [SubjectController::class, 'update'])->name('update');
  Route::delete('/{id}/delete', [SubjectController::class, 'destroy'])->name('delete');
  
  Route::get('/select', [SubjectController::class, 'subject_get_select'])->name('select');
});


Route::prefix('exam')->name('exam.')->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function(){
  Route::get('/', [ExamController::class, 'index'])->name('index');
  Route::post('/store', [ExamController::class, 'store'])->name('store');
  Route::post('/{id}/update', [ExamController::class, 'update'])->name('update');
  Route::delete('/{id}/delete', [ExamController::class, 'destroy'])->name('delete');
  
  Route::get('/get/exams', [ExamController::class, 'get_exams'])->name('get.select');
  
  Route::prefix('map')->name('map.')->group(function(){
    Route::get('/', [SubjectMappingController::class, 'index'])->name('index');
    Route::get('/create', [SubjectMappingController::class, 'create'])->name('create');
    Route::post('/store', [SubjectMappingController::class, 'store'])->name('store');
    Route::post('/{id}/update', [SubjectMappingController::class, 'update'])->name('update');
    Route::delete('/{exam_id}/{class_id}/delete', [SubjectMappingController::class, 'destroy'])->name('delete');
    Route::get('/exams', [SubjectMappingController::class, 'get_exams'])->name('exam.get');
    Route::get('/subjects/{class_id}', [SubjectMappingController::class, 'get_subjects'])->name('subject.get');
    Route::get('/form/', [SubjectMappingController::class, 'prepareForm'])->name('form');
  });
});

Route::prefix('student')->name('student.')->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function(){
  Route::get('/', [StudentController::class, 'index'])->name('index');
  Route::get('/create', [StudentController::class, 'create'])->name('create');
  Route::post('/store', [StudentController::class, 'store'])->name('store');
  Route::post('/{id}/update', [StudentController::class, 'update'])->name('update');
  Route::delete('/{id}/delete', [StudentController::class, 'destroy'])->name('delete');
  Route::get('/roll/latest', [StudentController::class, 'get_roll'])->name('roll.get');
  
  Route::get('/get/students', [StudentController::class, 'get_students'])->name('get.select');
  
  Route::get('/promotion', [StudentPromotionController::class, 'index'])->name('promotion');
  Route::get('/get/{class_id}', [StudentPromotionController::class, 'getStudents'])->name('get');
  Route::post('/promote', [StudentPromotionController::class, 'promote'])->name('promote');
});

Route::prefix('institute')->name('institute.')->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function(){
  Route::get('/', [InstituteController::class, 'index'])->name('index');
  Route::post('/store', [InstituteController::class, 'store'])->name('store');
  Route::post('/{id}/update', [InstituteController::class, 'update'])->name('update');
  Route::delete('/{id}/delete', [InstituteController::class, 'destroy'])->name('delete');
});






Route::name('admin.')->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard.index');
    Route::get('table', [AdminDashboardController::class, 'table'])->name('dashboard.table');
  
    Route::prefix('gate')->name('gate.')->group(function (){
      Route::prefix('role')->name('role.')->group(function(){
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/create', [RoleController::class, 'create'])->name('create');
        Route::post('/store', [RoleController::class, 'store'])->name('store');
        Route::delete('/{id}/delete', [RoleController::class, 'destroy'])->name('delete');
        Route::post('/{id}/delete', [RoleController::class, 'update'])->name('edit');
      });
      
      Route::prefix('permission')->name('permission.')->group(function() {
        Route::get('/', [PermissionController::class, 'index'])->name('index');
        Route::get('/{id}/form', [PermissionController::class, 'permissionForm'])->name('form');
        Route::post('/{id}/update', [PermissionController::class, 'updateRolePermission'])->name('update');
      });
    });
});

Route::prefix('user')->name('user.')->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function(){
  Route::get('/', [UserController::class, 'index'])->name('index');
  Route::get('/create', [UserController::class, 'create'])->name('create');
  Route::post('/store', [UserController::class, 'store'])->name('store');
  Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
  Route::post('/{id}/update', [UserController::class, 'update'])->name('update');
  Route::delete('/{user}/delete', [UserController::class, 'delete'])->name('delete');
});

Route::get('user-list', [AdminDashboardController::class, 'getUser'])->name('select.user');
Route::get('autocomplete', [AutocompleteController::class, 'autocomplete'])->name('autocomplete');
Route::get('autocomplete/customers', [AutocompleteController::class, 'customers'])->name('autocomplete.customers');


Route::prefix('gate')->name('gate.')->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function(){
  Route::prefix('permission')->name('permission.')->group(function(){
    Route::get('/', [PermissionController::class, 'index'])->name('index');
    Route::post('/store', [PermissionController::class, 'store'])->name('store');
    Route::delete('/{id}/delete', [PermissionController::class, 'destroy'])->name('delete');
    Route::post('/{id}/update', [PermissionController::class, 'update'])->name('update');
  });
});

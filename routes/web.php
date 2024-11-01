<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KalenderAkademikController;
use App\Http\Controllers\JadwalMapelController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MapelController;

// Group routes for LoginController using Route::controller
Route::controller(LoginController::class)->group(function () {
    Route::get('login', 'showLoginForm')->name('login');
    Route::post('login', 'login');
});

// Group routes that require 'auth' middleware
Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    //Ini untuk nge-protect routes biar khusus cuma diakses sama Admin
    Route::middleware('role:Admin')->group(function () {

        // Account CRUD routes
        Route::prefix('accounts')->controller(AccountController::class)->group(function () {
            Route::get('/', 'index')->name('account.index');
            Route::get('/{id}/edit', 'edit')->name('account.edit');
            Route::post('/{id}/delete', 'destroy')->name('account.destroy');
            Route::put('/{id}', 'update')->name('account.update');
        });

        // Siswa data routes
        Route::prefix('siswas')->controller(SiswaController::class)->group(function () {
            Route::get('/', 'index')->name('siswa.index');
            Route::post('/import', 'import')->name('siswa.import');
            Route::get('/import', 'showImportForm')->name('siswa.showImportForm');
            Route::post('/generate-multiple-users', 'generateMultipleUsers')->name('siswa.generateMultipleUsers');
            Route::post('/{id}/generate-user', 'generateUser')->name('siswa.generateUser');
            Route::get('/{siswaId}/edit', 'edit')->name('siswa.edit');
            Route::put('/{siswaId}', 'update')->name('siswa.update');
            Route::delete('/{siswaId}', 'delete')->name('siswa.delete');

        });

        // Guru data routes
        Route::prefix('guru')->controller(GuruController::class)->group(function() {
            Route::get('/', 'index')->name('guru.index');                         
            Route::post('/import', 'import')->name('guru.import');                
            Route::post('/create', 'create')->name('guru.create');               
            Route::get('/{id}/edit', 'edit')->name('guru.edit');                  
            Route::put('/{id}/update', 'update')->name('guru.update');           
            Route::delete('/{id}', 'destroy')->name('guru.destroy');              
            Route::post('/{guruId}/generate-user', 'generateUser')->name('guru.generateUser'); 
        });

        Route::prefix('kelas')->controller(KelasController::class)->group(function () {
            Route::get('/', 'index')->name('kelas.index');
            Route::get('/create', 'create')->name('kelas.create');
            Route::post('/store', 'store')->name('kelas.store');
            Route::post('/{kelasId}/update', 'update')->name('kelas.update');
            Route::post('/{kelasId}/add-student', 'addStudentToClass')->name('kelas.addStudent');
            Route::get('/{kelasId}/buka', 'bukaKelas')->name('kelas.buka');
            Route::post('/{kelasId}/hapus', 'hapusKelas')->name('kelas.hapus');
            Route::post('/{kelasId}/auto-assign', 'autoAddStudents')->name('kelas.autoAdd');
        });

        //Mapel routes
        Route::prefix('mapel')->controller(MapelController::class)->group(function () {
            Route::get('/', 'index')->name('mapel.index');
            Route::post('/store', 'store')->name('mapel.store');
            Route::delete('/{mapelId}/delete', 'hapusMapel')->name('mapel.delete');
            Route::post('/{mapelId}/assign-kelas', 'assignKelasToMapel')->name('mapel.assign-kelas');
            Route::get('/{mapelId}/show-assign-kelas-modal', 'showAssignKelasModal')->name('mapel.show-assign-kelas-modal');
        });

        // Jadwal Mapel routes
        Route::controller(JadwalMapelController::class)->group(function() {
            Route::get('/jadwalmapel', 'index')->name('jadwalmapel.index');
        });

        // Kalender Akademik routes
        Route::prefix('kalender-akademik')->controller(KalenderAkademikController::class)->group(function() {
            Route::get('/', 'index')->name('kalenderakademik.index');              // Display the main calendar view
            Route::get('/list', 'listEvent')->name('kalenderakademik.list');       // Fetch list of events for a specified period
            Route::post('/ajax', 'ajax')->name('kalenderakademik.ajax');           // Handle create, update, delete via AJAX
        });

        // Semester routes
        Route::prefix('semesters')->controller(SemesterController::class)->group(function () {
            Route::get('/', 'index')->name('semesters.index');
            Route::post('/', 'store')->name('semesters.store');
            Route::put('/{id}', 'update')->name('semesters.update');
            Route::delete('/{id}', 'destroy')->name('semesters.destroy');
        });
    });
});

// Default route redirect to login page
Route::get('', function () {
    return view('auth.login');
});

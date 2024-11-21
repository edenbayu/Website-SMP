<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KalenderAkademikController;
use App\Http\Controllers\KalenderMapelController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SemesterSelectionController;
use App\Http\Controllers\SillabusController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\KomentarController;

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
            Route::post('/{id}/generate-user', 'generateUser')->name('siswa.generateUser');
            Route::put('/update/{siswaId}', 'update')->name('siswa.update');
            Route::delete('/delete/{siswaId}', 'delete')->name('siswa.delete');

        });

        // Guru data routes
        Route::prefix('guru')->controller(GuruController::class)->group(function() {
            Route::get('/', 'index')->name('guru.index');                         
            Route::post('/import', 'import')->name('guru.import');                
            Route::post('/create', 'create')->name('guru.create');                              
            Route::put('/{id}/update', 'update')->name('guru.update');           
            Route::delete('/{id}', 'destroy')->name('guru.destroy');              
            Route::post('/{guruId}/generate-user', 'generateUser')->name('guru.generateUser'); 
        });

        // Admin data routes
        Route::prefix('staffs')->controller(AdminController::class)->group(function() {
            Route::get('/', 'index')->name('admin.index');                         
            Route::post('/import', 'import')->name('admin.import');                
            Route::post('/create', 'create')->name('admin.create');                              
            Route::put('/{id}/update', 'update')->name('admin.update');           
            Route::delete('/{id}', 'destroy')->name('admin.destroy');              
            Route::post('/{guruId}/generate-user', 'generateUser')->name('admin.generateUser'); 
        });

        Route::prefix('kelas')->controller(KelasController::class)->group(function () {
            Route::get('/', 'index')->name('kelas.index');
            Route::get('/create', 'create')->name('kelas.create');
            Route::post('/store', 'store')->name('kelas.store');
            Route::post('/storeEkskul', 'storeEkskul')->name('kelas.storeEkskul');
            Route::post('/{kelasId}/update', 'update')->name('kelas.update');
            Route::post('/{kelasId}/add-student', 'addStudentToClass')->name('kelas.addStudent');
            Route::get('/{kelasId}/buka', 'bukaKelas')->name('kelas.buka');
            Route::post('/{kelasId}/hapus', 'hapusKelas')->name('kelas.hapus');
            Route::delete('/kelas/{kelasId}/siswa/{siswaId}', 'deleteAssignedSiswa')->name('kelas.siswa.delete');;
            Route::post('/{kelasId}/auto-assign', 'autoAddStudents')->name('kelas.autoAdd');
        });

        //Mapel routes
        Route::prefix('mapel')->controller(MapelController::class)->group(function () {
            Route::get('/', 'index')->name('mapel.index');
            Route::post('/store', 'store')->name('mapel.store');
            Route::delete('/{mapelId}/delete', 'hapusMapel')->name('mapel.delete');
            Route::post('/{mapelId}/assign-kelas', 'assignKelasToMapel')->name('mapel.assign-kelas');
        });

        // Kalender Akademik routes
        Route::prefix('kalender-akademik')->controller(KalenderAkademikController::class)->group(function() {
            Route::get('/', 'index')->name('kalenderakademik.index');              
            Route::get('/list', 'listEvent')->name('kalenderakademik.list');       
            Route::post('/ajax', 'ajax')->name('kalenderakademik.ajax');
        });

        Route::prefix('kalender-mapel')->controller(KalenderMapelController::class)->group(function() {
            Route::get('/', 'index')->name('kalendermapel.index');
            Route::post('/get-kelas-by-mapel', 'getKelasByMapel')->name('kalendermapel.ajax');
        });

        // Semester routes
        Route::prefix('semesters')->controller(SemesterController::class)->group(function () {
            Route::get('/', 'index')->name('semesters.index');
            Route::post('/', 'store')->name('semesters.store');
            Route::put('/{id}', 'update')->name('semesters.update');
            Route::delete('/{id}', 'destroy')->name('semesters.destroy');
        });
    });

    //disini kita protect Routesnya Guru yak!

    Route::middleware('role:Guru')->group(function () {

        Route::prefix('CP')->controller(SillabusController::class)->group(function () {
            Route::get('/{mapelId}', 'index')->name('silabus.index');
            Route::post('/{mapelId}/store', 'storeCP')->name('silabus.storeCP');
            Route::post('/{mapelId}/update/{cpId}', 'updateCP')->name('silabus.updateCP');
            Route::delete('/{mapelId}/delete/{cpId}', 'deleteCP')->name('silabus.deleteCP');
        });

        Route::prefix('TP')->controller(SillabusController::class)->group(function () {
            Route::get('/{mapelId}/cp/{cpId}', 'bukaTP')->name('bukaTP');
            Route::post('/{mapelId}/cp/{cpId}/store','storeTP')->name('silabus.storeTP');
            Route::post('/{mapelId}/cp/{cpId}/{tpId}/update', 'updateTP')->name('silabus.updateTP');
            Route::delete('/{mapelId}/cp/{cpId}/{tpId}/delete', 'deleteTP')->name('silabus.deleteTP');
        });

        Route::prefix('Penilaian/{kelasId}/{mapelId}')->controller(PenilaianController::class)->group(function () {
            Route::get('/', 'index')->name('penilaian.index');
            Route::post('/store', 'storePenilaian')->name('penilaian.store');
            Route::put('/{penilaianId}/update', 'updatePenilaian')->name('penilaian.update');
            Route::delete('/{penilaianId}/delete', 'deletePenilaian')->name('penilaian.delete');
            Route::get('/buka/{penilaianId}','bukaPenilaian')->name('penilaian.buka');
            Route::put('/update', 'updatePenilaianSiswaBatch')->name('penilaian.updateBatch');
            Route::get('/buku-nilai', 'bukuNilai')->name('penilaian.bukuNilai');
        });

        Route::prefix('Penilaian/Ekskul/{kelasId}')->controller(PenilaianController::class)->group(function () {
            Route::get('/',  'penilaianEkskul')->name('penilaian.ekskul');
            Route::post('/update', 'updateAllPenilaianEkskul')->name('penilaian.ekskul.update.all');
        });

        Route::prefix('Komentar/{mapelId}')->controller(KomentarController::class)->group(function () {
            Route::get('/', 'index')->name('komentar.index');
            Route::post('/update', 'updateKomentar')->name('komentar.update');
        });

    });
});

// Default route redirect to login page
Route::get('', function () {
    return view('auth.login');
});

//Route unntuk reset password
Route::get('/forgot-password', function(){
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');


Route::post('/select-semester', [SemesterSelectionController::class, 'selectSemester'])->name('select.semester');
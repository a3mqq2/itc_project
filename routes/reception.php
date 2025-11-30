<?php

use App\Http\Controllers\Reception\ReceptionController;
use App\Http\Controllers\Reception\MedicalFileController;
use App\Http\Controllers\Reception\MedicalFileAttachmentController;
use App\Http\Controllers\Reception\AppointmentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Reception Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:receptionist|admin'])->prefix('reception')->name('reception.')->group(function () {
    Route::get('/home', [ReceptionController::class, 'home'])->name('home');
    Route::post('/search-file', [ReceptionController::class, 'searchFile'])->name('search-file');

    // Medical Files
    Route::resource('medical-files', MedicalFileController::class);
    Route::get('/medical-files/{medicalFile}/print', [MedicalFileController::class, 'print'])->name('medical-files.print');
    Route::get('/medical-files/{medicalFile}/pdf', [MedicalFileController::class, 'exportPdf'])->name('medical-files.pdf');

    // Excel Export/Import
    Route::get('/medical-files-export/excel', [MedicalFileController::class, 'exportExcel'])->name('medical-files.export-excel');
    Route::post('/medical-files-import/excel', [MedicalFileController::class, 'importExcel'])->name('medical-files.import-excel');
    Route::get('/medical-files-template/download', [MedicalFileController::class, 'downloadTemplate'])->name('medical-files.download-template');

    // Medical File Attachments
    Route::post('/medical-files/{medicalFile}/attachments', [MedicalFileAttachmentController::class, 'store'])->name('medical-files.attachments.store');
    Route::put('/medical-files/{medicalFile}/attachments/{attachment}', [MedicalFileAttachmentController::class, 'update'])->name('medical-files.attachments.update');
    Route::delete('/medical-files/{medicalFile}/attachments/{attachment}', [MedicalFileAttachmentController::class, 'destroy'])->name('medical-files.attachments.destroy');
    Route::get('/medical-files/{medicalFile}/attachments/{attachment}/download', [MedicalFileAttachmentController::class, 'download'])->name('medical-files.attachments.download');

    // Appointments
    Route::resource('appointments', AppointmentController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::post('/appointments/get-medical-file', [AppointmentController::class, 'getMedicalFile'])->name('appointments.get-medical-file');
    Route::get('/appointments-print/list', [AppointmentController::class, 'printList'])->name('appointments.print-list');
});

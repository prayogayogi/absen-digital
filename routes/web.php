<?php

use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QrCodeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $token = 'UNRcjxuZMgwyQs3mFBci';


    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.fonnte.com/send',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array(
            'target' => '6282281735896',
            'message' => 'Siswa dengan nama ' . 'piter' . ' sudah absen masuk pada Jam ' . now()->format('H:i') . ' WIB',
        ),
        CURLOPT_HTTPHEADER => array(
            "Authorization: $token"
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    echo $response;
});

Route::post('/scan-absen', [QrCodeController::class, 'scan'])->name('scan.absen');

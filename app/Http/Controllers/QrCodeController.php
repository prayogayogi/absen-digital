<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Http\Request;

class QrCodeController extends Controller
{
    public function scan(Request $request)
    {
        $nisn = $request->input('nisn');

        $student = Student::where('nisn', $nisn)->first();

        if (! $student) {
            return response()->json(['status' => 'error', 'message' => 'Siswa tidak ditemukan.'], 404);
        }

        $alreadyAttended = Attendance::whereDate('created_at', now()->toDateString())
            ->where('student_id', $student->id)
            ->exists();

        if ($alreadyAttended) {
            return response()->json(['status' => 'exists', 'message' => 'Siswa sudah absen hari ini.']);
        }

        Attendance::create([
            'student_id' => $student->id,
            'status' => 'hadir',
            'attendance_date' => now(),
        ]);

        $token = env('TOKEN_FONNTE');

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
                'target' => $student->parent_phone,
                'message' => 'Selamat Pagi Bapak/Ibu Siswa/Siswi dengan nama ' . $student->name . ' sudah absen masuk pada Jam ' . now()->format('H:i') . ' WIB',
            ),
            CURLOPT_HTTPHEADER => array(
                "Authorization: $token"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return response()->json([
            'status' => 'success',
            'message' => 'Absensi berhasil dicatat.',
            'fonnte_response' => json_decode($response, true),
        ]);
    }
}

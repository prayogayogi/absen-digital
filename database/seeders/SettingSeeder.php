<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'key'       => 'app_name',
            'value'     => env('APP_NAME') ?? '...',
            'name'      => 'Nama Aplikasi',
            'type'      => 'text',
            'category'  => 'information'
        ]);
        Setting::create([
            'key'       => 'app_logo',
            'value'     => 'storage/default.png',
            'value_file' => '["settings\/default.png"]',
            'name'      => 'Logo Aplikasi',
            'type'      => 'file',
            'ext'       => 'png',
            'category'  => 'information'
        ]);
        Setting::create([
            'key'       => 'app_favicon',
            'value'     => 'storage/default.png',
            'value_file'     => '["settings\/default.png"]',
            'name'      => 'Favicon Aplikasi',
            'type'      => 'file',
            'ext'       => 'png',
            'category'  => 'information'
        ]);
        Setting::create([
            'key'       => 'jam_masuk',
            'value'     => '07:00',
            'name'      => 'Jam Masuk',
            'type'      => 'text',
            'ext'       => 'png',
            'category'  => 'Jam Sekolah'
        ]);
        Setting::create([
            'key'       => 'jam_pulang',
            'value'     => '15:00',
            'name'      => 'Jam Pulang',
            'type'      => 'text',
            'ext'       => 'png',
            'category'  => 'Jam Sekolah'
        ]);
    }
}

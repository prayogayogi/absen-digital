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
            'name'      => 'Application Name',
            'type'      => 'text',
            'category'  => 'information'
        ]);
        Setting::create([
            'key'       => 'app_short_name',
            'value'     => env('APP_NAME') ?? '...',
            'name'      => 'Application Short Name',
            'type'      => 'text',
            'category'  => 'information'
        ]);
        Setting::create([
            'key'       => 'app_logo',
            'value'     => 'storage/default.png',
            'value_file' => '["settings\/default.png"]',
            'name'      => 'Application Logo',
            'type'      => 'file',
            'ext'       => 'png',
            'category'  => 'information'
        ]);
        Setting::create([
            'key'       => 'app_favicon',
            'value'     => 'storage/default.png',
            'value_file'     => '["settings\/default.png"]',
            'name'      => 'Application Favicon',
            'type'      => 'file',
            'ext'       => 'png',
            'category'  => 'information'
        ]);
    }
}

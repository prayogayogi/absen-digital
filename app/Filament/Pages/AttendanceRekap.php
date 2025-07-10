<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Attendance;

class AttendanceRekap extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Attendance';

    protected static string $view = 'filament.pages.attendance-rekap';

    protected function getViewData(): array
    {
        return [
            'attendances' => Attendance::all(),
        ];
    }
}

<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.dashboard';

    protected function getViewData(): array
    {
        return [
            'siswaCount' => 10,
            'classCount' => 5,
            'teacherCount' => 20,
        ];
    }
}

<?php

namespace App\Filament\Resources\Setting\SettingResource\Pages;

use Filament\Actions;
use App\Models\Setting;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Setting\SettingResource;

class ListSettings extends ListRecords
{
    protected static string $resource = SettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return SettingResource::getWidgets();
    }

    public function getTabs(): array
    {

        $categories = Setting::distinct()->pluck('category')->toArray();
        // $tabs = [
        //     'All' => Tab::make('All'),
        // ];
        foreach ($categories as $category) {
            $tabs[$category] = Tab::make()->query(fn($query) => $query->where('category', $category));
        }
        return $tabs;
    }
}

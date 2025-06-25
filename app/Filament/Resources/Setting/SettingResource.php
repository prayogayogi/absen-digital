<?php

namespace App\Filament\Resources\Setting;

use Filament\Forms;
use Filament\Tables;
use App\Models\Setting;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\Setting\SettingResource\Pages;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $pluralLabel = 'Manage Setting';

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $slug = 'setting';

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
            'publish'
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('key')
                                    ->label('Key: (app_name, app_logo, email)')
                                    ->unique(Setting::class, 'key', ignoreRecord: true)
                                    ->required(),

                                Forms\Components\TextInput::make('name')
                                    ->label('Name')
                                    ->required(),
                            ]),

                        Forms\Components\Select::make('type')
                            ->label('Type: (text, textarea, file)')
                            ->placeholder('Select an option')
                            ->searchable()
                            ->options([
                                'text' => 'Text',
                                'textarea' => 'Textarea',
                                'file' => 'File',
                            ])
                            ->live()
                            ->required(),

                        Forms\Components\TextInput::make('value')
                            ->label('Value')
                            ->visible(fn($get) => $get('type') === 'text')
                            ->required(fn($get) => $get('type') === 'text'),

                        Forms\Components\RichEditor::make('value')
                            ->label('Value')
                            ->columnSpan('full')
                            ->visible(fn($get) => $get('type') === 'textarea')
                            ->required(fn($get) => $get('type') === 'textarea'),

                        Forms\Components\FileUpload::make('value_file')
                            ->label('Upload File')
                            ->disk('settings')
                            ->multiple()
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->visible(fn($get) => $get('type') === 'file')
                            ->required(fn($get) => $get('type') === 'file')
                            ->preserveFilenames()
                            ->downloadable()
                            ->acceptedFileTypes(['image/*'])
                            ->maxSize(10240) // 10MB
                            ->afterStateUpdated(function ($state, $set) {}),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('category')
                                    ->label('Category')
                                    ->required(),

                                Forms\Components\TextInput::make('ext')
                                    ->label('Ext'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('category')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created Date')
                    ->date()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->groups([
                Tables\Grouping\Group::make('created_at')
                    ->label('Order Date')
                    ->date()
                    ->collapsible(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return null;
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources\Student;

use Filament\Forms;
use App\Models\User;
use Faker\Core\File;
use Filament\Tables;
use App\Models\Classe;
use App\Models\Student;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Student\StudentResource\Pages;
use App\Filament\Resources\Student\StudentResource\RelationManagers;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('nisn')
                                    ->label('Nomor Induk Siswa Nasional (NISN)')
                                    ->required()
                                    ->maxLength(15),
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Siswa')
                                    ->required(),
                                Forms\Components\TextInput::make('parent_name')
                                    ->label('Nama Orang Tua'),
                                Forms\Components\TextInput::make('parent_phone')
                                    ->label('Nomor HanPhone Orang Tua')
                                    ->tel()
                                    ->maxLength(15),
                            ]),
                        Forms\Components\Select::make('class_id')
                            ->label('Kelas')
                            ->searchable()
                            ->required()
                            ->live()
                            ->options(function () {
                                try {
                                    return Classe::orderBy('name')->pluck('name', 'id')->toArray();
                                } catch (\Exception $e) {
                                    return [];
                                }
                            })
                            ->loadingMessage('Memuat daftar kelas...')
                            ->noSearchResultsMessage('Kelas tidak ditemukan')
                            ->searchPrompt('Ketik nama kelas...')
                            ->suffixAction(
                                fn($state): ?Forms\Components\Actions\Action => $state
                                    ? Forms\Components\Actions\Action::make('deleteOption')
                                    ->icon('heroicon-m-x-mark')
                                    ->color('danger')
                                    ->tooltip('Hapus kelas ini')
                                    ->action(function (Forms\Get $get, Forms\Set $set) {
                                        $value = $get('class_id');

                                        try {
                                            Classe::where('id', $value)->delete();
                                            cache()->forget('class_id_options');
                                            $set('class_id', null);

                                            Notification::make()
                                                ->title('Berhasil')
                                                ->body('Kelas berhasil dihapus')
                                                ->success()
                                                ->send();
                                        } catch (\Exception $e) {
                                            Notification::make()
                                                ->title('Error')
                                                ->body('Gagal menghapus kelas')
                                                ->danger()
                                                ->send();
                                        }
                                    })
                                    ->requiresConfirmation()
                                    : null
                            )
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Kelas')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Select::make('user_id')
                                    ->required()
                                    ->label('Nama Pengajar')
                                    ->options(function () {
                                        return User::whereHas('roles', function ($query) {
                                            $query->where('name', 'guru');
                                        })
                                            ->orderBy('name')
                                            ->pluck('name', 'id')
                                            ->toArray();
                                    })
                                    ->searchable()
                                    ->loadingMessage('Memuat daftar pengajar...')
                                    ->noSearchResultsMessage('Pengajar tidak ditemukan')
                                    ->searchPrompt('Ketik nama pengajar...')
                            ])
                            ->createOptionUsing(function (array $data) {
                                try {
                                    $newClasse = Classe::create($data);
                                    cache()->forget('class_id_options');
                                    return $newClasse->id;

                                    Notification::make()
                                        ->title('Berhasil')
                                        ->body('Kelas Berhasil ditambahkan')
                                        ->success()
                                        ->send();
                                } catch (\Exception $e) {
                                    Notification::make()
                                        ->title('Error')
                                        ->body('Gagal menambahkan kelas')
                                        ->danger()
                                        ->send();

                                    return null;
                                }
                            })
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                if ($state) {
                                    $kelas = Classe::find($state);

                                    if ($kelas) {
                                        $set('class_name', $kelas->name);
                                    } else {
                                        $set('class_name', null);
                                    }

                                    if (!Classe::where('id', $state)->exists()) {
                                        $set('class_id', null);
                                        cache()->forget('class_id_options');
                                    }
                                }
                            })
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('class.name')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nisn')
                    ->label('NISN')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('parent_name')
                    ->label('Nama Wali')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('class_id')
                    ->label('Kelas')
                    ->searchable()
                    ->options(function () {
                        return Classe::orderBy('name')->pluck('name', 'id')->toArray();
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }
}

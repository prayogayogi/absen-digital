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
                                    ->placeholder('Masukkan NISN')
                                    ->numeric()
                                    ->unique(Student::class, 'nisn', ignorable: fn($record) => $record)
                                    ->required()
                                    ->maxLength(15),
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Siswa')
                                    ->placeholder('Masukkan nama siswa')
                                    ->required(),
                                Forms\Components\Select::make('gender')
                                    ->label('Jenis Kelamin')
                                    ->placeholder('Pilih jenis kelamin')
                                    ->options([
                                        'L' => 'Laki-laki',
                                        'P' => 'Perempuan',
                                    ]),
                                Forms\Components\TextInput::make('birth_place')
                                    ->label('Tempat Lahir')
                                    ->placeholder('Masukkan tempat lahir')
                                    ->maxLength(100),
                                Forms\Components\DatePicker::make('birth_date')
                                    ->label('Tanggal Lahir')
                                    ->placeholder('Pilih tanggal lahir')
                                    ->date()
                                    ->maxDate(now())
                                    ->displayFormat('d F Y')
                                    ->placeholder('Pilih tanggal lahir'),
                                Forms\Components\TextInput::make('address')
                                    ->label('Alamat')
                                    ->placeholder('Masukkan alamat siswa')
                                    ->helperText('Alamat lengkap siswa')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('phone')
                                    ->label('Nomor Handphone')
                                    ->placeholder('Masukkan nomor handphone siswa')
                                    ->helperText('Nomor handphone siswa untuk komunikasi')
                                    ->tel()
                                    ->maxLength(15),
                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->placeholder('Masukkan email siswa')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\FileUpload::make('photo')
                                    ->label('Foto Siswa')
                                    ->placeholder('Unggah foto siswa')
                                    ->image()
                                    ->disk('public')
                                    ->directory('students')
                                    ->visibility('public')
                                    ->maxSize(1024) // 1 MB
                                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                                    ->columnSpanFull()
                                    ->helperText('Format: JPG, PNG. Maksimal ukuran: 1 MB'),
                                Forms\Components\TextInput::make('parent_name')
                                    ->label('Nama Wali')
                                    ->placeholder('Masukkan nama orang tua/wali siswa'),
                                Forms\Components\TextInput::make('parent_phone')
                                    ->label('Nomor handphone Orang Tua')
                                    ->placeholder('Masukkan nomor handphone orang tua/wali siswa')
                                    ->helperText('Nomor handphone orang tua/wali siswa untuk komunikasi')
                                    ->required()
                                    ->numeric()
                                    ->unique(Student::class, 'parent_phone', ignorable: fn($record) => $record)
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
                                    ->placeholder('Masukkan nama kelas')
                                    ->helperText('Contoh: Kelas 10A, Kelas 11B')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('academic_year')
                                    ->label('Tahun Ajaran')
                                    ->placeholder('Masukkan tahun ajaran')
                                    ->helperText('Contoh: 2023/2024')
                                    ->required()
                                    ->maxLength(10),

                                Forms\Components\Select::make('homeroom_teacher_id')
                                    ->label('Wali Kelas')
                                    ->placeholder('Pilih Wali Kelas')
                                    ->helperText('Pilih wali kelas untuk kelas ini')
                                    ->required()
                                    ->options(function () {
                                        $usedTeacherIds = Classe::pluck('homeroom_teacher_id')->filter()->toArray();
                                        return User::role('guru')
                                            ->whereNotIn('id', $usedTeacherIds)
                                            ->orderBy('name')
                                            ->pluck('name', 'id')
                                            ->toArray();
                                    })
                                    ->searchable()
                                    ->loadingMessage('Memuat daftar Wali Kelas...')
                                    ->noSearchResultsMessage('Wali Kelas tidak ditemukan')
                                    ->searchPrompt('Ketik nama Wali Kelas...'),
                            ])
                            ->createOptionUsing(function (array $data) {
                                try {
                                    $newClasse = Classe::create($data);
                                    cache()->forget('class_id_options');

                                    Notification::make()
                                        ->title('Berhasil')
                                        ->body('Kelas berhasil ditambahkan')
                                        ->success()
                                        ->send();

                                    return $newClasse->id;
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

                                    $set('class_name', $kelas?->name);

                                    if (!$kelas) {
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
                // SelectFilter::make('class_id')
                //     ->label('Kelas')
                //     ->searchable()
                //     ->options(function () {
                //         return Classe::orderBy('name')->pluck('name', 'id')->toArray();
                //     })
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

<?php

namespace App\Filament\Resources\User;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\User\UserResource\Pages;
use Filament\Infolists\Components\Section as InfolistSection;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationGroup = 'Manage Setting';

    protected static ?string $slug = 'users';

    protected static ?string $pluralLabel = 'User';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->placeholder('Enter user name')
                                    ->required(),
                                TextInput::make('email')
                                    ->placeholder('Enter user email')
                                    ->email()
                                    ->required(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('password')
                                    ->password(),
                                Forms\Components\DateTimePicker::make('email_verified_at')
                                    ->required()
                            ]),
                        Select::make('role')
                            ->relationship(
                                name: 'roles',
                                titleAttribute: 'name',
                                modifyQueryUsing: function (\Illuminate\Database\Eloquent\Builder $query) {
                                    $user =  Filament::auth()->user();

                                    if (!$user->hasRole('super_admin')) {
                                        $query->where('name', '!=', 'super_admin');
                                    }

                                    return $query;
                                }
                            )
                            ->required()
                            ->searchable()
                            ->preload()
                            ->optionsLimit(10)
                            ->getOptionLabelFromRecordUsing(fn($record) => $record->name),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(function () {
                $query = User::query()->with('roles');

                $currentUser = Filament::auth()->user();

                if ($currentUser && !$currentUser->hasRole('super_admin')) {
                    $query->whereDoesntHave('roles', function ($q) {
                        $q->where('name', 'super_admin');
                    });
                }

                return $query;
            })

            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->icon('heroicon-m-envelope')
                    ->grow(false)
                    ->sortable(),

                Tables\Columns\TextColumn::make('roles.name')
                    ->searchable()
                    ->badge()
                    ->grow(false),

                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('Set Role')
                    ->icon('heroicon-m-adjustments-vertical')
                    ->form([
                        Select::make('role')
                            ->relationship('roles', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->optionsLimit(10)
                            ->getOptionLabelFromRecordUsing(fn($record) => $record->name),
                    ])
                    ->visible(fn() => Filament::auth()->user()?->hasRole('super_admin')),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->orderByDesc('created_at');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                InfolistSection::make('User Information')->schema([
                    TextEntry::make('name')->label('Name'),
                    TextEntry::make('email')->label('Email'),
                ])->columns(2),
            ]);
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;

use Filament\Tables\Columns\BadgeColumn;

use Filament\Forms\Components\Toggle;
use Filament\Tables\Actions\BulkAction;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationLabel = 'Users';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function getTitle(): string
    {
        return 'Users'; // o el texto que desees
    }

    public static function getNavigationLabel(): string
    {
        return 'Users'; // Texto que aparecerá en el menú
    }

    public static function getModelLabel(): string
    {
        return 'Users'; // Texto singular que aparecerá en botones, formularios, etc.
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required(),

                // TextInput::make('password')
                // ->label('Password')
                // ->password()
                // ->required(fn ($livewire) => $livewire instanceof Pages\CreateUser)
                // ->dehydrateStateUsing(fn ($state) => !empty($state) ? bcrypt($state) : null)
                // ->hiddenOn('edit')
                // ->maxLength(255),

                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required(fn($livewire) => $livewire instanceof Pages\CreateUser)  // Solo requerido en la creación
                    ->dehydrateStateUsing(fn($state) => !empty($state) ? bcrypt($state) : null)  // Encriptar la contraseña solo si no está vacía
                    ->hiddenOn('edit')  // Esconde el campo en la página de edición
                    ->maxLength(255),

                // Agregar un campo de contraseña en el formulario de edición para que el usuario pueda cambiarla
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->nullable()
                    ->dehydrateStateUsing(fn($state) => !empty($state) ? bcrypt($state) : null)
                    ->dehydrated(fn($state) => filled($state)) // <- ESTA LÍNEA ES CLAVE
                    ->maxLength(255)
                    ->hiddenOn('create'),
                Select::make('role')
                    ->label('Role')
                    ->options([
                        'admin' => 'Administrador',
                        'cliente' => 'Client',
                        'chef' => 'Chef',
                    ])
                    ->required(),

                // DatePicker::make('happy')
                //     ->label('Fecha de Nacimiento'),

                // TextInput::make('telefono')
                //     ->label('Phone')
                //     ->tel()
                //     ->maxLength(20),

                Toggle::make('activo')
                    ->label('Active')
                    ->visible(fn($state, $record) => $record !== null) //
                    ->default(true),


                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Name')->sortable()->searchable(),
                TextColumn::make('email')->label('Email')->sortable()->searchable(),

                Tables\Columns\IconColumn::make('activo')
                    ->label('Active')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                BadgeColumn::make('role')
                    ->label('Role')
                    ->colors([
                        'admin' => 'danger',
                        'cliente' => 'success',
                    ]),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make('verPerfil')
                    ->label('Ver Perfil')
                    ->modalHeading('Perfil del Usuario')
                    ->modalWidth('lg')
                    ->modalContent(function ($record) {
                        $profile = $record->profile; // Relación desde el modelo User

                        return view('filament.resources.users.view-profile', [
                            'user' => $record,
                            'profile' => $profile,
                        ]);
                    }),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([

                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                BulkAction::make('export_excel')
                    ->label('Export to Excel')
                    ->requiresConfirmation()
                    ->action(function ($records) {
                        // Filtramos solo los campos deseados para cada usuario
                        $data = $records->map(fn($record) => [
                            $record->id,
                            $record->name,
                            $record->email,
                            $record->role,
                            $record->activo ? 'Yes' : 'No',
                        ]);

                        return \Maatwebsite\Excel\Facades\Excel::download(
                            new \App\Exports\UsersExport($data),
                            'selected_users.xlsx'
                        );
                    }),
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
}

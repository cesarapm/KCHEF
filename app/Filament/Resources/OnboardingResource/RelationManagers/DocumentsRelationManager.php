<?php

namespace App\Filament\Resources\OnboardingResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Actions\Action;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    protected static ?string $title = 'Uploaded Documents';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Upload Document')
                    ->description('Select the type and upload your file')
                    ->schema([
                        Forms\Components\TextInput::make('document_type')
                            ->label('What document is this?')
                            ->required()
                            ->placeholder('Write the document type')
                            ->helperText('Example: ID document, Medical certificate, etc.'),

                        Forms\Components\FileUpload::make('file_path')
                            ->label('Upload your file')
                            ->required()
                            ->disk('local')
                            ->directory('onboarding-documents')
                            ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                            ->maxSize(10240)
                            ->helperText('PDF, Word, JPG or PNG. Maximum 10MB'),

                        Forms\Components\Textarea::make('description')
                            ->label('Description (optional)')
                            ->placeholder('Add notes about this document...')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(1),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('file_name')
            ->columns([
                TextColumn::make('document_type')
                    ->label('Document Type')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('file_name')
                    ->label('File')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->limit(40),

                TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->placeholder('No description'),

                TextColumn::make('file_size')
                    ->label('Size')
                    ->formatStateUsing(function ($state) {
                        if (!$state) return '-';
                        $units = ['B', 'KB', 'MB'];
                        $bytes = $state;
                        for ($i = 0; $i < count($units) && $bytes >= 1024; $i++) {
                            $bytes /= 1024;
                        }
                        return round($bytes, 2) . ' ' . $units[$i];
                    }),

                TextColumn::make('uploaded_at')
                    ->label('Uploaded')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->since(),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(fn (array $data): array => $this->prepareDocumentData($data))
                    ->label('Add Document'),
            ])
            ->actions([
                Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->action(function ($record) {
                        $path = $record->file_path;
                        if ($path && Storage::disk('local')->exists($path)) {
                            return Storage::disk('local')->download($path, $record->file_name);
                        }
                    })
                    ->visible(fn ($record) => $record->file_path && Storage::disk('local')->exists($record->file_path)),

                Tables\Actions\DeleteAction::make(),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(fn (array $data): array => $this->prepareDocumentData($data))
                    ->label('Add Document'),
            ]);
    }

    private function prepareDocumentData(array $data): array
    {
        $path = $data['file_path'] ?? null;

        if ($path) {
            $data['file_name'] = basename($path);

            try {
                $data['file_size'] = Storage::disk('local')->size($path);
            } catch (\Throwable $e) {
                $data['file_size'] = null;
            }
        }

        $data['status'] = $data['status'] ?? 'pendiente';
        $data['uploaded_at'] = now();
        $data['user_id'] = auth()->id();
        $data['onboarding_id'] = $this->getOwnerRecord()->id;

        return $data;
    }
}

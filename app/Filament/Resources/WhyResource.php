<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WhyResource\Pages;
use App\Filament\Resources\WhyResource\RelationManagers;
use App\Models\Why;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Fieldset;


class WhyResource extends Resource
{
    protected static ?string $model = Why::class;
    protected static ?string $navigationLabel = 'Why'; // ubah nama di menu
    protected static ?string $pluralLabel = 'Why'; // judul di list
    protected static ?string $label = 'Why';
    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    public static function form(Form $form): Form
    {
        return $form
              ->schema([
                Fieldset::make('Details')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('info')
                            ->label('Informasi')
                            ->required(),
                        Forms\Components\FileUpload::make('photo')
                            ->label('Gambar')
                            ->image()
                            ->required()
                            ->disk('direct_storage') // Menggunakan disk direct_storage
                            ->directory('why') // Folder abouts di storage/abouts/
                            ->maxSize(1024),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->label('Gambar')
                    ->disk('direct_storage')
                    ->rounded(),
                Tables\Columns\TextColumn::make('title')->label('Judul')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y H:i')->sortable(),

            ])
            ->filters([
                 Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListWhies::route('/'),
            'create' => Pages\CreateWhy::route('/create'),
            'edit' => Pages\EditWhy::route('/{record}/edit'),
        ];
    }
     public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->orderByDesc('created_at');
    }
        public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('view_any_why');
    }
}

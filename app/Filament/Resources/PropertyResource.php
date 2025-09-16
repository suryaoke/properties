<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Propertys;
use App\Filament\Resources\PropertyResource\Pages;
use App\Filament\Resources\PropertyResource\RelationManagers;
use App\Models\Facility;
use App\Models\Property;
use Filament\Forms;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;
    protected static ?string $navigationLabel = 'Properti'; // ubah nama di menu
    protected static ?string $pluralLabel = 'Properti'; // judul di list
    protected static ?string $label = 'Properti';

    protected static ?string $cluster = Propertys::class;

    // protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    public static function form(Form $form): Form
    {
        return $form
           ->schema([
                Fieldset::make('Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('price')
                            ->label('Harga')
                            ->required()
                            ->numeric()
                            ->prefix('IDR'),
                        Forms\Components\Select::make('property_type_id')
                            ->label('Tipe Properti')
                            ->relationship('propertyType', 'name')
                            ->required(),
                        Forms\Components\Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->required(),
                        Forms\Components\Select::make('city_id')
                            ->label('Kota')
                            ->relationship('city', 'name')
                            ->required(),
                        Forms\Components\Textarea::make('address')
                            ->label('Alamat')
                            ->required(),
                        Forms\Components\Textarea::make('about')
                            ->label('Deskripsi')
                            ->required(),
                        Forms\Components\FileUpload::make('thumbnail')
                            ->label('Thumbnail')
                            ->required()
                            ->image()
                            ->directory('properties/thumbnails')
                            ->maxSize(1024),
                        Forms\Components\Repeater::make('photos')
                            ->label('Foto')
                            ->relationship('photos')
                            ->schema([
                                Forms\Components\FileUpload::make('photo')
                                    ->label('Foto')
                                    ->image()
                                    ->directory('properties/photos')
                                    ->maxSize(2048)
                                    ->required(),
                            ]),
                        Forms\Components\Repeater::make('facilities')
                            ->label('Fasilitas')
                            ->relationship('facilities')
                            ->schema([
                                Forms\Components\Select::make('facility_id')
                                    ->label('Fasilitas')
                                    ->options(Facility::all()->pluck('name', 'id'))
                                    ->searchable()
                                    ->required(),
                            ]),
                        Forms\Components\TextInput::make('map')
                            ->label('Peta (Map)')
                            ->placeholder('Masukkan embed code dari Google Maps')
                            ->helperText('Dapatkan kode embed dari Google Maps dengan memilih "Share" > "Embed a map" > Salin kode HTML yang disediakan.')
                            ->columnSpanFull(),



                    ])->columns(2),

                Fieldset::make('Additional')
                    ->schema([
                        Forms\Components\Select::make('certificate')
                            ->label('Sertifikat')
                            ->options([
                                'SHM' => 'SHM',
                                'HGB' => 'HGB',
                                'IMB' => 'IMB',
                                'Lainnya' => 'Lainnya',
                            ]),
                        Forms\Components\TextInput::make('bedrooms')
                            ->label('Kamar Tidur')
                            ->numeric()
                            ->prefix('Unit'),
                        Forms\Components\TextInput::make('bathrooms')
                            ->label('Kamar Mandi')
                            ->numeric()
                            ->prefix('Unit'),
                        Forms\Components\TextInput::make('electric')
                            ->label('Daya Listrik (Watt)')
                            ->numeric()
                            ->prefix('Watt'),
                        Forms\Components\TextInput::make('land_area')
                            ->label('Luas Tanah (m2)')
                            ->numeric()
                            ->prefix('m2'),
                        Forms\Components\TextInput::make('building_area')
                            ->label('Luas Bangunan (m2)')
                            ->numeric()
                            ->prefix('m2')
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')->label('Thumbnail')->rounded(),
                Tables\Columns\TextColumn::make('name')->label('Nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('price')->label('Harga')->money('idr', true)->sortable(),
                Tables\Columns\TextColumn::make('propertyType.name')->label('Tipe Properti')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('category.name')->label('Kategori')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('city.name')->label('Kota')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('certificate')->label('Sertifikat')->searchable()->sortable(),
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
             ->actionsColumnLabel('Aksi')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->can('view_any_property');
    }

}

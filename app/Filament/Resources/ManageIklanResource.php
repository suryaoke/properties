<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ManageIklanResource\Pages;
use App\Filament\Resources\ManageIklanResource\RelationManagers;
use App\Models\Facility;
use App\Models\Property;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManageIklanResource extends Resource
{
    protected static ?string $model = Property::class;
    protected static ?string $navigationLabel = 'Manage Iklan';
    protected static ?string $pluralLabel = 'Manage Iklan';
    protected static ?string $label = 'Manage Iklan';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('jenis', 'Iklan');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Details')
                    ->schema([
                        Forms\Components\TextInput::make('name_iklan')
                            ->label('Nama Pengiklan')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email_iklan')
                            ->label('Email Pengiklan')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone_iklan')
                            ->label('No. HP Pengiklan')
                            ->required()
                            ->maxLength(20),
                        Forms\Components\Select::make('status_iklan')
                            ->label('Status Iklan')
                            ->options([
                                'Active' => 'Active',
                                'Inactive' => 'Inactive',
                                'Rejected' => 'Rejected',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('jenis')
                            ->default('Iklan')   // gunakan default
                            ->hidden()
                            ->maxLength(20),
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
                        Forms\Components\Textarea::make('paragraph')
                            ->label('Paragraf')
                            ->nullable(),
                        Forms\Components\Select::make('status_listing')
                            ->label('Status Listing')
                            ->options([
                                'For Sale' => 'For Sale',
                                'For Rent' => 'For Rent',
                            ])
                            ->required(),
                        Forms\Components\Select::make('status_active')
                            ->label('Status Aktif')
                            ->options([
                                'Active' => 'Active',
                                'Inactive' => 'Inactive',
                            ])
                            ->required(),
                        Forms\Components\FileUpload::make('thumbnail')
                            ->label('Thumbnail')
                            ->required()
                            ->image()
                            ->disk('direct_storage')
                            ->directory('properties')
                            ->maxSize(1024),
                        Forms\Components\Repeater::make('photos')
                            ->label('Foto')
                            ->relationship('photos')
                            ->schema([
                                Forms\Components\FileUpload::make('photo')
                                    ->label('Foto')
                                    ->image()
                                    ->directory('properties')
                                    ->disk('direct_storage')
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
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Thumbnail')
                    ->disk('direct_storage')
                    ->rounded(),
                Tables\Columns\TextColumn::make('name_iklan')->label('Nama Pengiklan')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('phone_iklan')->label('No. HP Pengiklan')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email_iklan')->label('Email Pengiklan')->searchable()->sortable(),

                Tables\Columns\BadgeColumn::make('status_iklan')
                    ->label('Status Iklan')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                        'Rejected' => 'Rejected',
                        default => $state,
                    })
                    ->colors([
                        'success' => fn($state) => $state === 'Active',
                        'danger' => fn($state) => $state === 'Inactive',
                        'warning' => fn($state) => $state === 'Rejected',
                    ])
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('price')->label('Harga')->money('idr', true)->sortable(),
                Tables\Columns\TextColumn::make('propertyType.name')->label('Tipe Properti')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('category.name')->label('Kategori')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('city.name')->label('Kota')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('certificate')->label('Sertifikat')->searchable()->sortable(),
                Tables\Columns\BadgeColumn::make('status_active')
                    ->label('Status')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                        default => $state,
                    })
                    ->colors([
                        'success' => fn($state) => $state === 'Active',
                        'danger' => fn($state) => $state === 'Inactive',
                    ])
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status_listing')
                    ->label('Status Listing')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'For Sale' => 'For Sale',
                        'For Rent' => 'For Rent',
                        default => $state,
                    })
                    ->colors([
                        'success' => fn($state) => $state === 'For Sale',
                        'danger' => fn($state) => $state === 'For Ren',
                    ])
                    ->searchable()
                    ->sortable(),
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
            'index' => Pages\ListManageIklans::route('/'),
            'create' => Pages\CreateManageIklan::route('/create'),
            'edit' => Pages\EditManageIklan::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->can('view_any_manage::iklan');
    }
}

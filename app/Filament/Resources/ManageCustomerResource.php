<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ManageCustomerResource\Pages;
use App\Filament\Resources\ManageCustomerResource\RelationManagers;
use App\Models\ManageCustomer;
use App\Models\Property;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;

class ManageCustomerResource extends Resource
{
    protected static ?string $model = ManageCustomer::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Konsumen'; // ubah nama di menu
    protected static ?string $pluralLabel = 'Kelola Konsumen'; // judul di list
    protected static ?string $label = 'Kelola Konsumen';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label('Telepon')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('message')
                    ->label('Pesan')
                    ->required(),
                Forms\Components\Select::make('property_id')
                    ->label('Properti')
                    ->required()
                    ->relationship('properties', 'name'),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'new' => 'Baru',
                        'in_progress' => 'Dalam Proses',
                        'completed' => 'Selesai',
                    ])
                    ->required()
                    ->default('new'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->label('Email')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('phone')->label('Telepon')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('message')->label('Pesan')->limit(50)->wrap(),
                Tables\Columns\TextColumn::make('properties.name')->label('Properti')->searchable()->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'new' => 'Baru',
                        'in_progress' => 'Dalam Proses',
                        'completed' => 'Selesai',
                        default => $state, // fallback kalau ada status lain
                    })
                    ->colors([
                        'primary' => fn ($state) => $state === 'new',
                        'warning' => fn ($state) => $state === 'in_progress',
                        'success' => fn ($state) => $state === 'completed',
                    ])
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diubah')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->form([
                        Forms\Components\Section::make('Informasi Customer')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama')
                                    ->disabled(),
                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->disabled(),
                                Forms\Components\TextInput::make('phone')
                                    ->label('Telepon')
                                    ->disabled(),
                                Forms\Components\Textarea::make('message')
                                    ->label('Pesan')
                                    ->disabled(),
                                Forms\Components\TextInput::make('properties.name')
                                    ->label('Properti')
                                    ->disabled(),
                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'new' => 'Baru',
                                        'in_progress' => 'Dalam Proses',
                                        'completed' => 'Selesai',
                                    ])
                                    ->disabled(),
                                Forms\Components\TextInput::make('created_at')
                                    ->label('Dibuat')
                                    ->disabled(),
                                Forms\Components\TextInput::make('updated_at')
                                    ->label('Diubah')
                                    ->disabled(),
                            ])->columns(2),
                    ])
                    ->modalActions([
                        // Tombol default Close
                        Tables\Actions\Action::make('close')
                            ->label('Tutup')
                            ->color('secondary')
                            ->action(fn () => null),

                        // Tombol View Properti yang membuka modal baru
                        Tables\Actions\Action::make('view_property')
                            ->label('View Properti')
                            ->color('primary')
                            ->icon('heroicon-o-home-modern')
                            ->visible(fn ($record) => $record->properties !== null)
                            ->form(fn ($record) => self::getPropertyViewForm($record->properties))
                            ->modalHeading(fn ($record) => 'Detail Properti: ' . $record->properties->name)
                            ->modalWidth('7xl')
                            ->modalActions([
                                Tables\Actions\Action::make('close_property')
                                    ->label('Tutup')
                                    ->color('secondary'),
                            ]),
                    ])
                    ->modalWidth('5xl'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->actionsColumnLabel('Aksi')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPropertyViewForm($property): array
    {
        if (!$property) {
            return [
                Forms\Components\Placeholder::make('no_property')
                    ->label('Tidak ada properti yang terkait')
                    ->content('Customer ini belum memilih properti.')
            ];
        }

        return [
            Forms\Components\Section::make('Informasi Properti')
                ->schema([
                    Forms\Components\TextInput::make('property_thumbnail')
                        ->label('Thumbnail')
                        ->disabled()
                        ->default($property->thumbnail ?? 'Tidak ada thumbnail'),
                    Forms\Components\TextInput::make('property_name')
                        ->label('Nama Properti')
                        ->disabled()
                        ->default($property->name ?? 'Tidak ada nama'),
                    Forms\Components\TextInput::make('property_price')
                        ->label('Harga')
                        ->disabled()
                        ->prefix('IDR')
                        ->default($property->price ? number_format($property->price, 0, ',', '.') : 'Tidak ada harga'),
                    Forms\Components\TextInput::make('property_type')
                        ->label('Tipe Properti')
                        ->disabled()
                        ->default($property->propertyType?->name ?? 'Tidak ada tipe'),
                    Forms\Components\TextInput::make('property_category')
                        ->label('Kategori')
                        ->disabled()
                        ->default($property->category?->name ?? 'Tidak ada kategori'),
                    Forms\Components\TextInput::make('property_city')
                        ->label('Kota')
                        ->disabled()
                        ->default($property->city?->name ?? 'Tidak ada kota'),
                ])->columns(2),

            Forms\Components\Section::make('Detail Properti')
                ->schema([
                    Forms\Components\Textarea::make('property_address')
                        ->label('Alamat')
                        ->disabled()
                        ->default($property->address ?? 'Tidak ada alamat'),
                    Forms\Components\Textarea::make('property_about')
                        ->label('Deskripsi')
                        ->disabled()
                        ->default($property->about ?? 'Tidak ada deskripsi'),
                    Forms\Components\TextInput::make('property_certificate')
                        ->label('Sertifikat')
                        ->disabled()
                        ->default($property->certificate ?? 'Tidak ada sertifikat'),
                    Forms\Components\TextInput::make('property_bedrooms')
                        ->label('Kamar Tidur')
                        ->disabled()
                        ->suffix('Unit')
                        ->default($property->bedrooms ?? 0),
                    Forms\Components\TextInput::make('property_bathrooms')
                        ->label('Kamar Mandi')
                        ->disabled()
                        ->suffix('Unit')
                        ->default($property->bathrooms ?? 0),
                    Forms\Components\TextInput::make('property_land_area')
                        ->label('Luas Tanah')
                        ->disabled()
                        ->suffix('m²')
                        ->default($property->land_area ?? 0),
                    Forms\Components\TextInput::make('property_building_area')
                        ->label('Luas Bangunan')
                        ->disabled()
                        ->suffix('m²')
                        ->default($property->building_area ?? 0),
                    Forms\Components\TextInput::make('property_electric')
                        ->label('Daya Listrik')
                        ->disabled()
                        ->suffix('Watt')
                        ->default($property->electric ?? 0),
                ])->columns(2),
        ];
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
            'index' => Pages\ListManageCustomers::route('/'),
            'create' => Pages\CreateManageCustomer::route('/create'),
            'edit' => Pages\EditManageCustomer::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->can('view_any_manage::customer');
    }
}

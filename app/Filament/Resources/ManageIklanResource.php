<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ManageIklanResource\Pages;
use App\Filament\Resources\ManageIklanResource\RelationManagers;
use App\Models\Facility;
use App\Models\Property;
use App\Models\About;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;

class ManageIklanResource extends Resource
{
    protected static ?string $model = Property::class;
    protected static ?string $navigationLabel = 'Manage Iklan';
    protected static ?string $pluralLabel = 'Manage Iklan';
    protected static ?string $label = 'Manage Iklan';

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('jenis', 'Iklan')->whereNull('status_terjual')->orderByDesc('created_at');
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
                            ->helperText('Dapatkan kode embed dari Google Maps dengan memilih "Share" > "Embed a map" > Salin kode (Https://..) nya saja.')
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
                Tables\Actions\Action::make('publish')
                    ->label('Publish')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Publish Iklan')
                    ->modalDescription('Apakah Anda yakin ingin mempublikasikan iklan ini?')
                    ->modalSubmitActionLabel('Ya, Publish')
                    ->action(function (Property $record) {
                        $record->update([
                            'status_iklan' => 'Active',
                            'status_active' => 'Active',
                        ]);

                        Notification::make()
                            ->title('Iklan berhasil dipublikasikan')
                            ->success()
                            ->send();
                    })
                    ->visible(
                        fn(Property $record) =>
                        $record->status_iklan !== 'Active' || $record->status_active !== 'Active'
                    ),
                Tables\Actions\Action::make('unpublish')
                    ->label('Unpublish')
                    ->icon('heroicon-o-x-circle')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Unpublish Iklan')
                    ->modalDescription('Apakah Anda yakin ingin menonaktifkan iklan ini?')
                    ->modalSubmitActionLabel('Ya, Unpublish')
                    ->action(function (Property $record) {
                        $record->update([
                            'status_iklan' => 'Inactive',
                            'status_active' => 'Inactive',
                        ]);

                        Notification::make()
                            ->title('Iklan berhasil dinonaktifkan')
                            ->warning()
                            ->send();
                    })
                    ->visible(
                        fn(Property $record) =>
                        $record->status_iklan === 'Active' && $record->status_active === 'Active'
                    ),

                     Tables\Actions\Action::make('markAsSoldOrRented')
        ->label(fn(Property $record) =>
            $record->status_listing === 'For Sale' ? 'Terjual' :
            ($record->status_listing === 'For Rent' ? 'Tersewa' : 'Selesai')
        )
        ->icon('heroicon-o-check-circle')
        ->color(fn(Property $record) =>
            $record->status_listing === 'For Sale' ? 'success' :
            ($record->status_listing === 'For Rent' ? 'warning' : 'gray')
        )
        ->requiresConfirmation()
        ->modalHeading(fn(Property $record) =>
            $record->status_listing === 'For Sale'
                ? 'Tandai Sebagai Terjual'
                : 'Tandai Sebagai Tersewa'
        )
        ->modalDescription(fn(Property $record) =>
            $record->status_listing === 'For Sale'
                ? 'Apakah Anda yakin ingin menandai properti ini sebagai terjual?'
                : 'Apakah Anda yakin ingin menandai properti ini sebagai tersewa?'
        )
        ->modalSubmitActionLabel(fn(Property $record) =>
            $record->status_listing === 'For Sale'
                ? 'Ya, Tandai Terjual'
                : 'Ya, Tandai Tersewa'
        )
        ->visible(fn(Property $record) =>
            $record->status_listing === 'For Sale' || $record->status_listing === 'For Rent'
        )
        ->action(function (Property $record) {
            if ($record->status_listing === 'For Sale') {
                $record->update([
                    'status_terjual' => 'Terjual',
                    'tanggal_terjual' => now(),
                ]);

                Notification::make()
                    ->title('Berhasil')
                    ->body('Properti telah ditandai sebagai terjual.')
                    ->success()
                    ->send();
            } elseif ($record->status_listing === 'For Rent') {
                $record->update([
                    'status_terjual' => 'Tersewa',
                    'tanggal_terjual' => now(),
                ]);

                Notification::make()
                    ->title('Berhasil')
                    ->body('Properti telah ditandai sebagai tersewa.')
                    ->success()
                    ->send();
            }
        }),
                Tables\Actions\ViewAction::make(),


                // Tombol WhatsApp dengan Pesan Default
                Tables\Actions\Action::make('whatsapp')
                    ->label('WhatsApp')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->url(function ($record) {
                        $number = self::formatPhoneForWhatsApp($record->phone_iklan);
                        if (!$number) {
                            return null;
                        }

                        // Ambil data About
                        $about = About::first();
                        $companyName = $about->title ?? 'Perusahaan Kami';

                        // Ambil data user yang sedang login
                        $userName = auth()->user()->name ?? 'Admin';

                        // Ambil data properti
                        $propertyName = $record->name ?? '-';
                        $propertyPrice = $record->price ?? 0;
                        $formattedPrice = 'Rp ' . number_format($propertyPrice, 0, ',', '.');

                        // Buat pesan WhatsApp
                        $message = "Halo {$record->name_iklan}, saya {$userName} dari {$companyName}\n\n";
                        $message .= "Terkait Iklan Anda:\n";
                        $message .= "Properti: {$propertyName}\n";
                        $message .= "Harga: {$formattedPrice}\n\n";
                        $message .= "Terima kasih telah memasang iklan. Ada yang bisa saya bantu?";

                        // Encode pesan untuk URL
                        $encodedMessage = urlencode($message);

                        return "https://wa.me/{$number}?text={$encodedMessage}";
                    }, shouldOpenInNewTab: true)
                    ->visible(fn($record) => !empty($record->phone_iklan) && self::formatPhoneForWhatsApp($record->phone_iklan) !== null),

                // Tombol Telepon
                Tables\Actions\Action::make('call')
                    ->label('Telepon')
                    ->icon('heroicon-o-phone')
                    ->color('primary')
                    ->url(
                        fn($record) => ($number = self::formatPhoneForWhatsApp($record->phone_iklan))
                            ? 'tel:' . $number
                            : null
                    )
                    ->visible(fn($record) => !empty($record->phone_iklan) && self::formatPhoneForWhatsApp($record->phone_iklan) !== null),
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

    /**
     * Normalisasi nomor HP untuk WA (E.164)
     */
    private static function formatPhoneForWhatsApp(?string $phone): ?string
    {
        if (empty($phone)) {
            return null;
        }

        // Hapus semua karakter non-digit
        $digits = preg_replace('/\D+/', '', $phone);
        if (empty($digits)) {
            return null;
        }

        // Hapus leading 00 jika ada
        $digits = preg_replace('/^00+/', '', $digits);

        if (str_starts_with($digits, '62')) {
            $normalized = $digits;
        } elseif (str_starts_with($digits, '0')) {
            $normalized = '62' . substr($digits, 1);
        } elseif (str_starts_with($digits, '8')) {
            $normalized = '62' . $digits;
        } else {
            $normalized = $digits;
        }

        // Validasi panjang (E.164 max 15 digit)
        $len = strlen($normalized);
        if ($len < 8 || $len > 15) {
            return null;
        }

        return $normalized;
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

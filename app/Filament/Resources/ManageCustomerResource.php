<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ManageCustomerResource\Pages;
use App\Models\ManageCustomer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;

class ManageCustomerResource extends Resource
{
    protected static ?string $model = ManageCustomer::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Konsumen';
    protected static ?string $pluralLabel = 'Kelola Konsumen';
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
                    ->formatStateUsing(fn($state) => match ($state) {
                        'new' => 'Baru',
                        'in_progress' => 'Dalam Proses',
                        'completed' => 'Selesai',
                        default => $state,
                    })
                    ->colors([
                        'primary' => fn($state) => $state === 'new',
                        'warning' => fn($state) => $state === 'in_progress',
                        'success' => fn($state) => $state === 'completed',
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

                // Tombol WhatsApp
                Action::make('whatsapp')
                    ->label('WhatsApp')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->url(
                        fn($record) => ($number = self::formatPhoneForWhatsApp($record->phone))
                            ? 'https://wa.me/' . $number
                            : null,
                        shouldOpenInNewTab: true
                    )
                    ->visible(fn($record) => !empty($record->phone) && self::formatPhoneForWhatsApp($record->phone) !== null),

                // Tombol Telepon
                Action::make('call')
                    ->label('Telepon')
                    ->icon('heroicon-o-phone')
                    ->color('primary')
                    ->url(
                        fn($record) => ($number = self::formatPhoneForWhatsApp($record->phone))
                            ? 'tel:' . $number
                            : null
                    )
                    ->visible(fn($record) => !empty($record->phone) && self::formatPhoneForWhatsApp($record->phone) !== null),
            ])

            ->actionsColumnLabel('Aksi')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
        return [];
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

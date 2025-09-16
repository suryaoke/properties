<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Propertys extends Cluster
{
    protected static ?string $navigationLabel = 'Properti'; // nama di menu
    protected static ?string $pluralLabel = 'Properti'; // judul di list
    protected static ?string $label = 'Properti';

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();

        // Cluster muncul jika user punya izin untuk salah satu resource di dalamnya
        return $user?->can('view_any_property') || $user?->can('view_any_property_type');
    }
}

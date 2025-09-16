<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $count_konsumen = \App\Models\ManageCustomer::count();
        $count_property = \App\Models\Property::count();
        $count_category = \App\Models\Category::count();
        $count_city = \App\Models\City::count();
        $count_facility = \App\Models\Facility::count();

        $user = Auth::user();

        // jika role agen -> hanya Konsumen & Properti
        if ($user->hasRole('agen')) {
            return [
                Stat::make('Konsumen', $count_konsumen),
                Stat::make('Properti', $count_property),
            ];
        }

        // default (admin atau role lain) -> semua
        return [
            Stat::make('Konsumen', $count_konsumen),
            Stat::make('Properti', $count_property),
            Stat::make('Kategori', $count_category),
            Stat::make('Kota', $count_city),
            Stat::make('Fasilitas', $count_facility),
        ];
    }
}

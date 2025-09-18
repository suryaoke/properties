<?php

namespace App\Providers\Filament;

use App\Models\About;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Navigation\MenuItem;
use App\Filament\Pages\Profile;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        // Ambil data dari tabel 'about'
        // $about = About::first();
        $brandName = $about?->title ?? 'Properti';
        $brandLogo = $about?->photo ?? null;

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->sidebarCollapsibleOnDesktop(true)
            ->sidebarWidth('13rem')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->spa()
            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'primary' => Color::Indigo,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            ->pages([
                Dashboard::class,
            ])

            // SOLUSI 1: Kombinasi logo dan nama dalam HTML
            ->brandName(new HtmlString('
                <div style="display: flex; align-items: center; gap: 8px;">
                    ' . ($brandLogo ? '<img src="' . asset('storage/' . $brandLogo) . '" alt="Logo" style="height: 32px; width: auto;">' : '') . '
                    <span>' . $brandName . '</span>
                </div>
            '))

            // SOLUSI 2: Atau gunakan brandLogo saja dengan HTML yang mengandung keduanya
            // ->brandLogo(fn () => new HtmlString('
            //     <div style="display: flex; align-items: center; gap: 8px;">
            //         ' . ($brandLogo ? '<img src="' . asset('storage/' . $brandLogo) . '" alt="Logo" style="height: 32px; width: auto;">' : '') . '
            //         <span style="font-weight: 600; color: rgb(55 65 81);">' . $brandName . '</span>
            //     </div>
            // '))

            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label('My Profile')
                    ->icon('heroicon-o-user')
                    ->url(fn (): string => Profile::getUrl())
                    ->visible(fn (): bool => Auth::check()),
            ])
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
            ]);
    }
}

<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;

class Profile extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static string $view = 'filament.pages.profile';
    protected static bool $shouldRegisterNavigation = false;
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(Auth::user()->toArray());
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nama')
                ->required(),

            Forms\Components\TextInput::make('email')
                ->label('Email')
                ->email()
                ->required(),

            Forms\Components\FileUpload::make('photo')
                ->label('Gambar')
                ->image()
                ->directory('users')   // sama dengan UserResource
                ->maxSize(1024),

            Forms\Components\TextInput::make('phone')
                ->label('No. HP'),

            Forms\Components\TextInput::make('password')
                ->label('Password Baru')
                ->password()
                ->helperText('Isi hanya jika ingin mengganti password')
                ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                ->dehydrated(fn ($state) => filled($state)),
        ])->statePath('data');
    }

    public function save(): void
    {
        Auth::user()->update($this->form->getState());

        $this->dispatch('notify', title: 'Profile updated successfully');
    }
}

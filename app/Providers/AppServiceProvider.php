<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Gate;
use Filament\Navigation\UserMenuItem;
use Illuminate\Support\ServiceProvider;
use Filament\Navigation\NavigationGroup;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Filament::serving(function () {
            // Using Vite
            Filament::registerViteTheme('resources/css/filament.css');
            Filament::registerUserMenuItems($this->getUserMenu());

            // Navigation Group
            Filament::registerNavigationGroups([
                NavigationGroup::make()
                    ->label('labels.nav.group.reports')
                    ->icon('heroicon-o-view-list')
                    ->collapsible(),
                NavigationGroup::make()
                    ->label('labels.nav.group.settings')
                    ->icon('heroicon-o-cog')
                    ->collapsible(),
            ]);
        });
    }


    private function getUserMenu()
    {
        $menu = [
            'lockscreen' => UserMenuItem::make()
                ->label(__('labels.nav.user_menu.lock_screen'))
                ->url(route('lockscreenpage'))
                ->icon('heroicon-o-lock-closed'),
        ];



        /** @var \App\Models\User */
        if ($user = auth()->user()) {

            $menu[] = UserMenuItem::make()
                ->label(__('labels.nav.user_menu.settings'))
                ->url(route('filament.resources.users.edit', auth()->id()))
                ->icon('heroicon-o-cog');
        }

        return $menu;
    }
}

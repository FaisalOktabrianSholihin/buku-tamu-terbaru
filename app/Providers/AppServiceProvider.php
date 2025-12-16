<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentView;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Tambahkan manifest & service worker agar muncul di semua halaman
        FilamentView::registerRenderHook(
            'head.start',
            function () {
                return <<<'HTML'
                    <link rel="manifest" href="/manifest.json">
                    <meta name="theme-color" content="#ffffff">

                    <script>
                        if ('serviceWorker' in navigator) {
                            navigator.serviceWorker.register('/sw.js')
                                .catch(error => console.error('SW registration failed:', error));
                        }
                    </script>
                HTML;
            }
        );
    }
}

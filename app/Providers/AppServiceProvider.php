<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

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
        // Blade directive لتحويل @username إلى روابط
        Blade::directive('mentions', function ($expression) {
            return "<?php echo linkify_mentions($expression); ?>";
        });
        
        // Blade directive لتنسيق اسم المستخدم مع رابط
        Blade::directive('username', function ($expression) {
            return "<?php echo format_username($expression); ?>";
        });
    }
}

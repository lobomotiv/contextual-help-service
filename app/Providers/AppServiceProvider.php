<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(\HTMLPurifier::class, static function () {
            $config = \HTMLPurifier_Config::createDefault();
            $config->set('Attr.EnableID', true);

            return new \HTMLPurifier($config);
        });
    }
}

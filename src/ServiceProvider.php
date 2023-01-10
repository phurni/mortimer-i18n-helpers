<?php

namespace Mortimer\I18nHelpers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Support\Carbon;

class ServiceProvider extends BaseServiceProvider
{
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
        $this->publishes([
            __DIR__.'../lang/en' => $this->app->langPath('en'),
        ]);
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
	}
}

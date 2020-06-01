<?php

namespace LaravelPolyglot\Providers;

use Illuminate\Translation\TranslationServiceProvider as IlluminateTranslationServiceProvider;
use LaravelPolyglot\Translation\Translator;
use Polyglot\Polyglot;
use Illuminate\Support\Str;

class TranslationServiceProvider extends IlluminateTranslationServiceProvider
{
    /**
     * @inheritDoc
     */
    public function register()
    {
        parent::register();

        $this->registerPolyglot();
        $this->registerTranslator();

        $this->mergeConfigFrom(__DIR__.'/../config/laravel-polyglot.php', 'laravel-polyglot');
    }

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole() && ! Str::contains($this->app->version(), 'Lumen')) {
            $this->publishes([
                __DIR__.'/../config/laravel-polyglot.php' => config_path('laravel-polyglot.php'),
            ], 'config');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array_merge(parent::provides(), ['translation.polyglot']);
    }

    /**
     * Register the polyglot instance.
     */
    protected function registerPolyglot()
    {
        $this->app->singleton('translation.polyglot', static function ($app) {
            $defaultLocale = $app['config']['app.locale'];
            $polyglotConfig = $app['config']['laravel-polyglot']['polyglot'] ?? [];

            $options = [
                'locale' => $defaultLocale,
                'allowMissing' => $polyglotConfig['allowMissing'],
                'delimiter' => $polyglotConfig['delimiter'],
                'interpolation' => $polyglotConfig['interpolation'],
                'pluralRules' => $polyglotConfig['pluralRules'],
            ];

            if (isset($polyglotConfig['onMissingKey']) &&
                is_callable($polyglotConfig['onMissingKey']) &&
                in_array(env('APP_ENV', 'production'), $polyglotConfig['onMissingKeyEnvs'])
            ) {
                $options['onMissingKey'] = $polyglotConfig['onMissingKey'];
            }

            return new Polyglot($options);
        });
    }

    /**
     * Register the translator.
     */
    protected function registerTranslator()
    {
        $this->app->singleton('translator', static function ($app) {
            $loader = $app['translation.loader'];
            $polyglot = $app['translation.polyglot'];

            // When registering the translator component, we'll need to set the default
            // locale as well as the fallback locale. So, we'll grab the application
            // configuration so we can easily get both of these values from there.
            $locale = $app['config']['app.locale'];

            $trans = new Translator($polyglot, $loader, $locale);

            $trans->setFallback($app['config']['app.fallback_locale']);

            return $trans;
        });
    }
}
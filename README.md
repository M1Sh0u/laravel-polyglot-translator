# Laravel Polyglot.php Translator


[![Latest Version on Packagist](https://img.shields.io/packagist/v/m1sh0u/laravel-polyglot-translator.svg?style=flat-square)](https://packagist.org/packages/m1sh0u/laravel-polyglot-translator)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Code Quality](https://scrutinizer-ci.com/g/M1Sh0u/laravel-polyglot-translator/badges/quality-score.png?b=master&style=flat-square)](https://scrutinizer-ci.com/g/M1Sh0u/laravel-polyglot-translator/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/m1sh0u/laravel-polyglot-translator.svg?style=flat-square)](https://packagist.org/packages/m1sh0u/laravel-polyglot-translator)

Installing this package will enable using [Polyglot.php](https://github.com/M1Sh0u/polyglot.php) to translate phrases through a Laravel application.

When installed, the `trans` and `trans_choice` will use the `Polyglot.php` library to translate the given phrases:

```php
trans('Hello, %{placeholder}', ['placeholder' => 'World']);
trans('1 vote %{period} |||| %{smart_count} votes %{period}', ['smart_count' => 4, 'period' => 'today'])

trans_choice('1 vote %{period} |||| %{smart_count} votes %{period}', 4, ['period' => 'today']);
```

### Installation
You can install the package via composer:

```
composer require m1sh0u/laravel-polyglot-translator
```

In `config/app.php` (Laravel) or `bootstrap/app.php` (Lumen) you should replace Laravel's translation service provider,

```
Illuminate\Translation\TranslationServiceProvider::class,
```

by the one included in this package:

```
LaravelPolyglot\Providers\TranslationServiceProvider
```

Optionally you could publish the config file using the command:

```
php artisan vendor:publish --provider="LaravelPolyglot\Providers\TranslationServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [
    // @see https://github.com/M1Sh0u/polyglot.php for the meaning of each polyglot configuration parameter
    'polyglot' => [
        
        'allowMissing' => true,
        'delimiter' => '||||',
        'interpolation' => [
            'prefix' => '%{',
            'suffix' => '}'
        ],
        'pluralRules' => [],

        // Set a callback function to be called whenever a missing key is found.
        // It could be useful if you need to store the missing keys into the database or to do something else.
        // Please note that the return of this callback will be the actual string returned by the translator. @see https://github.com/M1Sh0u/polyglot.php
        'onMissingKey' => null,
        'onMissingKeyEnvs' => ['local', 'staging']
    ]
];
```

### Usage
For more information about Polyglot.php' capabilities please follow its [documentation](https://github.com/M1Sh0u/polyglot.php)

> Note: publishing assets doesn't work out of the box in Lumen. Instead you have to copy the files from the repo.

### License
The MIT License (MIT). Please see License File for more information.
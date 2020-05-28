<?php
/**
 * This file is part of Staffcloud.
 *
 * @copyright  Copyright (c) 2015 and Onwards, Smartbridge AG <info@smartbridge.ch>. All rights reserved.
 * @license    Proprietary/Closed Source
 * @see        https://www.staff.cloud
 */

declare(strict_types=1);

namespace LaravelPolyglot\Translation;

use Illuminate\Contracts\Translation\Loader;
use Illuminate\Translation\Translator as IlluminateTranslationTranslator;
use Polyglot\Polyglot;

class Translator extends IlluminateTranslationTranslator
{
    /**
     * @var Polyglot
     */
    private $polyglot;

    /**
     * Translator constructor.
     *
     * @param Polyglot $polyglot
     * @param Loader   $loader
     * @param          $locale
     */
    public function __construct(Polyglot $polyglot, Loader $loader, $locale)
    {
        parent::__construct($loader, $locale);

        $this->polyglot = $polyglot;
    }

    /**
     * @inheritDoc
     */
    public function get($key, array $replace = [], $locale = null, $fallback = true)
    {
        $locale = $locale ?? $this->locale;
        $namespace = $replace['namespace'] ?? '*';
        $group = $replace['group'] ?? '*';

        $this->load($namespace, $group, $locale);

        $this->polyglot->replace($this->loaded[$namespace][$group][$locale]);
        $this->polyglot->locale($locale);

        return $this->polyglot->t($key, $replace);
    }

    /**
     * @inheritDoc
     */
    public function choice($key, $number, array $replace = [], $locale = null)
    {
        // If the given "number" is actually an array or countable we will simply count the
        // number of elements in an instance. This allows developers to pass an array of
        // items without having to count it on their end first which gives bad syntax.
        if (is_countable($number)) {
            $number = count($number);
        }

        $replace['smart_count'] = $number;

        return $this->get($key, $replace, $locale);
    }
}
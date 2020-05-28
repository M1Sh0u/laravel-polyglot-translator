<?php

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
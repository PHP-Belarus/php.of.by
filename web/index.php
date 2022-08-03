<?php

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    $debug = (bool) $context['APP_DEBUG'];

    if ($debug) {
        mask(0000);
    }

    return new Kernel($context['APP_ENV'], $debug);
};

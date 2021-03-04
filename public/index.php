<?php

declare(strict_types=1);

/**
 * Package: Street-Api.
 * 03 March 2021
 */

use App\Helpers\Quest;

require __DIR__ . '/./../vendor/autoload.php';

ini_set('display_startup_errors', '1');
ini_set('display_errors', '1');
error_reporting(-1);
\App\Helpers\Config::init();
new Quest();
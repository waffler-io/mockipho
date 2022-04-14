<?php

/*
 * This file is part of Waffler\Mockipho.
 * (c) Erick Johnson Almeida de Menezes <erickmenezes.dev@gmail.com>
 * This source file is subject to the MIT licence that is bundled
 * with this source code in the file LICENCE.
 */

include_once __DIR__.'/vendor/autoload.php';

use ZEngine\Core;

$reflectionCore = new ReflectionClass(Core::class);
$engine = $reflectionCore->getProperty('engine');
$engine->setAccessible(true);
if (!$engine->isInitialized()) {
    try {
        Core::preload();
    } catch (\FFI\Exception) {
        Core::init();
    }
}

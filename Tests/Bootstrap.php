<?php

require_once __DIR__ . '/../../../../autoload.php';

$enlightLoader = new Enlight_Loader();

// register the namespace of the plugin because its not a normal shop environment.
$enlightLoader->registerNamespace('SwagAdvDevBundle', __DIR__ . '/../');

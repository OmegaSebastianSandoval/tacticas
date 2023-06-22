<?php

require __DIR__ . '/vendor/autoload.php';

use EdSDK\FlmngrServer\FlmngrServer;

FlmngrServer::flmngrRequest(
    array(
        'dirFiles' => '../../upload',
        'dirTmp'   => 'tmp',
        'dirCache' => 'cache'
    )
);
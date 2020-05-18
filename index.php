<?php

use Hyperf\Nano\Factory\AppFactory;

require_once __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create('0.0.0.0', 8087);

$app->get('/*', function () {

    $user = $this->request->input('user', 'nano');
    $method = $this->request->getMethod();

    return [
        'message' => "hello {$user}",
        'method' => $method,
    ];

});

$app->run();
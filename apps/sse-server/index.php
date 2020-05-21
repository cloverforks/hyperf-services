<?php

use Hyperf\Nano\ContainerProxy;
use Hyperf\Nano\Factory\AppFactory;
use Hyperf\Utils\Context;
use Psr\Http\Message\ResponseInterface;
use Swoole\Http\Response;

require_once __DIR__ . '/../../vendor/autoload.php';

$app = AppFactory::createBase('0.0.0.0', 8080);
$app->get('/', function () {
    /** @var ContainerProxy $this */
    /** @var Response $response */
    $response = Context::get(ResponseInterface::class)->getSwooleResponse();
    $response->header('Content-Type', 'text/html; charset=UTF-8');
    $response->end(file_get_contents(__DIR__ . '/sse.html'));

});
$app->get('/sse', function () {
    /** @var ContainerProxy $this */
    /** @var Response $response */
    $method = $this->request->getMethod();
    $token = $this->request->input('access_token', '');

    $response = Context::get(ResponseInterface::class)->getSwooleResponse();
    $response->header('Content-Type', 'text/event-stream');
    $counter = 100;
    while ($counter-- > 0) {
        $data = "event: ping\n";
        $curDate = date("Y-m-d H:i:s");
        $data .= 'data: {"time": "' . $curDate . '"}';
        $data .= "\n\n";

        if (!$counter || $counter % 3 === 0)
            $data .= 'data: This is a message at time ' . $curDate . "\n\n";

        $response->write($data);
        sleep(1);
    }
    $response->end('');
});

$app->run();
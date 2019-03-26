<?php

use PTS\RateLimiter\Adapter\MemoryAdapter;
use PTS\RateLimiter\Limiter;
use PTS\RateLimiter\RateLimitMiddleware;
use Relay\Relay;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\ServerRequestFactory;

require_once '../vendor/autoload.php';

$limitStore = new MemoryAdapter;
$rateLimiter = new Limiter($limitStore);
$response = new JsonResponse(['error' => 'Too Many Requests'], 429);

$limiterMiddleware = new RateLimitMiddleware($rateLimiter, $response);
$limiterMiddleware->setKeyAttr('ip');

$psr15Runner = new Relay([
    $limiterMiddleware
]);

$psr7Request = ServerRequestFactory::fromGlobals();
$response = $psr15Runner->handle($psr7Request);

// flush response or other
// ...

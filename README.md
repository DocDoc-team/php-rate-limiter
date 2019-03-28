# Rate Limiter

Fork from https://github.com/alexpts/php-rate-limiter


[![Build Status](https://travis-ci.org/DocDoc-team/php-rate-limiter.svg?branch=master)](https://travis-ci.org/DocDoc-team/php-rate-limiter)


Rate limiter + PSR-15 middleware


#### Install

`composer require docdoc/php-rate-limiter`


#### Example

```php
<?php


use DocDoc\RateLimiter\Adapter\MemoryAdapter;
use DocDoc\RateLimiter\Limiter;
use DocDoc\RateLimiter\RateLimitMiddleware;
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
]); // relay or other psr-15 runner

$psr7Request = ServerRequestFactory::fromGlobals();
$response = $psr15Runner->handle($psr7Request);

// flush response or other
// ...

```

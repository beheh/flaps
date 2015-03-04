# Flaps

Flaps is a simplistic library for PHP applications to rate limit various requests.

## Why rate limit?

There are many benefits from rate-limiting your web application. At any point in time your web application or API could be hit by a huge number of requests from some clients. This could be:
- Malicious clients trying to degrade your applications performance
- Malicious clients bruteforcing user credentials
- Bugged clients repeating requests over and over again
- Automated web crawlers indexing as much data as possible without 
- Spambots attempting to register a large number of users
- Spambots attempting to post links to malicious sites

Most of these problems can be solved in a variety of ways, for example by using a spam filter or a fully configured firewall. Rate limiting is nethertheless a basic tool for improving application security, but offers no full protection.

## Requirements

- PHP 5.3+
- A somewhat persistent storage container (e.g. Redis, APC or anything that is supported by Doctrine\Cache)

## Basic usage

```php
use Predis\Client;
use BehEh\Flaps\Storage\PredisStorage;
use BehEh\Flaps\Wing;

$storage = new PredisStorage(new Client());
$wing = new Wing($storage);
$flap = $wing->getFlap('api'); // limiting access to "api"

$flap->pushThrottlingStrategy(new TimeBasedThrottlingStrategy(10, '5s')); // 10 requests per 5 seconds
$flap->pushThrottlingStrategy(new TimeBasedThrottlingStrategy(200, '5m')); // 200 requests per 5 minutes
$flap->limit($_SERVER['HTTP_REMOTE_ADDR']); // add request for this ip and exit with HTTP 429, if limit exceeded
```

Each flap is a certain part of your application you would like to protect. It might be all of your api, certain requests which require authentication or only your login page.

Once a user violates any throttling strategy of a flap, a violation handler kicks in. The default is to send the user an "HTTP 429 Too Many Requests" and terminate.

## Storage

### Predis

The easiest storage system to get started is Redis via Predis:

```php
use Predis\Client;
use BehEh\Flaps\Storage\PredisStorage;
use BehEh\Flaps\Wing;

$storage = new PredisStorage(new Client('tcp://10.0.0.1:6379'));
$wing = new Wing($storage);
```

### Doctrine

You can use any of the [Doctrine caching implementations](http://doctrine-common.readthedocs.org/en/latest/reference/caching.html) by using the `DoctrineCacheAdapter`.

```php
use Doctrine\Common\Cache\ApcCache;
use BehEh\Flaps\Storage\DoctrineCacheAdapter;
use BehEh\Flaps\Wing;

$apc = new ApcCache();
$apc->setNamespace('MyApplication');
$storage = new DoctrineCacheAdapter($apc);
$wing = new Wing($storage);
```

### Custom storage

Alternatively you can use your own storage system by implementing `BehEh\Flaps\StorageInterface`.

## Throttling strategies

```php
use BehEh\Flaps\Throttling\TimeBasedThrottlingStrategy;

$flap->addThrottlingStrategy(new TimeBasedThrottlingStrategy());
```

class CustomThrottlingStrategy implements ThrottlingStrategy {
	public function isViolator($identifier) { ... }
}

## Violation handler

You can handle violations either using one of the included handlers or by writing your own (implementing the interface `ViolationHandler`).

## HTTP violation handler

```php
use BehEh\Flaps\Violation\HttpViolationHandler;

$flap->setViolationHandler(new HttpViolationHandler);
$flap->limit($user); // sends HTTP 429 and exits on violation (default)
```

## Passive violation handler

```php
use BehEh\Flaps\Violation\PassiveViolationHandler;

$flap->setViolationHandler(new PassiveViolationHandler);
if(!$flap->limit($user)) {
	// violation
}
```

### Exception violation handler

```
use BehEh\Flaps\Violation\ExceptionViolationHandler;

$flap->setViolationHandler(new ExceptionViolationHandler);
try {
	$flap->limit($user); // throws RuntimeException on violation
}
catch(\RuntimeException $e) {
	// violation
}
```

### Custom violation handler

```php
class CustomViolationHandler implements ViolationHandler {
	// the return value will be passed on to and returned by limit()
	public function handleViolation($identifier) { ... }
}
```

### Default violation handler

The `Wing` object can pass a default violation handler the flaps.

```php
$flaps->setDefaultViolationHandler(new CustomViolationHandler);

$loginFlap = $flaps->getFlap('login');
$loginFlap->addThrottlingStrategy(new TimeBasedThrottlingStrategy(1, '1s'));
$loginFlap->limit($identifier); // will use CustomViolationHandler
```

## Credits

This library was created by Benedict Etzel (developer@beheh.de) and is licensed under the ISC license.

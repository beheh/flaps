# Flaps

Flaps is a fully configurable library for rate limiting requests in your PHP application.
The library supports custom storage backends, throttling strategies and violation handlers for flexible integration into any project.

## Requirements

- PHP 5.3+
- A storage container (e.g. Redis, APC or anything supported by [`Doctrine\Cache`](http://doctrine-common.readthedocs.org/en/latest/reference/caching.html)

## Basic usage

```php
use Predis\Client;
use BehEh\Flaps\Storage\PredisStorage;
use BehEh\Flaps\Throttling\LeakyBucketStrategy
use BehEh\Flaps\Wing;

// setup storage
$storage = new PredisStorage(new Client());
$wing = new Wing($storage);

// rate limit login attempts by ip
$flap = $wing->getFlap('login');
$flap->pushThrottlingStrategy(new LeakyBucketStrategy(3, '5s'));
$flap->limit($_SERVER['HTTP_REMOTE_ADDR']);
```

## Why rate limit?

There are many benefits from rate limiting your web application. At any point in time your server(s) could be hit by a huge number of requests from one or many clients. These could be:
- Malicious clients trying to degrade your applications performance
- Malicious clients bruteforcing user credentials
- Bugged clients repeating requests over and over again
- Automated web crawlers searching for usernames or email adresses
- Penetration frameworks testing for sql injections and other vulnerabilities
- Spambots attempting to register a large number of users
- Spambots attempting to post links to malicious sites

Most of these problems can be solved in a variety of ways, for example by using a spam filter or a fully configured firewall. Rate limiting is nevertheless a basic tool for improving application security, but offers no full protection.

## Advanced examples

```php
// different violation handler
$flap = $wing->getFlap('api');
$flap->pushThrottlingStrategy(new LeakyBucketStrategy(15, '10s'));
$flap->setViolationHandler(new PassiveViolationHandler);
if(!$flap->limit(filter_var(INPUT_GET, 'api_key'))) {
	die(json_encode(array('error' => 'too many requests')));
}
```

```php
// multiple throttling strategies
$flap = $wing->getFlap('api');
$flap->pushThrottlingStrategy(new LeakyBucketStrategy(15, '10s'));
$flap->pushThrottlingStrategy(new LeakyBucketStrategy(15, '10s'));
$flap->limit($userid);
```

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

### Doctrine cache

You can use any of the [Doctrine caching implementations](http://doctrine-common.readthedocs.org/en/latest/reference/caching.html) by using the `DoctrineCacheAdapter`:

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

### Leaky bucket strategy

This strategy is based on the leaky bucket algorithm. Each unique identifier of a flap corresponds to a leaky bucket.
Clients can now access the buckets as much as they like, inserting water for every request. If a request would cause the bucket to overflow, it is denied.
In order to allow later requests, the bucket leaks at a fixed rate.

```php
use BehEh\Flaps\Throttle\LeakyBucketStrategy;

$flap->addThrottle(new LeakyBucketStrategy(2000, '10m'));
```

### Custom throttling strategy

Once again you can use your own throttling strategy by implementing `BehEh\Flaps\ThrottlingStrategyInterface`.

## Violation handler

You can handle violations either using one of the included handlers or by writing your own (implementing the interface `ViolationHandler`).

## HTTP violation handler

```php
use BehEh\Flaps\Violation\HttpViolationHandler;

$flap->setViolationHandler(new HttpViolationHandler);
$flap->limit($user); // sends HTTP 429 "Too Many Requests" and exits on violation (default)
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

```php
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

The corresponding interface for custom violation handlers is `BehEh\Flaps\ViolationHandlerInterface`.

### Default violation handler

The `Wing` object can pass a default violation handler to the flaps.

```php
$flaps->setDefaultViolationHandler(new CustomViolationHandler);

$loginFlap = $flaps->getFlap('login');
$loginFlap->addThrottlingStrategy(new TimeBasedThrottlingStrategy(1, '1s'));
$loginFlap->limit($identifier); // will use CustomViolationHandler
```

## Credits

This library was created by Benedict Etzel (developer@beheh.de) and is licensed under the ISC license.

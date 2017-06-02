# Flaps

[![Travis](https://img.shields.io/travis/beheh/flaps/master.svg?style=flat-square)](https://travis-ci.org/beheh/flaps)
[![Scrutinizer Coverage](https://img.shields.io/scrutinizer/coverage/g/beheh/flaps/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/beheh/flaps/?branch=master)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/beheh/flaps/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/beheh/flaps/?branch=master)
[![Packagist](https://img.shields.io/packagist/v/beheh/flaps.svg?style=flat-square)](https://packagist.org/packages/beheh/flaps)
[![Packagist](https://img.shields.io/packagist/l/beheh/flaps.svg?style=flat-square)](https://packagist.org/packages/beheh/flaps)

Flaps is a modular library for rate limiting requests in PHP applications.

The library supports custom storage backends, throttling strategies and violation handlers for flexible integration into any project.

Developed by [@beheh](https://github.com/beheh) and licensed under the ISC license.

## Requirements

- PHP 5.4 or newer
- Persistent-ish storage (e.g. Redis, APC or anything supported by _[Doctrine\Cache](http://doctrine-common.readthedocs.org/en/latest/reference/caching.html)_)
- Composer

## Basic usage

```php
use Predis\Client;
use BehEh\Flaps\Storage\PredisStorage;
use BehEh\Flaps\Flaps;
use BehEh\Flaps\Throttling\LeakyBucketStrategy;

// setup with Redis as storage backend
$storage = new PredisStorage(new Client());
$flaps = new Flaps($storage);

// allow 3 requests per 5 seconds
$flaps->login->pushThrottlingStrategy(new LeakyBucketStrategy(3, '5s'));
//or $flaps->__get('login')->pushThrottlingStrategy(...)

// limit by ip (default: send "HTTP/1.1 429 Too Many Requests" and die() on violation)
$flaps->login->limit($_SERVER['REMOTE_ADDR']);
```

## Why rate limit?

There are many benefits from rate limiting your web application. At any point in time your server(s) could be hit by a huge number of requests from one or many clients. These could be:
- Malicious clients trying to degrade your applications performance
- Malicious clients bruteforcing user credentials
- Bugged clients repeating requests over and over again
- Automated web crawlers enumerating usernames or email adresses
- Penetration frameworks testing for vulnerabilities
- Bots registering a large number of users
- Bots spamming links to malicious sites

Most of these problems can be solved in a variety of ways, for example by using a spam filter or a fully configured firewall. Rate limiting is nevertheless a basic tool for improving application security, but offers no full protection.

## Advanced examples

### Application-handled violation

```php
use BehEh\Flaps\Throttling\LeakyBucketStrategy;
use BehEh\Flaps\Violation\PassiveViolationHandler;

$flap = $flaps->__get('api');
$flap->pushThrottlingStrategy(new LeakyBucketStrategy(15, '10s'));
$flap->setViolationHandler(new PassiveViolationHandler);
if (!$flap->limit(filter_var(INPUT_GET, 'api_key'))) {
	die(json_encode(array('error' => 'too many requests')));
}
```

### Multiple throttling strategies

```php
use BehEh\Flaps\Throttling\LeakyBucketStrategy;

$flap = $flaps->__get('add_comment');
$flap->pushThrottlingStrategy(new LeakyBucketStrategy(1, '30s'));
$flap->pushThrottlingStrategy(new LeakyBucketStrategy(10, '10m'));
$flap->limit($userid);
```

## Storage

### Redis

The easiest storage system to get started is Redis (via [nrk/predis](https://github.com/nrk/predis)):

```php
use Predis\Client;
use BehEh\Flaps\Storage\PredisStorage;
use BehEh\Flaps\Flaps;

$storage = new PredisStorage(new Client('tcp://10.0.0.1:6379'));
$flaps = new Flaps($storage);
```

Don't forget to `composer require predis/predis`.

### Doctrine cache

You can use any of the [Doctrine cache implementations](http://doctrine-common.readthedocs.org/en/latest/reference/caching.html) by using the _DoctrineCacheAdapter_:

```php
use Doctrine\Common\Cache\ApcCache;
use BehEh\Flaps\Storage\DoctrineCacheAdapter;
use BehEh\Flaps\Flaps;

$apc = new ApcCache();
$apc->setNamespace('MyApplication');
$storage = new DoctrineCacheAdapter($apc);
$flaps = new Flaps($storage);
```

The Doctrine caching implementations can be installed with `composer require doctrine/cache`.

### Custom storage

Alternatively you can use your own storage system by implementing _BehEh\Flaps\StorageInterface_.

## Throttling strategies

### Leaky bucket strategy

This strategy is based on the leaky bucket algorithm. Each unique identifier of a flap corresponds to a leaky bucket.
Clients can now access the buckets as much as they like, inserting water for every request. If a request would cause the bucket to overflow, it is denied.
In order to allow later requests, the bucket leaks at a fixed rate.

```php
use BehEh\Flaps\Throttle\LeakyBucketStrategy;

$flap->pushThrottlingStrategy(new LeakyBucketStrategy(60, '10m'));
```

### Custom throttling strategy

Once again, you can supply your own throttling strategy by implementing _BehEh\Flaps\ThrottlingStrategyInterface_.

## Violation handler

You can handle violations either using one of the included handlers or by writing your own.

## HTTP violation handler

The HTTP violation handler is the most basic violation handler, recommended for simple scripts.
It simply sends the correct HTTP header (status code 429) and die()s. This is not recommended for any larger application and should be replaced by one of the more customizable handlers.

```php
use BehEh\Flaps\Violation\HttpViolationHandler;

$flap->setViolationHandler(new HttpViolationHandler);
$flap->limit($identifier);  // send "HTTP/1.1 429 Too Many Requests" and die() on violation
```

## Passive violation handler

The passive violation handler allows you to easily react to violations.
`limit()` will return false if the requests violates any throttling strategy, so you are able to log the request or return a custom error page.

```php
use BehEh\Flaps\Violation\PassiveViolationHandler;

$flap->setViolationHandler(new PassiveViolationHandler);
if (!$flap->limit($identifier)) {
	// violation
}
```

### Exception violation handler

The exception violation handler can be used in larger frameworks. It will throw a _ThrottlingViolationException_ whenever a _ThrottlingStrategy_ is violated.
You should be able to setup your exception handler to catch any _ThrottlingViolationException_.

```php
use BehEh\Flaps\Violation\ExceptionViolationHandler;
use BehEh\Flaps\Violation\ThrottlingViolationException;

$flap->setViolationHandler(new ExceptionViolationHandler);
try {
	$flap->limit($identifier); // throws ThrottlingViolationException on violation
}
catch (ThrottlingViolationException $e) {
	// violation
}
```

### Custom violation handler

The corresponding interface for custom violation handlers is _BehEh\Flaps\ViolationHandlerInterface_.

### Default violation handler

The `Flaps` object can pass a default violation handler to the flaps.

```php
$flaps->setDefaultViolationHandler(new CustomViolationHandler);

$flap = $flaps->__get('login');
$flap->addThrottlingStrategy(new TimeBasedThrottlingStrategy(1, '1s'));
$flap->limit($identifier); // will use CustomViolationHandler
```

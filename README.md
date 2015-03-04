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

## Basic usage

```php
$cache = new Doctrine\Common\Cache\ApcCache();
$cache->setNamespace('MyApplication');
$flaps = new Flaps(new DoctrineAdapter($cache));
$apiFlap = $flaps->getFlap('api');
$apiFlap->addThrottlingStrategy(new TimeBasedThrottlingStrategy(10, '10m'));
$apiFlap->limit($_SERVER['HTTP_REMOTE_ADDR']);
```

Each flap is a certain part of your application you would like to protect. It might be all of your api, certain requests which require authentication or only your login page.

Once a user violates any throttling strategy of a flap, a violation handler kicks in. The default is to send the user an "HTTP 429 Too Many Requests" and terminate.

## Storage adapters

class CustomStorageAdapater implements StorageAdapter {
}

APCAdapter
RedisAdapter

## Throttling strategies

```php
$flaps->addThrottlingStrategy(new TimeBasedThrottlingStrategy());
```

class CustomThrottlingStrategy implements ThrottlingStrategy {
	public function isViolator($identifier) { ... }
}

## Violation handler

You can handle violations either using one of the included handlers or by writing your own (implementing the interface `ViolationHandler`).

```php
$flaps->setViolationHandler(new HttpViolationHandler); // limit() writes HTTP 429 and exits (default)
$flaps->setViolationHandler(new ExceptionViolationHandler); // limit() throws RuntimeException on violation
$flaps->setViolationHandler(new PassiveViolationHandler); // limit() returns false on violation
```

class CustomViolationHandler implements ViolationHandler {
	// the return value will be passed on to and returned by limit()
	public function handleViolation($identifier) { ... }
}

## License

This library was created by Benedict Etzel (developer@beheh.de) and is licensed under the ISC license.

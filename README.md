# Random Extension Polyfill for PHP

[![Packagist](https://img.shields.io/packagist/v/arokettu/random-polyfill.svg?style=flat-square)](https://packagist.org/packages/arokettu/random-polyfill)
[![PHP](https://img.shields.io/packagist/php-v/arokettu/random-polyfill.svg?style=flat-square)](https://packagist.org/packages/arokettu/random-polyfill)
[![Packagist](https://img.shields.io/packagist/l/arokettu/random-polyfill.svg?style=flat-square)](https://opensource.org/licenses/BSD-3-Clause)
[![Gitlab pipeline status](https://img.shields.io/gitlab/pipeline/sandfox/php-random-polyfill/master.svg?style=flat-square)](https://gitlab.com/sandfox/php-random-polyfill/-/pipelines)
[![Codecov](https://img.shields.io/codecov/c/gl/sandfox/php-random-polyfill?style=flat-square)](https://codecov.io/gl/sandfox/php-random-polyfill/)

This is a polyfill for the new `ext-random` extension that will be released with PHP 8.2.

RFC:

* https://wiki.php.net/rfc/rng_extension
* https://wiki.php.net/rfc/random_extension_improvement

## Requirements

* PHP 7.1
* GMP extension

## Installation

```bash
composer require 'arokettu/random-polyfill'
```

## Compatibility

The library is compatible with `ext-random` as released in PHP 8.2.0 beta 1.

## What works

* `Random\Randomizer`
* Engines
  * `Random\Engine` interface
  * `Random\CryptoSafeEngine` interface
  * Secure Engine: `Random\Engine\Secure`
  * Mersenne Twister: `Random\Engine\Mt19937`
  * PCG64: `Random\Engine\PcgOneseq128XslRr64`

## TODO

* Keep updating with fixes from the upcoming betas and release 1.0.0 around PHP 8.2.0 rc 1
* Empty `arokettu/random-polyfill` v1.99 for PHP 8.2.0 users
* `Xoshiro256StarStar`
* Spin-off without extension dependencies?

## Documentation

Read full documentation here: <https://sandfox.dev/php/random-polyfill.html>

Also on Read the Docs: <https://php-random-polyfill.readthedocs.io/>

## Support

Please file issues on our main repo at GitLab: <https://gitlab.com/sandfox/php-random-polyfill/-/issues>

## License

The library is available as open source under the terms of the [3-Clause BSD License].
See [COPYING.adoc][COPYING] for additional licenses.

[3-Clause BSD License]: https://opensource.org/licenses/BSD-3-Clause
[COPYING]: COPYING.adoc

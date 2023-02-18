# Random Extension Polyfill for PHP

[![PHP](https://img.shields.io/packagist/php-v/arokettu/random-polyfill/dev-master.svg?style=flat-square)](https://packagist.org/packages/arokettu/random-polyfill)
[![Packagist](https://img.shields.io/packagist/l/arokettu/random-polyfill.svg?style=flat-square)][COPYING]
[![Gitlab pipeline status](https://img.shields.io/gitlab/pipeline/sandfox/php-random-polyfill/master.svg?style=flat-square)](https://gitlab.com/sandfox/php-random-polyfill/-/pipelines)
[![Codecov](https://img.shields.io/codecov/c/gl/sandfox/php-random-polyfill?style=flat-square)](https://codecov.io/gl/sandfox/php-random-polyfill/)

This is a polyfill for the new `ext-random` extension that was released with PHP 8.2.

## Requirements

* PHP 7.1
* GMP extension is strongly recommended on PHP 7

## Installation

```bash
composer require 'arokettu/random-polyfill'
```

## Compatibility

The library aims to be compatible with `ext-random` as released in PHP 8.2.0 and subsequent patch releases.

## Documentation

### Random Extension

Read the official PHP doc: https://www.php.net/manual/en/book.random.php

### The Polyfill

Read full documentation here: <https://sandfox.dev/php/random-polyfill.html>

Also on Read the Docs: <https://php-random-polyfill.readthedocs.io/>

## Support

Please file issues on our main repo at GitHub: <https://github.com/arokettu/php-random-polyfill/issues>

## License

The library is available as open source under the terms of the [3-Clause BSD License].
See [COPYING.adoc][COPYING] for additional licenses.

[3-Clause BSD License]: LICENSE.md
[COPYING]: COPYING.adoc

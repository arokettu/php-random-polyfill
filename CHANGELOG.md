# Changelog

[gh@TimWolla]: https://github.com/TimWolla
[gh@nicolas-grekas]: https://github.com/nicolas-grekas

## Special

### 1.99.0

*Sep 2, 2022*

A special empty version to be installed on PHP >= 8.2.

## 1.x

### 1.0.4 "The Final"_final

*Apr 1, 2025*

* Upgraded embedded arokettu/unsigned to 1.3.6, a more future-proof version 

### 1.0.3 "The Final"

*Feb 20, 2025*

* Inline arokettu/unsigned
* Unlock the upper PHP version because the lib will fall back to the native ext-random in 8.2+ anyway

### 1.0.2

*Sep 7, 2023*

* Bump arokettu/unsigned to 1.2.1, previous versions may cause invalid results in Mersenne Twister on 32-bit systems.

### 1.0.1

*Nov 12, 2022*

* Rely on symfony/polyfill-php82 for interface implementations and Random\Engine\Secure ([#6][gh#6]),
  thanks to [Nicolas Grekas][gh@nicolas-grekas]
* Added compatibility with PHP regression fix: [PHP#9839] ([#5][gh#5])

[gh#6]: https://github.com/arokettu/php-random-polyfill/pull/6
[PHP#9839]: https://github.com/php/php-src/pull/9839
[gh#5]: https://github.com/arokettu/php-random-polyfill/pull/5

### 1.0.0

*Oct 26, 2022*

Since RC1/RC2 are compatible with last 4 release candidates of PHP 8.2 (RC2-RC5),
RC2 is re-released as stable.

* No user facing changes since RC2

### 1.0.0-rc2

*Oct 1, 2022*

* Speed up 32 bit calculations in 64 bit envs

### 1.0.0-rc1

*Sep 14, 2022*

* `Secure` engine throws `RandomException` instead of `Exception`
* Added verification of the number of elements in serialized data
* Generated data is truncated to 64 bits in `Randomizer::getBytes()`
* Fixed integer overflow in the upstream math lib

### 1.0.0-beta1

*Sep 2, 2022*

* Fix incompatibility with PHP 8.2.0 RC1

## 0.x

### 0.5.0

*Sep 1, 2022*

Hopefully the last alpha

* Exceptions are now compatible in most known cases
* Full coverage
* GMP is now an optional dependency (but highly recommended)

### 0.4.0

*Aug 19, 2022*

* Verified compatibility with PHP 8.2.0 beta 3
* Tests ported from the PHP engine
* Various compatibility fixes
* Exceptions compatibility (mostly)

### 0.3.0

*Aug 5, 2022*

* Verified compatibility with PHP 8.2.0 beta 2
* `PcgOneseq128XslRr64`
* `Xoshiro256StarStar`
* Split `nextInt()` and `getInt($min, $max)` like in beta 2

### 0.2.1

*Jul 31, 2022*

* Verified compatibility of custom engines with the current PHP 8.2 master
* ``getInt()`` now has proper signature ``getInt(int $min, int $max)``
* ``arrayPickKeys()`` throws a warning because full compatibility is not achievable.
  Thanks to [Tim Düsterhus][gh@TimWolla] for [the explanation][gh#1]
  * No warning if the engine is ``CryptoSafeEngine``
* Fixed incorrect range function selection ([#1][gh#1]), thanks to [Tim Düsterhus][gh@TimWolla]
* Fixed byte selection in range64

[gh#1]: https://github.com/arokettu/php-random-polyfill/issues/1

### 0.2.0

*Jul 27, 2022*

NOTE: 0.2.0 currently is not fully compatible when using custom engines.
This will be fixed in 0.3.0 after PHP 8.2.0 beta 2 is released with some critical fixes. 

* `Randomizer`
  * `$engine`
  * `getBytes($length)`
  * `shuffleArray($array)`
  * `shuffleBytes($bytes)`
  * `arrayPickKeys($array, $num)`
  * Serialization and unserialization are now compatible with PHP 8.2
    if performed in PHP 7.4+
* `Mt19937`
  * Serialization and unserialization are now compatible with PHP 8.2
    if performed in PHP 7.4+

### 0.1.1

*Jul 23, 2022*

* Fixed Mt not generating enough data sometimes

### 0.1.0

*Jul 23, 2022*

* First release
* Compatible with PHP 8.2.0 beta 1
* Engines:
  * Secure Random
  * Mersenne Twister
* Randomizer features:
  * `getInt()`
  * `getInt($min, $max)`

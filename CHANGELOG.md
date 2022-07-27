# Changelog

## 0.2.0

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

## 0.1.1

*Jul 23, 2022*

* Fixed Mt not generating enough data sometimes

## 0.1.0

*Jul 23, 2022*

* First release
* Compatible with PHP 8.2.0 beta 1
* Engines:
  * Secure Random
  * Mersenne Twister
* Randomizer features:
  * `getInt()`
  * `getInt($min, $max)`

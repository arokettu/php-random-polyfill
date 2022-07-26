# Changelog

## 0.next

* `Randomizer`
  * `$engine`
  * `getBytes($length)`
  * `shuffleArray($array)`
  * `shuffleBytes($bytes)`
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

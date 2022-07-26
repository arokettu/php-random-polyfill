Compatibility Notes
###################

PHP
===

The library is aimed to be compatible with ``ext-random`` from PHP 8.1.

* Version 0.1 is compatible with PHP 8.2.0 beta 1
* Version 1.0 will be compatible with PHP 8.2.0 rc 1

What works
==========

The library will not be a full replacement for ``ext-random`` and total compatibility does not seem to be achievable.

* ``Random\Randomizer`` - WIP

* Engines

  * ``Random\Engine`` interface
  * ``Random\CryptoSafeEngine`` interface
  * Secure Engine: ``Random\Engine\Secure``
  * Mersenne Twister: ``Random\Engine\Mt19937``

Version 1.99.0
==============

Version 1.99.0 will be released as an empty package for PHP >= 8.2.

Known differences
=================

These differences are considered to be permanent features

Serialization
-------------

* Serialization is only compatible if done in PHP 7.4 and later.
* Serializable entities implement ``\Serializable`` for controlled serialization in PHP 7.1 - 7.3.
* Entities serialized in PHP 7.1 - 7.3 can be unserialized with the polyfill under any version of PHP but will not be
  unserializable by the native extension.
* Serialization in PHP 7.1 - 7.3 will trigger a warning.

Randomizer
----------

* `pickArrayKeys()` messes a lot with the internal structure of the PHP hash tables and therefore
  may produce different results in the userland.
  Please report if you manage to produce a case where PHP 8.2 and this library disagree.

Mt19937
-------

Generating integers with ``$max - $min >= mt_getrandmax()`` is considered undefined behavior and likely won't be fixed.

PcgOneseq128XslRr64, Xoshiro256StarStar
---------------------------------------

Currently I have no plans about implementing these.

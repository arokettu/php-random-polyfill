Compatibility Notes
###################

PHP
===

The library aims to be compatible with ``ext-random`` as released in PHP 8.2.0 and subsequent patch releases.
The library will not be a full replacement for ``ext-random`` and total compatibility does not seem to be achievable.

Version 1.99.0
==============

Version 1.99.0 is an empty package for PHP >= 8.2.
It ensures that no overhead or extra code will be used for PHP 8.2+ apps.

Known differences
=================

These differences are considered to be permanent features.
However if you know how to fix them, ideas are welcome.

Serialization
-------------

* Serialization is only compatible if done in PHP 7.4 and later.
* Serializable entities implement ``\Serializable`` for controlled serialization in PHP 7.1 - 7.3.
* Entities serialized in PHP 7.1 - 7.3 can be unserialized with the polyfill under any version of PHP but will not be
  unserializable by the native extension.
* Serialization in PHP 7.1 - 7.3 will trigger a warning.
  Silence it with ``@`` if you don't care.
* Does not throw in GH-9186_ case but also does not create a dynamic property (PHP 7.4+)
  Undefined behavior for PHP <7.4 (returns false with a warning for the given code)

.. _GH-9186: https://github.com/php/php-src/issues/9186

Randomizer
----------

* ``pickArrayKeys()`` messes a lot with the internal structure of the PHP hash tables and therefore
  may produce different results in the userland.
  Example from `Tim Düsterhus`__:

  .. code-block:: php

    <?php

    $r1 = new Random\Randomizer(new Random\Engine\Xoshiro256StarStar(1));
    $r2 = new Random\Randomizer(new Random\Engine\Xoshiro256StarStar(1));

    $a = [ 'a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5, 'f' => 6, ];
    unset($a['b']);

    $b = [ 'a' => 1, 'c' => 3, 'd' => 4, 'e' => 5, 'f' => 6, ];

    var_dump($a === $b); // bool(true)

    var_dump(
        $r1->pickArrayKeys($a, 1), // native: [ 0 => 'e' ], lib: [ 0 => 'd' ]
        $r2->pickArrayKeys($b, 1), // [ 0 => 'd' ]
    );

  The interpreter operates on the actual hash table that looks different for these arrays.
  The lib "repacks" arrays and therefore returns ``['d']`` in both cases.

.. __: https://github.com/php/doc-en/issues/1731

* using ``pickArrayKeys()`` will trigger a warning if the engine is not CryptoSafeEngine.
  Silence it with ``@`` if you don't care.

Mt19937
-------

* Generating integers with ``$max - $min >= mt_getrandmax()`` with ``MT_RAND_PHP`` is considered undefined behavior.
  This library is consistent with PHP 8.2 behavior, not the version it runs under.

Warnings
--------

For obvious reasons, native extension produces ``E_WARNING`` and the library produces ``E_USER_WARNING``.

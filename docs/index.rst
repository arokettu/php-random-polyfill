Random Extension Polyfill
#########################

|Packagist| |GitLab| |GitHub| |Bitbucket| |Gitea|

This is a polyfill for the new ``ext-random`` extension that will be released with PHP 8.2.

RFC:

* https://wiki.php.net/rfc/rng_extension
* https://wiki.php.net/rfc/random_extension_improvement

Requirements
============

* PHP 7.1
* GMP extension is strongly recommended

Installation
============

.. code-block:: bash

    composer require 'arokettu/random-polyfill'

Documentation
=============

.. toctree::
   :maxdepth: 2

   compatibility

TODO
====

* Keep updating with fixes from the upcoming betas and release 1.0.0 around PHP 8.2.0 rc 1
* Empty ``arokettu/random-polyfill`` v1.99 for PHP 8.2.0 users

License
=======

The library is available as open source under the terms of the `3-Clause BSD License`__.
See `COPYING.adoc`__ for additional licenses.

.. __: https://opensource.org/licenses/BSD-3-Clause
.. __: https://gitlab.com/sandfox/php-random-polyfill/-/blob/master/COPYING.adoc

.. |Packagist|  image:: https://img.shields.io/packagist/v/arokettu/random-polyfill.svg?style=flat-square
   :target:     https://packagist.org/packages/arokettu/random-polyfill
.. |GitHub|     image:: https://img.shields.io/badge/get%20on-GitHub-informational.svg?style=flat-square&logo=github
   :target:     https://github.com/arokettu/php-random-polyfill
.. |GitLab|     image:: https://img.shields.io/badge/get%20on-GitLab-informational.svg?style=flat-square&logo=gitlab
   :target:     https://gitlab.com/sandfox/php-random-polyfill
.. |Bitbucket|  image:: https://img.shields.io/badge/get%20on-Bitbucket-informational.svg?style=flat-square&logo=bitbucket
   :target:     https://bitbucket.org/sandfox/php-random-polyfill
.. |Gitea|      image:: https://img.shields.io/badge/get%20on-Gitea-informational.svg?style=flat-square&logo=gitea
   :target:     https://sandfox.org/sandfox/php-random-polyfill

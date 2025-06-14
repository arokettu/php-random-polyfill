Random Extension Polyfill
#########################

|Packagist| |GitLab| |GitHub| |Codeberg| |Gitea|

This is a polyfill for the new ``ext-random`` extension that was released with PHP 8.2.

Requirements
============

* PHP 7.1
* GMP extension is strongly recommended on PHP 7 and somewhat recommended on PHP 8

Installation
============

.. code-block:: bash

    composer require 'arokettu/random-polyfill'

Random Extension Documentation
==============================

On the PHP website: https://www.php.net/manual/en/book.random.php

Documentation
=============

.. toctree::
   :maxdepth: 2

   compatibility
   performance
   license
   thanks

License
=======

The library is available as open source under the terms of the `3-Clause BSD License`__.
See :ref:`license` for additional licenses.

.. __: https://opensource.org/licenses/BSD-3-Clause

.. |Packagist|  image:: https://img.shields.io/packagist/v/arokettu/random-polyfill.svg?style=flat-square
   :target:     https://packagist.org/packages/arokettu/random-polyfill
.. |GitHub|     image:: https://img.shields.io/badge/get%20on-GitHub-informational.svg?style=flat-square&logo=github
   :target:     https://github.com/arokettu/php-random-polyfill
.. |GitLab|     image:: https://img.shields.io/badge/get%20on-GitLab-informational.svg?style=flat-square&logo=gitlab
   :target:     https://gitlab.com/sandfox/php-random-polyfill
.. |Codeberg|   image:: https://img.shields.io/badge/get%20on-Codeberg-informational.svg?style=flat-square&logo=codeberg
   :target:     https://codeberg.org/sandfox/php-random-polyfill
.. |Gitea|      image:: https://img.shields.io/badge/get%20on-Gitea-informational.svg?style=flat-square&logo=gitea
   :target:     https://sandfox.org/sandfox/php-random-polyfill

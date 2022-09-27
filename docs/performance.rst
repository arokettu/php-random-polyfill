Performance Notes
#################

Well, even with GMP, it's guaranteed to be slow.
Good enough if you need a dozen numbers or so.

Performance test
================

https://github.com/arokettu/random-polyfill-perf

* Secure engine is mostly unaffected by the choice of backend (except for nextInt() case)
* PHP 7:

  * With GMP installed, all engines is approximately 400 times slower than native
  * Mersenne Twister is consistently 400 times slower than native, whether you use GMP or not
  * PCG is 100 times slower than GMP and xoshiro256** is 50 times slower than GMP
* PHP 8:

  * 100-150 times slower than native
  * GMP presence does not affect performance that much, but PCG will run twice as fast
  * JIT helps almost as much as GMP (use both for max performance)

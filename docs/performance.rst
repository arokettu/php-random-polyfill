Performance Notes
#################

Well, even with GMP, it's guaranteed to be slow.
Good enough if you need a dozen numbers or so.

Performance test
================

https://github.com/arokettu/random-polyfill-perf

* Secure engine is mostly not affected on the backend used
* With GMP installed, all engines is approximately 400 times slower than native
* Mersenne Twister is consistently 400 times slower than native, whether you use GMP or not
* PCG are 100 times slower than GMP and xoshiro256** is are 50 times slower than GMP

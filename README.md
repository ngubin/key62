# Key62
Generating a unique short key from the number.

A simple class for converting a number into a string key and back. You can use to shorten long numbers to unique keys.

<a href="https://github.com/ngubin/key62/releases"><img src="https://img.shields.io/github/release/ngubin/key62.svg?style=flat-square" alt="Latest Version"/></a>
<a href="https://packagist.org/packages/ngubin/key62"><img src="https://img.shields.io/packagist/dt/ngubin/key62.svg?style=flat-square" alt="Total Downloads"/></a>

## Example Usage

#### Creating a key from a number:

You can only encode an integer greater than or equal to zero.

``` php
use Key62\Key62;

$key62 = new Key62();
$key = $key62->encode(100000);
```

#### Set your own character set to create keys and the minimum number of characters in the key:

If you change the minimum length of the key when encoding, then do not forget to set it with the reverse decryption of the key.

``` php
use Key62\Key62;

$characters = 'wWpJbH8nIDed1Evq5OcToF2ZuXsayz7RrtP490ixSKC3GM6gYkNVhBUQmLlfAj';
$length = 4;

$key62 = new Key62($characters, $length);
$key = $key62->encode(100000);
```

#### Getting the number from the key:

You can decode the key if all of its characters are in the class set.

``` php
use Key62\Key62;

$key62 = new Key62();
$number = $key62->decode('Gh1a');
```

## License

This project is released under the MIT License.

© 2017 Nik Gubin, All rights reserved.
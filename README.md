# WT

[![Latest Stable Version](https://img.shields.io/packagist/v/magroski/wt.svg?style=flat)](https://packagist.org/packages/magroski/wt)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.1-8892BF.svg?style=flat)](https://php.net/)
[![CircleCI](https://circleci.com/gh/magroski/wt.svg?style=shield)](https://circleci.com/gh/magroski/wt)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg?style=flat)](https://github.com/magroski/wt/blob/master/LICENSE)

This library provides a quick and simple way to encode/decode WebTokens.

## Usage examples

```php
# Passing the key on the constructor
$wt = new WT('AES-256-CBC', 'abcdefgh', 'potato');

$object = new \stdClass();
$token = $wt->encode($object);

$decoded = $wt->decode($token);

# Passing the key during encode/decode
$wt = new WT('AES-256-CBC', 'xkfo39sp');

$object = new \stdClass();
$token = $wt->encode($object, 'potato');

$decoded = $wt->decode($token, 'potato');
```

PHP client for SMSLabs.pl
======
[![Licence MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![Build Status](https://travis-ci.org/ittoolspl/php-lib-smslab.svg?branch=master)](https://travis-ci.org/ittoolspl/php-lib-smslab)
[![Build Status](https://scrutinizer-ci.com/g/ittoolspl/php-lib-smslab/badges/build.png?b=master)](https://scrutinizer-ci.com/g/ittoolspl/php-lib-smslab/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ittoolspl/php-lib-smslab/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ittoolspl/php-lib-smslab/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/ittoolspl/php-lib-smslab/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/ittoolspl/php-lib-smslab/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/8d1dcd58-b1d1-4caa-8659-cb0c76402786/mini.png)](https://insight.sensiolabs.com/projects/8d1dcd58-b1d1-4caa-8659-cb0c76402786)

## Tags
 - v1.0 - deprecated tag without tests for PHP 5.6+
 - v2.0 - deprecated fully tested tag for PHP 5.6+
 - v3.0 - fully tested tag for PHP 7.0+

## Installation
The recommended way to install the library is through [Composer](http://getcomposer.org):
```sh
$ composer require ittoolspl/php-lib-smslabs
```
## Usage
**Constructor:**
```php
$sms = new \Ittoolspl\Smslabs\SmslabsClient($appKey, $secret);
```
**Send SMS:**
```php
$sms->setSenderId($numberOrSenderId)
    ->setExpirationMinutes($minutes) // optionally
    ->setSendDate($dateTime) // optionally
    ->setFlashMessage($isFlashMessage) // optionally
    ->add($number, $message)
    ->send();
```
**Show queued SMS (before send())**
```php
$sms->getSmsQueue();
```
**Get recently sent SMS status (after send()):**
```php
$sms->getSentStatus();
```
**Get available SenderId:**
```php
$sms->getAvailableSenders();
```
**Account Balance:**
```php
$sms->getAccountBalance();
```
**Check incoming SMS:**
```php
$sms->getSmsIn();
```
**Check sent SMS:**
```php
$sms->getSmsOut($offset, $limit);
```
**Check details of SMS:**
```php
$sms->getSmsDetails();
```

## Contributing
Feel free to contribute. 
If you've got any problems/ideas, please create new [issue](https://github.com/ittoolspl/php-lib-smslab/issues) or develop new pull request. 

## License
php-lib-smslab is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).

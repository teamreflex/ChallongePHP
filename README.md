# ChallongePHP

![Test](https://github.com/teamreflex/ChallongePHP/workflows/Test/badge.svg?branch=master)
[![Latest Version](https://img.shields.io/packagist/v/team-reflex/challonge-php.svg)](https://packagist.org/packages/team-reflex/challonge-php)
[![Downloads](https://img.shields.io/packagist/dt/team-reflex/challonge-php.svg)](https://packagist.org/packages/team-reflex/challonge-php)

PSR-18 compliant package for interfacing with the [Challonge] API.

## Installation
Refer to the table for PHP version compatibility:

| ChallongePHP Ver. | Compatible PHP |
|----------|-------------|
| ^3.0 | 7.4 - 8.0 |
| ^2.1 | 7.4 |
| ^2.0 | 7.4 |
| ^1.0 | 7.0 - 7.4 |

Install via composer:

```bash
composer require team-reflex/challonge-php:version
```

## Usage
As the package is PSR-18 compliant, it does not come with an HTTP client by default.

You can use a client such as Guzzle, and pass an instance of it when instantiating:

```php
$http = new GuzzleHttp\Client();
$challonge = new Challonge($http, 'api_key_here', true);
```

By default, the package maps the keys of any input, as Challonge requires its input to be in a format such as:

```php
$tournament = $challonge->createTournament([
    'tournament[name]' => 'test'
]);
```

Which means you are able to use the package without prefixing your keys:

```php
$tournament = $challonge->createTournament([
    'name' => 'test'
]);
```

You can change the third argument to `false` to disable this mapping if you would prefer to do it yourself.

Now you're ready to make requests:

```php
$tournament = $challonge->fetchTournament('challongephptest');
```

## API Updates
Challonge does not lock their API and has been consistently adding new fields to objects, thus breaking strongly typed DTOs.

As of 3.0.4, all three DTOs have been marked to ignore missing fields. If Challonge adds a new field, it will no longer throw a `DataTransferObjectError`, but the DTO will also however not contain that new field.


## Documentation
As the package is fully type-hinted, everything should be self documenting, however there is documentation in the wiki.

## Contact
- [@Reflexgg](http://twitter.com/Reflexgg)
- [@Kairuxo](http://twitter.com/Kairuxo)

[Challonge]: <http://api.challonge.com/v1>

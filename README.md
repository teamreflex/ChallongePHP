# ChallongePHP

![Test](https://github.com/teamreflex/ChallongePHP/workflows/Test/badge.svg?branch=master)
[![Latest Version](https://img.shields.io/packagist/v/team-reflex/challonge-php.svg)](https://packagist.org/packages/team-reflex/challonge-php)
[![Downloads](https://img.shields.io/packagist/dt/team-reflex/challonge-php.svg)](https://packagist.org/packages/team-reflex/challonge-php)

PSR-18 compliant package for interfacing with the [Challonge] API.

## Installation
Requires PHP 7.4 as it takes advantage of its type support.

Install via composer:

```bash
composer require team-reflex/challonge-php
```

## Usage
As the package is PSR-18 compliant, it does not come with an HTTP client by default.

You can use a client such as Guzzle, and pass an instance of it when instantiating:

```bash
$http = new GuzzleHttp\Client();
$challonge = new Challonge($http, 'api_key_here');
```

Now you're ready to make requests:

```bash
$tournament = $challonge->fetchTournament('challongephptest');
```

As the package is fully type-hinted, everything should be self documenting, however there is documentation in the wiki.

## Contact
- [@Reflexgg](http://twitter.com/Reflexgg)
- [@Kairuxo](http://twitter.com/Kairuxo)

[Challonge]: <http://api.challonge.com/v1>

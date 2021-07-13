<?php

namespace Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use PHPUnit\Framework\TestCase;
use Reflex\Challonge\Challonge;

class BaseTestCase extends TestCase
{
    protected Challonge $challonge;
    protected MockHandler $mockHandler;

    protected function setUp(): void
    {
        $this->mockHandler = new MockHandler();

        // mocking
        $http = new Client([
            'handler' => $this->mockHandler,
        ]);
        // real
        //$http = new Client();

        $this->challonge = new Challonge($http, '', true);

        parent::setUp();
    }

}

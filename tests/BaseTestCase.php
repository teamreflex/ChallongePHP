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

        // real
        //$http = new Client();
        // mocking
        $http = new Client([
            'handler' => $this->mockHandler,
        ]);

        $this->challonge = new Challonge($http, 'aafRwAi7PsS7ruJWvq8G1mL0myucjQNNOj7cTwZO');

        parent::setUp();
    }

}

<?php

namespace Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use PHPUnit\Framework\TestCase;
use Reflex\Challonge\Challonge;
use Symfony\Component\HttpClient\Psr18Client;

class BaseTestCase extends TestCase
{
    protected Challonge $challonge;
    protected MockHandler $mockHandler;

    protected function setUp(): void
    {
        $this->mockHandler = new MockHandler();

        // mocking
        $client = new Client([
            'handler' => $this->mockHandler,
        ]);
        // real
//        $client = new Psr18Client();

        $this->challonge = new Challonge($client, '', true);

        parent::setUp();
    }

}

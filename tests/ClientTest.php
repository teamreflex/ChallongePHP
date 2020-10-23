<?php

namespace Tests;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Reflex\Challonge\Challonge;

class ClientTest extends TestCase
{
    /**
     * Test if the underlying client is wrapping correctly.
     */
    public function test_client_wrapping(): void
    {
        $guzzle = new Client();
        $challonge = new Challonge($guzzle, 'asdf');

        $this->assertEquals($guzzle, $challonge->getClient()->getClient());
    }
}

<?php

namespace Tests;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use Http\Mock\Client as MockClient;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Reflex\Challonge\Challonge;
use Symfony\Component\HttpClient\Psr18Client;

class ClientTest extends TestCase
{
    /**
     * Test if the underlying client is wrapping correctly.
     */
    public function test_client_wrapping(): void
    {
        $client = new Psr18Client();
        $challonge = new Challonge($client, 'asdf');

        $this->assertEquals($client, $challonge->getClient()->getClient());
    }

    /**
     * Test PSR-18 compliance.
     */
    public function test_psr18_compliance(): void
    {
        $mockResponse = new Response(200, [], file_get_contents(__DIR__ . '/stubs/tournament_create.json'));

        // set httplug mock
        $httplug = new MockClient();
        $httplug->setDefaultResponse($mockResponse);

        // get httplug response
        $challonge = new Challonge($httplug, 'asdf');
        $httplugResponse = $challonge->createTournament();
        $this->assertSame(
            json_decode($mockResponse->getBody())->tournament->name,
            $httplugResponse->name,
        );

        // set guzzle mock
        $mockHandler = new MockHandler();
        $guzzle = new GuzzleClient([
            'handler' => $mockHandler,
        ]);
        $mockHandler->append($mockResponse);

        // get guzzle response
        $challonge = new Challonge($guzzle, 'asdf');
        $guzzleResponse = $challonge->createTournament();
        $this->assertSame(
            json_decode($mockResponse->getBody())->tournament->name,
            $guzzleResponse->name,
        );

        // check if both clients return the same response
        $this->assertSame($httplugResponse->name, $guzzleResponse->name);
    }
}

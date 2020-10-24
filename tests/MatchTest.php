<?php

namespace Tests;

use GuzzleHttp\Psr7\Response;

class MatchTest extends BaseTestCase
{
    public function test_match_index(): void
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__ . '/stubs/match_index.json')));

        $response = $this->challonge->getMatches('challongephptest');

        $this->assertCount(2, $response);
    }

    public function test_match_fetch(): void
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__ . '/stubs/match_fetch.json')));

        $response = $this->challonge->getMatch('challongephptest', 217044207);

        $this->assertEquals(217044207, $response->id);
    }
}

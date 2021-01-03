<?php

namespace Tests;

use GuzzleHttp\Psr7\Response;
use Reflex\Challonge\DTO\MatchDto;

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

    public function test_match_update(): void
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__ . '/stubs/match_fetch.json')));

        $match = MatchDto::fromResponse(
            $this->challonge->getClient(),
            json_decode(file_get_contents(__DIR__ . '/stubs/match_fetch.json'), true)['match']
        );

        $response = $match->update();

        $this->assertEquals(217044207, $response->id);
    }

    public function test_match_reopen(): void
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__ . '/stubs/match_fetch.json')));

        $match = MatchDto::fromResponse(
            $this->challonge->getClient(),
            json_decode(file_get_contents(__DIR__ . '/stubs/match_fetch.json'), true)['match']
        );

        $response = $match->reopen();

        $this->assertEquals(217044207, $response->id);
    }

    public function test_match_mark_underway(): void
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__ . '/stubs/match_fetch.json')));

        $match = MatchDto::fromResponse(
            $this->challonge->getClient(),
            json_decode(file_get_contents(__DIR__ . '/stubs/match_fetch.json'), true)['match']
        );

        $response = $match->markAsUnderway();

        $this->assertEquals(217044207, $response->id);
    }

    public function test_match_unmark_underway(): void
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__ . '/stubs/match_fetch.json')));

        $match = MatchDto::fromResponse(
            $this->challonge->getClient(),
            json_decode(file_get_contents(__DIR__ . '/stubs/match_fetch.json'), true)['match']
        );

        $response = $match->unmarkAsUnderway();

        $this->assertEquals(217044207, $response->id);
    }
}

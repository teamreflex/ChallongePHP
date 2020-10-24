<?php

namespace Tests;

use GuzzleHttp\Psr7\Response;

class ParticipantTest extends BaseTestCase
{
    public function test_participant_index(): void
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__ . '/stubs/participant_index.json')));

        $response = $this->challonge->getParticipants('challongephptest');

        $this->assertCount(3, $response);
    }

    public function test_tournament_fetch(): void
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__ . '/stubs/participant_fetch.json')));

        $response = $this->challonge->getParticipant('challongephptest', 132810231);

        $this->assertEquals('Team 1', $response->display_name);
    }
}

<?php

namespace Tests;

use GuzzleHttp\Psr7\Response;
use Reflex\Challonge\DTO\Participant;

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

    public function test_participant_randomize(): void
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__ . '/stubs/participant_index.json')));

        $response = $this->challonge->randomizeParticipants('challongephptest');

        $this->assertCount(3, $response);
    }

    public function test_participant_update(): void
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__ . '/stubs/participant_fetch.json')));

        $participant = Participant::fromResponse(
            $this->challonge->getClient(),
            json_decode(file_get_contents(__DIR__ . '/stubs/participant_fetch.json'), true)['participant']
        );

        $response = $participant->update();

        $this->assertEquals('Team 1', $response->display_name);
    }

    public function test_participant_delete(): void
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__ . '/stubs/participant_fetch.json')));

        $participant = Participant::fromResponse(
            $this->challonge->getClient(),
            json_decode(file_get_contents(__DIR__ . '/stubs/participant_fetch.json'), true)['participant']
        );

        $response = $participant->delete();

        $this->assertEquals('Team 1', $response->display_name);
    }

    public function test_participant_checkin(): void
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__ . '/stubs/participant_fetch.json')));

        $participant = Participant::fromResponse(
            $this->challonge->getClient(),
            json_decode(file_get_contents(__DIR__ . '/stubs/participant_fetch.json'), true)['participant']
        );

        $response = $participant->checkin();

        $this->assertEquals('Team 1', $response->display_name);
    }

    public function test_participant_undo_checkin(): void
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__ . '/stubs/participant_fetch.json')));

        $participant = Participant::fromResponse(
            $this->challonge->getClient(),
            json_decode(file_get_contents(__DIR__ . '/stubs/participant_fetch.json'), true)['participant']
        );

        $response = $participant->undoCheckin();

        $this->assertEquals('Team 1', $response->display_name);
    }
}

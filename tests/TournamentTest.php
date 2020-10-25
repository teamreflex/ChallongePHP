<?php

namespace Tests;

use GuzzleHttp\Psr7\Response;
use Reflex\Challonge\DTO\Tournament;

class TournamentTest extends BaseTestCase
{
    public function test_tournament_index(): void
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__ . '/stubs/tournament_index.json')));

        $response = $this->challonge->getTournaments();

        $this->assertCount(2, $response);
    }

    public function test_tournament_create(): void
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__ . '/stubs/tournament_create.json')));

        $response = $this->challonge->createTournament();

        $this->assertEquals('challongephp test', $response->name);
    }

    public function test_tournament_fetch(): void
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__ . '/stubs/tournament_fetch.json')));

        $response = $this->challonge->fetchTournament('9044420');

        $this->assertEquals('challongephp test', $response->name);
    }

    public function test_tournament_delete(): void
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__ . '/stubs/tournament_fetch.json')));

        $response = $this->challonge->fetchTournament('9044420');

        $this->assertEquals('challongephp test', $response->name);
    }

    public function test_tournament_start(): void
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__ . '/stubs/tournament_fetch.json')));

        $tournament = Tournament::fromResponse(
            $this->challonge->getClient(),
            json_decode(file_get_contents(__DIR__ . '/stubs/tournament_start.json'), true)['tournament']
        );

        $response = $tournament->start();

        $this->assertEquals('underway', $response->state);
    }

    public function test_tournament_finalize(): void
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__ . '/stubs/tournament_finalize2.json')));

        $tournament = Tournament::fromResponse(
            $this->challonge->getClient(),
            json_decode(file_get_contents(__DIR__ . '/stubs/tournament_finalize1.json'), true)['tournament']
        );

        $response = $tournament->finalize();

        $this->assertEquals('complete', $response->state);
    }

    public function test_tournament_reset(): void
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__ . '/stubs/tournament_fetch.json')));

        $tournament = Tournament::fromResponse(
            $this->challonge->getClient(),
            json_decode(file_get_contents(__DIR__ . '/stubs/tournament_fetch.json'), true)['tournament']
        );

        $response = $tournament->reset();

        $this->assertEquals('challongephp test', $response->name);
    }

    public function test_tournament_update(): void
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__ . '/stubs/tournament_fetch.json')));

        $tournament = Tournament::fromResponse(
            $this->challonge->getClient(),
            json_decode(file_get_contents(__DIR__ . '/stubs/tournament_fetch.json'), true)['tournament']
        );

        $response = $tournament->update();

        $this->assertEquals('challongephp test', $response->name);
    }

    public function test_tournament_delete_self(): void
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__ . '/stubs/tournament_fetch.json')));

        $tournament = Tournament::fromResponse(
            $this->challonge->getClient(),
            json_decode(file_get_contents(__DIR__ . '/stubs/tournament_fetch.json'), true)['tournament']
        );

        $response = $tournament->delete();

        $this->assertEquals('challongephp test', $response->name);
    }

    public function test_tournament_clear(): void
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__ . '/stubs/tournament_fetch.json')));

        $tournament = Tournament::fromResponse(
            $this->challonge->getClient(),
            json_decode(file_get_contents(__DIR__ . '/stubs/tournament_fetch.json'), true)['tournament']
        );

        $response = $tournament->clear();

        $this->assertEquals('challongephp test', $response->name);
    }

    public function test_tournament_process_checkins(): void
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__ . '/stubs/tournament_fetch.json')));

        $tournament = Tournament::fromResponse(
            $this->challonge->getClient(),
            json_decode(file_get_contents(__DIR__ . '/stubs/tournament_start.json'), true)['tournament']
        );

        $response = $tournament->processCheckins();

        $this->assertEquals('challongephp test', $response->name);
    }

    public function test_tournament_abort_checkins(): void
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__ . '/stubs/tournament_fetch.json')));

        $tournament = Tournament::fromResponse(
            $this->challonge->getClient(),
            json_decode(file_get_contents(__DIR__ . '/stubs/tournament_start.json'), true)['tournament']
        );

        $response = $tournament->abortCheckins();

        $this->assertEquals('challongephp test', $response->name);
    }

    public function test_tournament_add_participant(): void
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__ . '/stubs/participant_fetch.json')));

        $tournament = Tournament::fromResponse(
            $this->challonge->getClient(),
            json_decode(file_get_contents(__DIR__ . '/stubs/tournament_fetch.json'), true)['tournament']
        );

        $response = $tournament->addParticipant();

        $this->assertEquals('Team 1', $response->display_name);
    }

    public function test_tournament_bulkadd_participant(): void
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__ . '/stubs/participant_index.json')));

        $tournament = Tournament::fromResponse(
            $this->challonge->getClient(),
            json_decode(file_get_contents(__DIR__ . '/stubs/tournament_fetch.json'), true)['tournament']
        );

        $response = $tournament->bulkAddParticipant();

        $this->assertCount(3, $response);
    }

    public function test_tournament_delete_participant(): void
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__ . '/stubs/participant_fetch.json')));

        $tournament = Tournament::fromResponse(
            $this->challonge->getClient(),
            json_decode(file_get_contents(__DIR__ . '/stubs/tournament_start.json'), true)['tournament']
        );

        $response = $tournament->deleteParticipant(1);

        $this->assertEquals('Team 1', $response->display_name);
    }

    public function test_tournament_update_participant(): void
    {
        $this->mockHandler->append(new Response(200, [], file_get_contents(__DIR__ . '/stubs/participant_fetch.json')));

        $tournament = Tournament::fromResponse(
            $this->challonge->getClient(),
            json_decode(file_get_contents(__DIR__ . '/stubs/tournament_start.json'), true)['tournament']
        );

        $response = $tournament->updateParticipant(1);

        $this->assertEquals('Team 1', $response->display_name);
    }
}

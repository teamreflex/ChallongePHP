<?php

namespace Reflex\Challonge;

use Illuminate\Support\Collection;
use Psr\Http\Client\ClientInterface;
use Reflex\Challonge\DTO\Match;
use Reflex\Challonge\DTO\Tournament;
use Reflex\Challonge\DTO\Participant;

class Challonge
{
    /**
     * ChallongePHP version.
     * Required to pass into Challonge.
     */
    protected string $version = '2.0.0';

    /**
     * PSR-18 compatible HTTP client wrapped in our wrapper.
     * @var ClientWrapper
     */
    protected ClientWrapper $client;

    /**
     * Challonge constructor.
     * @param ClientInterface $client
     * @param string $key
     */
    public function __construct(ClientInterface $client, string $key = '')
    {
        $this->client = new ClientWrapper($client, $key, $this->version);
    }

    /**
     * @return ClientWrapper
     */
    public function getClient(): ClientWrapper
    {
        return $this->client;
    }

    /**
     * Retrieve a set of tournaments created with your account.
     * @return Collection
     * @throws Exceptions\InvalidFormatException
     * @throws Exceptions\NotFoundException
     * @throws Exceptions\ServerException
     * @throws Exceptions\UnauthorizedException
     * @throws Exceptions\UnexpectedErrorException
     * @throws Exceptions\ValidationException
     * @throws \JsonException
     */
    public function getTournaments(): Collection {
        $response = $this->client->request('get', 'tournaments');
        return Collection::make($response)
            ->map(fn (array $tournament) => Tournament::fromResponse($this->client, $tournament['tournament']));
    }

    /**
     * Create a new tournament.
     * @param array $options
     * @return Tournament
     * @throws Exceptions\InvalidFormatException
     * @throws Exceptions\NotFoundException
     * @throws Exceptions\ServerException
     * @throws Exceptions\UnauthorizedException
     * @throws Exceptions\UnexpectedErrorException
     * @throws Exceptions\ValidationException
     * @throws \JsonException
     */
    public function createTournament(array $options = []): Tournament
    {
        $response = $this->client->request('post', 'tournaments', $options);
        return Tournament::fromResponse($this->client, $response['tournament']);
    }

    /**
     * Retrieve a single tournament record.
     * @param string $tournament
     * @return Tournament
     * @throws Exceptions\InvalidFormatException
     * @throws Exceptions\NotFoundException
     * @throws Exceptions\ServerException
     * @throws Exceptions\UnauthorizedException
     * @throws Exceptions\UnexpectedErrorException
     * @throws Exceptions\ValidationException
     * @throws \JsonException
     */
    public function fetchTournament(string $tournament): Tournament
    {
        $response = $this->client->request('get', "tournaments/{$tournament}");
        return Tournament::fromResponse($this->client, $response['tournament']);
    }

    /**
     * Retrieve a tournament's participant list.
     * @param string $tournament
     * @return Collection
     * @throws Exceptions\InvalidFormatException
     * @throws Exceptions\NotFoundException
     * @throws Exceptions\ServerException
     * @throws Exceptions\UnauthorizedException
     * @throws Exceptions\UnexpectedErrorException
     * @throws Exceptions\ValidationException
     * @throws \JsonException
     */
    public function getParticipants(string $tournament): Collection
    {
        $response = $this->client->request('get', "tournaments/{$tournament}/participants");
        return Collection::make($response)
            ->map(fn (array $participant) => Participant::fromResponse($this->client, $participant['participant']));
    }

    /**
     * Randomize seeds among participants.
     * @param string $tournament
     * @return Collection
     * @throws Exceptions\InvalidFormatException
     * @throws Exceptions\NotFoundException
     * @throws Exceptions\ServerException
     * @throws Exceptions\UnauthorizedException
     * @throws Exceptions\UnexpectedErrorException
     * @throws Exceptions\ValidationException
     * @throws \JsonException
     */
    public function randomizeParticipants(string $tournament): Collection
    {
        $response = $this->client->request('post', "tournaments/{$tournament}/participants/randomize");
        return Collection::make($response)
            ->map(fn (array $participant) => Participant::fromResponse($this->client, $participant['participant']));
    }

    /**
     * Retrieve a single participant record for a tournament.
     * @param string $tournament
     * @param int $participant
     * @return Participant
     * @throws Exceptions\InvalidFormatException
     * @throws Exceptions\NotFoundException
     * @throws Exceptions\ServerException
     * @throws Exceptions\UnauthorizedException
     * @throws Exceptions\UnexpectedErrorException
     * @throws Exceptions\ValidationException
     * @throws \JsonException
     */
    public function getParticipant(string $tournament, int $participant): Participant
    {
        $response = $this->client->request('post', "tournaments/{$tournament}/participants/{$participant}");
        return Participant::fromResponse($this->client, $response['participant']);
    }

    /**
     * Retrieve a tournament's match list.
     * @param string $tournament
     * @return Collection
     * @throws Exceptions\InvalidFormatException
     * @throws Exceptions\NotFoundException
     * @throws Exceptions\ServerException
     * @throws Exceptions\UnauthorizedException
     * @throws Exceptions\UnexpectedErrorException
     * @throws Exceptions\ValidationException
     * @throws \JsonException
     */
    public function getMatches(string $tournament): Collection
    {
        $response = $this->client->request('get', "tournaments/{$tournament}/matches");
        return Collection::make($response)
            ->map(fn (array $match) => Match::fromResponse($this->client, $match['match']));
    }

    /**
     * Retrieve a single match record for a tournament.
     * @param string $tournament
     * @param int $match
     * @return Match
     * @throws Exceptions\InvalidFormatException
     * @throws Exceptions\NotFoundException
     * @throws Exceptions\ServerException
     * @throws Exceptions\UnauthorizedException
     * @throws Exceptions\UnexpectedErrorException
     * @throws Exceptions\ValidationException
     * @throws \JsonException
     */
    public function getMatch(string $tournament, int $match): Match
    {
        $response = $this->client->request('get', "tournaments/{$tournament}/matches/{$match}");
        return Match::fromResponse($this->client, $response['match']);
    }
}

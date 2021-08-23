<?php

namespace Reflex\Challonge;

use Illuminate\Support\Collection;
use Psr\Http\Client\ClientInterface;
use Reflex\Challonge\DTO\MatchDto;
use Reflex\Challonge\DTO\Tournament;
use Reflex\Challonge\DTO\Participant;

class Challonge
{
    /**
     * ChallongePHP version.
     * Required to pass into Challonge.
     */
    protected string $version = '3.1';

    /**
     * PSR-18 compatible HTTP client wrapped in our wrapper.
     * @var ClientWrapper
     */
    protected ClientWrapper $client;

    /**
     * Challonge constructor.
     * @param ClientInterface $client
     * @param string $key
     * @param bool $mapOptions
     */
    public function __construct(ClientInterface $client, string $key = '', bool $mapOptions = true)
    {
        $this->client = new ClientWrapper($client, $key, $this->version, $mapOptions);
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
        $response = $this->client->request('GET', 'tournaments');
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
        $response = $this->client->request('POST', 'tournaments', $this->client->mapOptions($options, 'tournament'));
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
        $response = $this->client->request('GET', "tournaments/{$tournament}");
        return Tournament::fromResponse($this->client, $response['tournament']);
    }

    /**
     * Delete a tournament and all it's records.
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
    public function deleteTournament(string $tournament): Tournament
    {
        $response = $this->client->request('DELETE', "tournaments/{$tournament}");
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
        $response = $this->client->request('GET', "tournaments/{$tournament}/participants");
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
        $response = $this->client->request('POST', "tournaments/{$tournament}/participants/randomize");
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
        $response = $this->client->request('GET', "tournaments/{$tournament}/participants/{$participant}");
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
        $response = $this->client->request('GET', "tournaments/{$tournament}/matches");
        return Collection::make($response)
            ->map(fn (array $match) => MatchDto::fromResponse($this->client, $match['match']));
    }

    /**
     * Retrieve a single match record for a tournament.
     * @param string $tournament
     * @param int $match
     * @return MatchDto
     * @throws Exceptions\InvalidFormatException
     * @throws Exceptions\NotFoundException
     * @throws Exceptions\ServerException
     * @throws Exceptions\UnauthorizedException
     * @throws Exceptions\UnexpectedErrorException
     * @throws Exceptions\ValidationException
     * @throws \JsonException
     */
    public function getMatch(string $tournament, int $match): MatchDto
    {
        $response = $this->client->request('GET', "tournaments/{$tournament}/matches/{$match}");
        return MatchDto::fromResponse($this->client, $response['match']);
    }

    /**
     * Retrieve a leaderboard listing for a tournament.
     *
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
    public function getStandings(string $tournament): Collection
    {
        $participants = collect($this->getParticipants($tournament));
        $matches = collect($this->getMatches($tournament));
        $matchesComplete = count($matches->where('state', 'complete'));
        $result['progress'] = (($matchesComplete > 0) ? round(($matchesComplete / count($matches) * 100)) : 0);
        $group = [];
        foreach ($participants as $team) {
            $teamWithResults = $this->getStanding($team, $matches);
            $finals[] = $teamWithResults->final['results'];
            if (! empty($teamWithResults->groups[0])) {
                $group[] = $teamWithResults->groups[0]['results'];
            }
        }
        ((!empty($finals))? $result['final'] = collect($finals)->sortByDesc('win') : $finals = null);
        ((!empty($group))? $result['groups'] = collect($group)->sortByDesc('win') : $group = null);
        return collect($result);
    }


    /**
     * Get standing for a participant across all groups and matches.
     *
     * @param  Participant $participant
     * @param  Collection $matches
     * @return Participant
     **/
    private function getStanding(Participant $participant, Collection $matches): Participant
    {
        $participantGroups = [];
        foreach ($participant->group_player_ids as $playerGroupId) {
            $data = $matches->filter(function ($item) use ($playerGroupId) {
                  return in_array($playerGroupId, [$item->player1_id, $item->player2_id], true);
            });

            $participantGroup['matches'] = $data;
            $participantGroup['results'] = $this->matchResults($data, $playerGroupId, $participant->name);
            $participantGroups[] = $participantGroup;
        }

        $participantFinal['matches'] = $matches->filter(function ($item) use ($participant) {
            return (($item->player1_id === $participant->id) || ($item->player2_id === $participant->id));
        });

        $participantFinal['results'] = $this->matchResults($participantFinal['matches'], $participant->id, $participant->name);
        $participant->groups = $participantGroups;
        $participant->final = $participantFinal;

        return $participant;
    }

    /**
     * Get match results for a given player.
     *
     * @param Collection $matches
     * @param int $playerId
     * @param string $participantName
     * @return Collection
     */
    private function matchResults(Collection $matches, int $playerId, string $participantName): Collection
    {
        $result = [
            'win' => 0,
            'lose' => 0,
            'tie' => 0,
            'pts' => 0,
            'history' => [],
            'name' => $participantName,
            'id' => $playerId,
        ];

        foreach ($matches as $match) {
            if ($match->winner_id === $playerId) {
                $result['win'] += 1;
                $result['history'][] = "W";
            }
            if ($match->loser_id === $playerId) {
                $result['lose'] += 1;
                $result['history'][] = "L";
            }
            if ($match->loser_id === null) {
                $result['tie'] += 1;
                $result['history'][] = "T";
            }
            $pts = $this->getMatchPts($match, $playerId);
            $result['pts'] += $pts->where('type', 'player')->pluck('score')->first();
        }

        return collect($result);
    }

    /**
     * Get match points for a given user.
     *
     * @param  MatchDto $match
     * @param  int $playerId
     * @return Collection
     **/
    private function getMatchPts(MatchDto $match, int $playerId): Collection
    {
        $playerScore = 0;
        $scores = [0, 0];
        if (! empty($match->scores_csv)){
            $scores = explode("-", $match->scores_csv);
            sort($scores);
        }

        if ($match->loser_id === $playerId) {
            $playerScore = $scores[0];
        }
        if ($match->winner_id === $playerId) {
            $playerScore = $scores[1];
        }
        if ($match->loser_id === null) {
            $playerScore = $scores[0];
        }

        $result[] = ['type' => 'loser', 'id' => $match->loser_id, 'score' => $scores[0]];
        $result[] = ['type' => 'winner', 'id' => $match->winner_id, 'score' => $scores[1]];
        $result[] = ['type' => 'player', 'id' => $playerId, 'score' => $playerScore];

        return collect($result);
    }
}

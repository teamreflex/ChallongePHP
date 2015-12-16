<?php

namespace Reflex\Challonge;

use GuzzleHttp\Client;
use Reflex\Challonge\Models\Match;
use Reflex\Challonge\Models\Tournament;
use Reflex\Challonge\Models\Participant;
use Reflex\Challonge\Exceptions\ServerException;
use Reflex\Challonge\Exceptions\NotFoundException;
use Reflex\Challonge\Exceptions\ValidationException;
use Reflex\Challonge\Exceptions\UnauthorizedException;
use Reflex\Challonge\Exceptions\InvalidFormatException;

class Challonge
{
    /**
     * ChallongePHP version.
     */
    const VERSION = '1.0';

    /**
     * Challonge API key.
     *
     * @var string
     */
    private $api_key;

    /**
     * Instantiate an instance with the API key.
     *
     * @param string $api_key
     */
    public function __construct($api_key = '')
    {
        $this->api_key = $api_key;
    }

    /**
     * Build any headers the requests need.
     *
     * @return array
     */
    private function buildHeaders()
    {
        return [
            'User-Agent' => 'ChallongePHP/' . self::VERSION . ' ChallongePHP (https://github.com/teamreflex/ChallongePHP, ' . self::VERSION . ')'
        ];
    }

    /**
     * Base function for all API requests.
     *
     * @param  string $path
     * @param  array  $params
     * @param  string $method
     * @return GuzzleHttp\Psr7\Response
     */
    private function makeCall($path, $params = [], $method = 'get')
    {
        if (empty($this->api_key)) {
            throw new UnauthorizedException('Must set an API key.');
        }

        $base_uri = "https://api.challonge.com/v1/{$path}.json";
        $client = new Client();

        $response = $client->request($method, $base_uri, [
            'query' => [
                'api_key' => $this->api_key,
            ],
            'headers' => $this->buildHeaders(),
            'http_errors' => false,
        ]);

        return $this->handleErrors($response);
    }

    /**
     * Handles the response and throws errors accordingly.
     *
     * @param $response GuzzleHttp\Psr7\Response
     * @return stdClass
     */
    private function handleErrors($response)
    {
        switch ($response->getStatusCode()) {
            case 200:
                return json_decode($response->getBody());
                break;
            case 401:
                throw new UnauthorizedException('Unauthorized (Invalid API key or insufficient permissions)');
                break;
            case 404:
                throw new NotFoundException('Object not found within your account scope');
                break;
            case 406:
                throw new InvalidFormatException('Requested format is not supported - request JSON or XML only');
                break;
            case 422:
                throw new ValidationException('Validation error(s) for create or update method');
                break;
            case 500:
                throw new ServerException('Something went wrong on Challonge\'s end');
                break;
            default:
                $errors = json_decode($response->getBody())->errors;
                throw new UnexpectedErrorException($errors);
                break;
        }
    }

    /**
     * Retrieve a set of tournaments created with your account.
     *
     * @return array
     */
    public function getTournaments() {
        $response = $this->makeCall('tournaments');

        $tournaments = [];
        foreach ($response as $tourney) {
            $tournaments[] = new Tournament($tourney->tournament);
        }

        return $tournaments;
    }

    /**
     * Retrieve a single tournament record created with your account.
     *
     * @param  string $tournament
     * @return Tournament
     */
    public function getTournament($tournament)
    {
        $response = $this->makeCall("tournaments/{$tournament}");
        return new Tournament($response->tournament);
    }

    /**
     * Retrieve a tournament's participant list.
     *
     * @param  string $tournament
     * @return array
     */
    public function getParticipants($tournament)
    {
        $response = $this->makeCall("tournaments/{$tournament}/participants");

        $participants = [];
        foreach ($response as $team) {
            $participants[] = new Participant($team->participant);
        }

        return $participants;
    }

    /**
     * Retrieve a single participant record for a tournament.
     *
     * @param  string $tournament
     * @param  string $participant
     * @return array
     */
    public function getParticipant($tournament, $participant)
    {
        $response = $this->makeCall("tournaments/{$tournament}/participants/{$participant}");

        $participant = new Participant($response->participant);

        return $participant;
    }

    /**
     * Retrieve a tournament's match list.
     *
     * @param  string $tournament
     * @return array
     */
    public function getMatches($tournament)
    {
        $response = $this->makeCall("tournaments/{$tournament}/matches");

        $matches = [];
        foreach ($response as $match) {
            $matches[] = new Match($match->match);
        }

        return $matches;
    }

    /**
     * Retrieve a single match record for a tournament.
     *
     * @param  string $tournament
     * @param  string $match
     * @return array
     */
    public function getMatch($tournament, $match)
    {
        $response = $this->makeCall("tournaments/{$tournament}/matches/{$match}");

        $match = new Match($response->match);

        return $match;
    }
}

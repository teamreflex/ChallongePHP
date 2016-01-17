<?php

namespace Reflex\Challonge;

use Reflex\Challonge\Models\Match;
use Reflex\Challonge\Helpers\Guzzle;
use Reflex\Challonge\Models\Tournament;
use Reflex\Challonge\Models\Participant;

class Challonge
{
    /**
     * ChallongePHP version.
     */
    const VERSION = '1.0.2';

    /**
     * Instantiate an instance with the API key.
     *
     * @param string $api_key
     */
    public function __construct($api_key = '')
    {
        @define("CHALLONGE_VERSION", self::VERSION);
        @define("CHALLONGE_KEY", $api_key);
    }

    /**
     * Retrieve a set of tournaments created with your account.
     *
     * @return array
     */
    public function getTournaments() {
        $response = Guzzle::get('tournaments');

        $tournaments = [];
        foreach ($response as $tourney) {
            $tournaments[] = new Tournament($tourney->tournament);
        }

        return $tournaments;
    }

    /**
     * Create a new tournament.
     *
     * @param  array $params
     * @return Tournament
     */
    public function createTournament($params)
    {
        $response = Guzzle::post("tournaments", $params);
        return new Tournament($response->tournament);
    }

    /**
     * Retrieve a single tournament record created with your account.
     *
     * @param  string $tournament
     * @return Tournament
     */
    public function getTournament($tournament)
    {
        $response = Guzzle::get("tournaments/{$tournament}");
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
        $response = Guzzle::get("tournaments/{$tournament}/participants");

        $participants = [];
        foreach ($response as $team) {
            $participant = new Participant($team->participant);
            $participant->tournament_slug = $tournament;
            $participants[] = $participant;
        }

        return $participants;
    }

    /**
     * Randomize seeds among participants.
     *
     * @param  string $tournament
     * @return array
     */
    public function randomizeParticipants($tournament)
    {
        $response = Guzzle::post("tournaments/{$tournament}/participants/randomize");

        $participants = [];
        foreach ($response as $team) {
            $participant = new Participant($team->participant);
            $participant->tournament_slug = $tournament;
            $participants[] = $participant;
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
        $response = Guzzle::get("tournaments/{$tournament}/participants/{$participant}");

        $participant = new Participant($response->participant);
        $participant->tournament_slug = $tournament;

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
        $response = Guzzle::get("tournaments/{$tournament}/matches");

        $matches = [];
        foreach ($response as $match) {
            $matchModel = new Match($match->match);
            $matchModel->tournament_slug = $tournament;
            $matches[] = $matchModel;
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
        $response = Guzzle::get("tournaments/{$tournament}/matches/{$match}");

        $match = new Match($response->match);
        $match->tournament_slug = $tournament;

        return $match;
    }
}

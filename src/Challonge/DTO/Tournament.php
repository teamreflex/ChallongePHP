<?php

namespace Reflex\Challonge\DTO;

use Reflex\Challonge\DtoClientTrait;
use Reflex\Challonge\Exceptions\StillRunningException;
use Reflex\Challonge\Exceptions\AlreadyStartedException;
use Spatie\DataTransferObject\DataTransferObject;

class Tournament extends DataTransferObject
{
    use DtoClientTrait;

    public bool $accept_attachments;
    public bool $allow_participant_match_reporting;
    public bool $anonymous_voting;
    public ?int $category;
    public ?int $check_in_duration;
    public ?string $completed_at;
    public ?string $created_at;
    public bool $created_by_api;
    public bool $credit_capped;
    public string $description;
    public ?int $game_id;
    public bool $group_stages_enabled;
    public bool $hide_forum;
    public bool $hide_seeds;
    public bool $hold_third_place_match;
    public int $id;
    public int $max_predictions_per_user;
    public string $name;
    public bool $notify_users_when_matches_open;
    public bool $notify_users_when_the_tournament_ends;
    public bool $open_signup;
    public int $participants_count;
    public int $prediction_method;
    public ?string $predictions_opened_at;
    public bool $private;
    public int $progress_meter;
    public string $pts_for_bye;
    public string $pts_for_game_tie;
    public string $pts_for_game_win;
    public string $pts_for_match_tie;
    public string $pts_for_match_win;
    public bool $quick_advance;
    public string $ranked_by;
    public bool $require_score_agreement;
    public string $rr_pts_for_game_tie;
    public string $rr_pts_for_game_win;
    public string $rr_pts_for_match_tie;
    public string $rr_pts_for_match_win;
    public bool $sequential_pairings;
    public bool $show_rounds;
    public ?int $signup_cap;
    public ?string $start_at;
    public ?string $started_at;
    public ?string $started_checking_in_at;
    public string $state;
    public int $swiss_rounds;
    public bool $teams;
    public array $tie_breaks;
    public string $tournament_type;
    public ?string $updated_at;
    public string $url;
    public ?string $description_source;
    public ?string $subdomain;
    public ?string $full_challonge_url;
    public ?string $live_image_url;
    public ?string $sign_up_url;
    public bool $review_before_finalizing;
    public bool $accepting_predictions;
    public bool $participants_locked;
    public ?string $game_name;
    public bool $participants_swappable;
    public bool $team_convertable;
    public bool $group_stages_were_started;

    /**
     * Start a tournament, opening up first round matches for score reporting.
     * @return Tournament
     * @throws AlreadyStartedException
     * @throws \JsonException
     * @throws \Reflex\Challonge\Exceptions\InvalidFormatException
     * @throws \Reflex\Challonge\Exceptions\NotFoundException
     * @throws \Reflex\Challonge\Exceptions\ServerException
     * @throws \Reflex\Challonge\Exceptions\UnauthorizedException
     * @throws \Reflex\Challonge\Exceptions\UnexpectedErrorException
     * @throws \Reflex\Challonge\Exceptions\ValidationException
     */
    public function start(): Tournament
    {
        if ($this->state !== 'pending') {
            throw new AlreadyStartedException('Tournament is already underway.');
        }

        $response = $this->client->request('post', "tournaments/{$this->id}/start");
        return self::fromResponse($this->client, $response['tournament']);
    }

    /**
     * Finalize a tournament that has had all match scores submitted, rendering its results permanent.
     * @return Tournament
     * @throws StillRunningException
     * @throws \JsonException
     * @throws \Reflex\Challonge\Exceptions\InvalidFormatException
     * @throws \Reflex\Challonge\Exceptions\NotFoundException
     * @throws \Reflex\Challonge\Exceptions\ServerException
     * @throws \Reflex\Challonge\Exceptions\UnauthorizedException
     * @throws \Reflex\Challonge\Exceptions\UnexpectedErrorException
     * @throws \Reflex\Challonge\Exceptions\ValidationException
     */
    public function finalize(): Tournament
    {
        if ($this->state !== 'awaiting_review') {
            throw new StillRunningException('Tournament is still running.');
        }

        $response = $this->client->request('post', "tournaments/{$this->id}/finalize");
        return self::fromResponse($this->client, $response['tournament']);
    }

    /**
     * Reset a tournament, clearing all of its scores and attachments.
     * @return Tournament
     * @throws \JsonException
     * @throws \Reflex\Challonge\Exceptions\InvalidFormatException
     * @throws \Reflex\Challonge\Exceptions\NotFoundException
     * @throws \Reflex\Challonge\Exceptions\ServerException
     * @throws \Reflex\Challonge\Exceptions\UnauthorizedException
     * @throws \Reflex\Challonge\Exceptions\UnexpectedErrorException
     * @throws \Reflex\Challonge\Exceptions\ValidationException
     */
    public function reset(): Tournament
    {
        $response = $this->client->request('post', "tournaments/{$this->id}/reset");
        return self::fromResponse($this->client, $response['tournament']);
    }

    /**
     * Update a tournament's attributes.
     * @param array $options
     * @return Tournament
     * @throws \JsonException
     * @throws \Reflex\Challonge\Exceptions\InvalidFormatException
     * @throws \Reflex\Challonge\Exceptions\NotFoundException
     * @throws \Reflex\Challonge\Exceptions\ServerException
     * @throws \Reflex\Challonge\Exceptions\UnauthorizedException
     * @throws \Reflex\Challonge\Exceptions\UnexpectedErrorException
     * @throws \Reflex\Challonge\Exceptions\ValidationException
     */
    public function update(array $options = []): Tournament
    {
        $response = $this->client->request('put', "tournaments/{$this->id}", $options);
        return self::fromResponse($this->client, $response['tournament']);
    }

    /**
     * Deletes a tournament along with all its associated records.
     * @return bool
     * @throws \JsonException
     * @throws \Reflex\Challonge\Exceptions\InvalidFormatException
     * @throws \Reflex\Challonge\Exceptions\NotFoundException
     * @throws \Reflex\Challonge\Exceptions\ServerException
     * @throws \Reflex\Challonge\Exceptions\UnauthorizedException
     * @throws \Reflex\Challonge\Exceptions\UnexpectedErrorException
     * @throws \Reflex\Challonge\Exceptions\ValidationException
     */
    public function delete(): bool
    {
        $response = $this->client->request('delete', "tournaments/{$this->id}");
        // TODO: validate the response
        return true;
    }

    /**
     * Add a participant to a tournament (up until it is started).
     *
     * @param array $params
     */
    public function addParticipant($params = [])
    {
        $response = Guzzle::post("tournaments/{$this->id}/participants", $params);

        $participant = new Participant($response->participant);
        $participant->tournament_slug = $this->id;

        return $participant;
    }

    /**
     * Bulk add participants to a tournament (up until it is started).
     *
     * @param  array $params
     * @return array
     */
    public function bulkAddParticipant($params = [])
    {
        $response = Guzzle::post("tournaments/{$this->id}/participants/bulk_add", $params);

        $participants = [];
        foreach ($response->participant as $participant) {
            $participant = new Participant($participant);
            $participant->tournament_slug = $tournament;
            $participants[] = $participant;
        }

        return $participants;
    }
}

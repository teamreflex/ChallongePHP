<?php

namespace Reflex\Challonge\DTO;

use Reflex\Challonge\DtoClientTrait;
use Spatie\DataTransferObject\DataTransferObject;

class Match extends DataTransferObject
{
    use DtoClientTrait;

    public ?int $attachment_count;
    public ?string $completed_at;
    public string $created_at;
    public ?bool $forfeited;
    public ?int $group_id;
    public bool $has_attachment;
    public int $id;
    public string $identifier;
    public ?string $location;
    public ?int $loser_id;
    public ?string $open_graph_image_file_name;
    public ?string $open_graph_image_content_type;
    public ?string $open_graph_image_file_size;
    public bool $optional;
    public int $player1_id;
    public bool $player1_is_prereq_match_loser;
    public ?int $player1_prereq_match_id;
    public ?int $player1_votes;
    public ?int $player2_id;
    public bool $player2_is_prereq_match_loser;
    public ?int $player2_prereq_match_id;
    public ?int $player2_votes;
    public int $round;
    public ?int $rushb_id;
    public ?string $scheduled_time;
    public ?string $started_at;
    public string $state;
    public $suggested_play_order;
    public int $tournament_id;
    public ?string $underway_at;
    public string $updated_at;
    public ?int $winner_id;
    public ?string $prerequisite_match_ids_csv;
    public ?string $scores_csv;

    /**
     * Update/submit the score(s) for a match.
     * @param array $options
     * @return Match
     * @throws \JsonException
     * @throws \Reflex\Challonge\Exceptions\InvalidFormatException
     * @throws \Reflex\Challonge\Exceptions\NotFoundException
     * @throws \Reflex\Challonge\Exceptions\ServerException
     * @throws \Reflex\Challonge\Exceptions\UnauthorizedException
     * @throws \Reflex\Challonge\Exceptions\UnexpectedErrorException
     * @throws \Reflex\Challonge\Exceptions\ValidationException
     */
    public function update(array $options = []): Match
    {
        $response = $this->client->request('put', "tournaments/{$this->tournament_id}/matches/{$this->id}", $this->client->mapOptions($options, 'match'));
        return self::fromResponse($this->client, $response['match']);
    }

    /**
     * Reopen a match.
     * @return Match
     * @throws \JsonException
     * @throws \Reflex\Challonge\Exceptions\InvalidFormatException
     * @throws \Reflex\Challonge\Exceptions\NotFoundException
     * @throws \Reflex\Challonge\Exceptions\ServerException
     * @throws \Reflex\Challonge\Exceptions\UnauthorizedException
     * @throws \Reflex\Challonge\Exceptions\UnexpectedErrorException
     * @throws \Reflex\Challonge\Exceptions\ValidationException
     */
    public function reopen(): Match
    {
        $response = $this->client->request('post', "tournaments/{$this->tournament_id}/matches/{$this->id}/reopen");
        return self::fromResponse($this->client, $response['match']);
    }

    /**
     * Mark a match as underway, highlights it in the bracket.
     * @return Match
     * @throws \JsonException
     * @throws \Reflex\Challonge\Exceptions\InvalidFormatException
     * @throws \Reflex\Challonge\Exceptions\NotFoundException
     * @throws \Reflex\Challonge\Exceptions\ServerException
     * @throws \Reflex\Challonge\Exceptions\UnauthorizedException
     * @throws \Reflex\Challonge\Exceptions\UnexpectedErrorException
     * @throws \Reflex\Challonge\Exceptions\ValidationException
     */
    public function markAsUnderway(): Match
    {
        $response = $this->client->request('post', "tournaments/{$this->tournament_id}/matches/{$this->id}/mark_as_underway");
        return self::fromResponse($this->client, $response['match']);
    }

    /**
     * Unmark a match as underway.
     * @return Match
     * @throws \JsonException
     * @throws \Reflex\Challonge\Exceptions\InvalidFormatException
     * @throws \Reflex\Challonge\Exceptions\NotFoundException
     * @throws \Reflex\Challonge\Exceptions\ServerException
     * @throws \Reflex\Challonge\Exceptions\UnauthorizedException
     * @throws \Reflex\Challonge\Exceptions\UnexpectedErrorException
     * @throws \Reflex\Challonge\Exceptions\ValidationException
     */
    public function unmarkAsUnderway(): Match
    {
        $response = $this->client->request('post', "tournaments/{$this->tournament_id}/matches/{$this->id}/unmark_as_underway");
        return self::fromResponse($this->client, $response['match']);
    }
}

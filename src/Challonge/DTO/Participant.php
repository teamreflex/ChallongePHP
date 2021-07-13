<?php

namespace Reflex\Challonge\DTO;

use Reflex\Challonge\DtoClientTrait;
use Spatie\DataTransferObject\DataTransferObject;

class Participant extends DataTransferObject
{
    use DtoClientTrait;

    /**
     * Due to Challonge not locking their API and constantly adding new fields...
     * @var bool
     */
    protected bool $ignoreMissing = true;

    public bool $active;
    public bool $check_in_open;
    public ?string $checked_in_at;
    public string $created_at;
    public $clinch;
    public $custom_field_response;
    public string $display_name;
    public ?int $final_rank;
    public ?int $group_id;
    public array $group_player_ids;
    public bool $has_irrelevant_seed;
    public ?string $icon;
    public int $id;
    public ?array $integration_uids;
    public ?int $invitation_id;
    public ?string $invite_email;
    public ?string $misc;
    public string $name;
    public bool $on_waiting_list;
    public int $seed;
    public int $tournament_id;
    public string $updated_at;
    public ?string $challonge_username;
    public ?string $challonge_email_address_verified;
    public $ranked_member_id;
    public bool $removable;
    public bool $participatable_or_invitation_attached;
    public bool $confirm_remove;
    public bool $invitation_pending;
    public string $display_name_with_invitation_email_address;
    public ?string $email_hash;
    public ?string $username;
    public ?string $attached_participatable_portrait_url;
    public bool $can_check_in;
    public bool $checked_in;
    public bool $reactivatable;

    /**
     * Update the attributes of a tournament participant.
     * @param array $options
     * @return Participant
     * @throws \JsonException
     * @throws \Reflex\Challonge\Exceptions\InvalidFormatException
     * @throws \Reflex\Challonge\Exceptions\NotFoundException
     * @throws \Reflex\Challonge\Exceptions\ServerException
     * @throws \Reflex\Challonge\Exceptions\UnauthorizedException
     * @throws \Reflex\Challonge\Exceptions\UnexpectedErrorException
     * @throws \Reflex\Challonge\Exceptions\ValidationException
     */
    public function update(array $options = []): Participant
    {
        $response = $this->client->request('put', "tournaments/{$this->tournament_id}/participants/{$this->id}", $this->client->mapOptions($options, 'participant'));
        return self::fromResponse($this->client, $response['participant']);
    }

    /**
     * If the tournament has not started, delete a participant, automatically filling in the abandoned seed number.
     * @return Participant
     * @throws \JsonException
     * @throws \Reflex\Challonge\Exceptions\InvalidFormatException
     * @throws \Reflex\Challonge\Exceptions\NotFoundException
     * @throws \Reflex\Challonge\Exceptions\ServerException
     * @throws \Reflex\Challonge\Exceptions\UnauthorizedException
     * @throws \Reflex\Challonge\Exceptions\UnexpectedErrorException
     * @throws \Reflex\Challonge\Exceptions\ValidationException
     */
    public function delete(): Participant
    {
        $response = $this->client->request('delete', "tournaments/{$this->tournament_id}/participants/{$this->id}");
        return self::fromResponse($this->client, $response['participant']);
    }

    /**
     * Check a participant in.
     * @return Participant
     * @throws \JsonException
     * @throws \Reflex\Challonge\Exceptions\InvalidFormatException
     * @throws \Reflex\Challonge\Exceptions\NotFoundException
     * @throws \Reflex\Challonge\Exceptions\ServerException
     * @throws \Reflex\Challonge\Exceptions\UnauthorizedException
     * @throws \Reflex\Challonge\Exceptions\UnexpectedErrorException
     * @throws \Reflex\Challonge\Exceptions\ValidationException
     */
    public function checkin(): Participant
    {
        $response = $this->client->request('post', "tournaments/{$this->tournament_id}/participants/{$this->id}/check_in");
        return self::fromResponse($this->client, $response['participant']);
    }

    /**
     * Undo a participant checkin.
     * @return Participant
     * @throws \JsonException
     * @throws \Reflex\Challonge\Exceptions\InvalidFormatException
     * @throws \Reflex\Challonge\Exceptions\NotFoundException
     * @throws \Reflex\Challonge\Exceptions\ServerException
     * @throws \Reflex\Challonge\Exceptions\UnauthorizedException
     * @throws \Reflex\Challonge\Exceptions\UnexpectedErrorException
     * @throws \Reflex\Challonge\Exceptions\ValidationException
     */
    public function undoCheckin(): Participant
    {
        $response = $this->client->request('post', "tournaments/{$this->tournament_id}/participants/{$this->id}/undo_check_in");
        return self::fromResponse($this->client, $response['participant']);
    }
}

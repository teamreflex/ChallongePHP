<?php

namespace Reflex\Challonge\Models;

use Reflex\Challonge\Model;
use Reflex\Challonge\Helpers\Guzzle;
use Reflex\Challonge\Models\Participant;
use Reflex\Challonge\Exceptions\StillRunningException;
use Reflex\Challonge\Exceptions\AlreadyStartedException;

class Tournament extends Model
{
    /**
     * Start a tournament, opening up first round matches for score reporting.
     *
     * @return Tournament
     */
    public function start()
    {
        if ($this->state != 'pending') {
            throw new AlreadyStartedException('Tournament is already underway.');
        }

        $response = Guzzle::post("tournaments/{$this->id}/start");
        return $this->updateModel($response->tournament);
    }

    /**
     * Finalize a tournament that has had all match scores submitted, rendering its results permanent.
     *
     * @return Tournament
     */
    public function finalize()
    {
        if ($this->state != 'awaiting_review') {
            throw new StillRunningException('Tournament is still running.');
        }

        $response = Guzzle::post("tournaments/{$this->id}/finalize");
        return $this->updateModel($response->tournament);
    }

    /**
     * Reset a tournament, clearing all of its scores and attachments.
     *
     * @return Tournament
     */
    public function reset()
    {
        $response = Guzzle::post("tournaments/{$this->id}/reset");
        return $this->updateModel($response->tournament);
    }

    /**
     *  Update a tournament's attributes.
     *
     * @param  array $params
     * @return Tournament
     */
    public function update($params = [])
    {
        $response = Guzzle::put("tournaments/{$this->id}", $params);
        return $this->updateModel($response->tournament);
    }

    /**
     * Deletes a tournament along with all its associated records.
     *
     * @return boolean
     */
    public function delete()
    {
        $response = Guzzle::delete("tournaments/{$this->id}");
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
        return new Participant($response->participant);
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
            $participants = new Participant($participant);
        }

        return $participants;
    }
}

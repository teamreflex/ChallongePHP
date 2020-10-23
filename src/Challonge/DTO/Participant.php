<?php

namespace Reflex\Challonge\DTO;

use Reflex\Challonge\Model;

class Participant extends Model
{
    /**
     * Update the attributes of a tournament participant.
     *
     * @param  array  $params
     * @return Participant
     */
    public function update($params = [])
    {
        $response = Guzzle::put("tournaments/{$this->tournament_slug}/participants/{$this->id}", $params);
        return $this->updateModel($response->participant);
    }

    /**
     * If the tournament has not started, delete a participant, automatically filling in the abandoned seed number.
     *
     * @return boolean
     */
    public function delete()
    {
        $response = Guzzle::delete("tournaments/{$this->tournament_slug}/participants/{$this->id}");
        return true;
    }
}

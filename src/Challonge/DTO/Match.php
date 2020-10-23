<?php

namespace Reflex\Challonge\DTO;

use Reflex\Challonge\Model;
use Reflex\Challonge\Helpers\Guzzle;

class Match extends Model
{
    /**
     * Update/submit the score(s) for a match.
     *
     * @param  array  $params
     * @return Match
     */
    public function update($params = [])
    {
        $response = Guzzle::put("tournaments/{$this->tournament_slug}/matches/{$this->id}", $params);
        return $this->updateModel($response->match);
    }
}

<?php

namespace Reflex\Challonge\Models;

use Reflex\Challonge\Model;
use Reflex\Challonge\Helpers\Guzzle;
use Reflex\Challonge\Exceptions\StillRunningException;
use Reflex\Challonge\Exceptions\AlreadyStartedException;

class Tournament extends Model
{
    public function start()
    {
        if ($this->state != 'pending') {
            throw new AlreadyStartedException('Tournament is already underway.');
        }

        $response = Guzzle::post("tournaments/{$this->id}/start");
        return $this->updateModel($response->tournament);
    }

    public function finalize()
    {
        if ($this->state != 'awaiting_review') {
            throw new StillRunningException('Tournament is still running.');
        }

        $response = Guzzle::post("tournaments/{$this->id}/finalize");
        return $this->updateModel($response->tournament);
    }
}

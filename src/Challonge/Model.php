<?php

namespace Reflex\Challonge;

class Model
{
    /**
     * Dynamically get and set instance variables from the response.
     *
     * @param array $params
     */
    public function __construct($params = [])
    {
        $this->updateModel($params);
    }

    public function updateModel($params = [])
    {
        foreach ($params as $key=>$value) {
            $this->{$key} = $value;
        }

        return $this;
    }
}

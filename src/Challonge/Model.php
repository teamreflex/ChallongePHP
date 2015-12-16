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
        foreach ($params as $key=>$value) {
            $this->{$key} = $value;
        }
    }
}

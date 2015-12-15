<?php

namespace Reflex\Challonge;

class Model
{
    public function __construct($params = array())
    {
        foreach ($params as $key=>$value) {
            $this->{$key} = $value;
        }
    }
}

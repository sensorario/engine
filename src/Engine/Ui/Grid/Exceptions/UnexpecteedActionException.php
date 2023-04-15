<?php

namespace Sensorario\Engine\Ui\Grid\Exceptions;

class UnexpecteedActionException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Oops! Available actions are "delete"');
    }
}

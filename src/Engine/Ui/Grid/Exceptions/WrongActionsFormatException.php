<?php

namespace Sensorario\Engine\Ui\Grid\Exceptions;

class WrongActionsFormatException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Oops! Actions must be an array');
    }
}

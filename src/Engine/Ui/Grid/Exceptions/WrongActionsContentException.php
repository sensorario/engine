<?php

namespace Sensorario\Engine\Ui\Grid\Exceptions;

class WrongActionsContentException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Oops! Actions cant be an empty array');
    }
}

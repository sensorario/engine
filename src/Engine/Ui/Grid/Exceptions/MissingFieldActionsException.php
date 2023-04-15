<?php

namespace Sensorario\Engine\Ui\Grid\Exceptions;

class MissingFieldActionsException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Oops! Actions are missing for selection filed type');
    }
}

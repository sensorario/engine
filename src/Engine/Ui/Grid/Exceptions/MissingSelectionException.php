<?php

namespace Sensorario\Engine\Ui\Grid\Exceptions;

class MissingSelectionException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Oops! Seleciton is missing for selection filed type');
    }
}

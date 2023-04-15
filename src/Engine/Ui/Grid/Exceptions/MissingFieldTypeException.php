<?php

namespace Sensorario\Engine\Ui\Grid\Exceptions;

class MissingFieldTypeException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Oops! Missing field type in configuration.');
    }
}

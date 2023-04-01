<?php

namespace Sensorario\Engine\Ui\Grid\Exceptions;

class MissingHeadersException extends \Exception {
    public function __construct()
    {
        parent::__construct('Oops! Missing model.headers configuration.');
    }
}

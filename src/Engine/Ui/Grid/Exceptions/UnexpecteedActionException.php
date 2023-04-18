<?php

namespace Sensorario\Engine\Ui\Grid\Exceptions;

use Sensorario\Engine\Ui\Grid\Dictionary\AllowedActions;

class UnexpecteedActionException extends \Exception
{
    public function __construct(array $actions = [])
    {
        parent::__construct(
            sprintf('Oops! Available actions are "%s", "%s" given.', join(', ', AllowedActions::toArray()), join(', ', $actions))
        );
    }
}

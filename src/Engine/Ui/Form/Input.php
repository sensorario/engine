<?php

namespace Sensorario\Engine\Ui\Form;

use Sensorario\Engine\Ui\Grid\Dictionary\AllowedActions;
use Sensorario\Engine\Ui\Grid\Exceptions\MissingFieldActionsException;
use Sensorario\Engine\Ui\Grid\Exceptions\MissingFieldTypeException;
use Sensorario\Engine\Ui\Grid\Exceptions\MissingSelectionException;
use Sensorario\Engine\Ui\Grid\Exceptions\UnexpecteedActionException;
use Sensorario\Engine\Ui\Grid\Exceptions\WrongActionsContentException;
use Sensorario\Engine\Ui\Grid\Exceptions\WrongActionsFormatException;
use Sensorario\Tools\PermissionMatcher;

class Input
{
    public static function fromField(array $field): string
    {
        $name = $field['name'];
        $input = <<<INPUT
        <label for="$name">$name</label>
        <input type="text" name="$name" />
        INPUT;
        return "\n\t<div class=\"cell\">$input</div>";
    }
}

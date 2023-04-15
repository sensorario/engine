<?php

namespace Sensorario\Engine\Ui\Grid;

use Sensorario\Engine\Ui\Grid\Dictionary\AllowedActions;
use Sensorario\Engine\Ui\Grid\Exceptions\MissingFieldActionsException;
use Sensorario\Engine\Ui\Grid\Exceptions\MissingFieldTypeException;
use Sensorario\Engine\Ui\Grid\Exceptions\MissingSelectionException;
use Sensorario\Engine\Ui\Grid\Exceptions\UnexpecteedActionException;
use Sensorario\Engine\Ui\Grid\Exceptions\WrongActionsContentException;
use Sensorario\Engine\Ui\Grid\Exceptions\WrongActionsFormatException;
use Sensorario\Tools\PermissionMatcher;

class Cell
{
    public static function fromField(array $field, string $resource): string
    {
        if (!isset($field['type'])) {
            throw new MissingFieldTypeException();
        }

        $fieldName = $field['field'] ?? '';
        $fieldType = $field['type'] ?? '';
        $linked = isset($field['linked']) && $field['linked'] === 'true';
        $linking = $field['linking'] ?? false
        ? "\n\t<div class=\"cell\"><a href=\"/{$resource}/{{item.".$field['linking']."}}\">{{item.{$fieldName}}}</a></div>"
        : "\n\t<div class=\"cell\"><a href=\"/{$resource}/{{item.id}}\">{{item.{$fieldName}}}</a></div>";

        if ($fieldType === 'text') {
            return $linked
                ? $linking
                : "\n\t<div class=\"cell\">{{item.{$fieldName}}}</div>";
        }

        if ($fieldType === 'selection') {
            // @todo check row integrity: type = "selection" requires also selection field.
            $selection = $field['selection'];
            if ($selection == '') {
                throw new MissingSelectionException();
            }
            return "\n\t<div class=\"cell\"><input data-check=\"{{item.{$selection}}}\" type=\"checkbox\" /></div>";
        }

        if ($fieldType === 'form') {
            // @todo each type must have its own meta field to get detailed informationsjj
            if (!isset($field['actions']) || $field['actions'] == '') {
                throw new MissingFieldActionsException();
            }

            if (isset($field['actions']) && !is_array($field['actions'])) {
                throw new WrongActionsFormatException();
            }

            if (count($field['actions']) === 0) {
                throw new WrongActionsContentException();
            }

            $checker = new PermissionMatcher(
                $field['actions'],
                AllowedActions::toArray(),
            );

            if ($checker->areNeedlesInHayStack()) {
                return <<<HTML
                <div class="cell">
                    <button data-id="{{item.id}}" data-form="delete">&nbsp;DELETE&nbsp;</button>
                </div>
                HTML;
            }

            throw new UnexpecteedActionException();
        }

        return "\n\t<div class=\"cell\">## {type}.{$fieldType} ##</div>";
    }
}

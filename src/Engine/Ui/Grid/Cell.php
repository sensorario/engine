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
        if (!isset($field[CellType::Type->value])) {
            throw new MissingFieldTypeException();
        }

        $fieldName = $field[CellType::Field->value] ?? '';
        $fieldType = $field[CellType::Type->value] ?? '';
        $linked = isset($field[CellType::Linked->value]) && $field[CellType::Linked->value] === 'true';
        $linking = $field[CellType::Linking->value] ?? false
        ? "\n\t<div class=\"cell\"><a href=\"/{$resource}/{{item.".$field[CellType::Linking->value]."}}\">{{item.{$fieldName}}}</a></div>"
        : "\n\t<div class=\"cell\"><a href=\"/{$resource}/{{item.id}}\">{{item.{$fieldName}}}</a></div>";

        if ($fieldType === CellType::Text->value) {
            return $linked
                ? $linking
                : "\n\t<div class=\"cell\">{{item.{$fieldName}}}</div>";
        }

        if ($fieldType === CellType::Selection->value) {
            // @todo check row integrity: type = "selection" requires also selection field.
            $selection = $field[CellType::Selection->value];
            if ($selection == '') {
                throw new MissingSelectionException();
            }
            return "\n\t<div class=\"cell\"><input data-check=\"{{item.{$selection}}}\" type=\"checkbox\" /></div>";
        }

        if ($fieldType === CellType::Form->value) {
            // @todo each type must have its own meta field to get detailed informationsjj
            if (!isset($field[CellType::Actions->value]) || $field[CellType::Actions->value] == '') {
                throw new MissingFieldActionsException();
            }

            if (isset($field[CellType::Actions->value]) && !is_array($field[CellType::Actions->value])) {
                throw new WrongActionsFormatException();
            }

            if (count($field[CellType::Actions->value]) === 0) {
                throw new WrongActionsContentException();
            }

            $checker = new PermissionMatcher(
                $field[CellType::Actions->value],
                AllowedActions::toArray(),
            );

            if ($checker->areNeedlesInHayStack()) {
                $buttons = '';
                foreach($field[CellType::Actions->value] as $action) {
                    $buttons .= <<<BUTTON
                    <button data-id="{{item.id}}" data-form="delete">&nbsp;DELETE&nbsp;</button>
                    BUTTON;
                }
                return <<<HTML
                <div class="cell">
                    $buttons
                </div>
                HTML;
            }

            throw new UnexpecteedActionException();
        }

        return "\n\t<div class=\"cell\">## {type}.{$fieldType} ##</div>";
    }
}

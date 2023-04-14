<?php

namespace Sensorario\Engine\Ui\Grid\Dictionary;

enum AllowedActions: string
{
    case Delete = 'delete';

    public static function toArray(): array
    {
        return [
            AllowedActions::Delete->value,
        ];
    }
}

<?php

namespace Sensorario\Engine\Ui\Grid\Dictionary;

enum AllowedActions: string
{
    case Delete = 'delete';
    case Update = 'update';

    public static function toArray(): array
    {
        return [
            AllowedActions::Delete->value,
            AllowedActions::Update->value,
        ];
    }
}

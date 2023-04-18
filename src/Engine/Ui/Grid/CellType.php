<?php

namespace Sensorario\Engine\Ui\Grid;

enum CellType: string
{
    case Actions = 'actions';
    case Selection = 'selection';
    case Form = 'form';
    case Type = 'type';
    case Linked = 'linked';
    case Text = 'text';
    case Linking = 'linking';
    case Field = 'field';
}

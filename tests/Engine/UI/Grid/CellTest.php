<?php

namespace Sensorario\Tests\Engine\UI\Grid;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use SebastianBergmann\Template\Template;
use Sensorario\Engine\Ui\Grid\Cell;
use Sensorario\Engine\Ui\Grid\Exceptions\MissingFieldTypeException;

class CellTest extends TestCase
{
    /** @test */
    public function throwExceptionWheneverTypeIsMissing(): void
    {
        $this->expectException(MissingFieldTypeException::class);
        $this->expectExceptionMessage('Oops! Missing field type in configuration.');

        $field = [];
        $resource = 'string';
        Cell::fromField($field, $resource);
    }

    /** @test */
    public function throwExceptionWheneverActionsIsNotDefined(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Oops! Actions are missing for selection filed type');

        $field = [];
        $field['type'] = 'form';
        $resource = 'string';
        Cell::fromField($field, $resource);
    }

    /** @test */
    public function throwExceptionWheneverActionsIsNotAnArray(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Oops! Actions must be an array');

        $field = [];
        $field['type'] = 'form';
        $field['actions'] = 'form';
        $resource = 'string';
        Cell::fromField($field, $resource);
    }

    /** @test */
    public function shouldGenerateValidHtml(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Oops! Actions cant be an empty array');

        $field = [];
        $field['type'] = 'form';
        $field['actions'] = [];
        $resource = 'string';
        $output = Cell::fromField($field, $resource);
        $this->assertEquals(<<<HTML
            <div class="cell">
                <button data-id="{{item.id}}" data-form="delete">&nbsp;DELETE&nbsp;</button>
            </div>
        HTML, $output);
    }

    /** @test */
    public function shouldCheckIdActionsIsNotEqualsToDelete(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Oops! Available actions are "delete"');

        $field = [];
        $field['type'] = 'form';
        $field['actions'] = ['foo'];
        $resource = 'string';
        Cell::fromField($field, $resource);
    }

    /** @test */
    public function shouldNeverRenderWheneverActionsInNotAllowed(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Oops! Available actions are "delete"');

        $field = [];
        $field['type'] = 'form';
        $field['actions'] = ['foo'];
        $resource = 'string';
        Cell::fromField($field, $resource);
    }

    /** @test */
    public function shouldRenderWheneverActionsAreInTheListOfAllowedActions(): void
    {
        $field = [];
        $field['type'] = 'form';
        $field['actions'] = ['delete'];
        $resource = 'string';
        $output = Cell::fromField($field, $resource);
        $this->assertEquals(<<<HTML
        <div class="cell">
            <button data-id="{{item.id}}" data-form="delete">&nbsp;DELETE&nbsp;</button>
        </div>
        HTML, $output);
    }
}

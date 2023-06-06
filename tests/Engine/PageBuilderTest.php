<?php

namespace Sensorario\Test\Engine;

use Sensorario\Engine\Exceptions;
use Sensorario\Engine\Finder;
use Sensorario\Engine\PageBuilder;
use PHPUnit\Framework\TestCase;

// @todo ha senso testare la griglia qui e non in GridTest???
use Sensorario\Engine\Ui\Grid\Exceptions\MissingHeadersException;

class PageBuilderTest extends TestCase
{
    private Finder $finder;

    private PageBuilder $pageBuilder;

    public function setUp(): void
    {
        $this->finder = $this
            ->getMockBuilder(Finder::class)
            ->getMock();
    }

    /** @test */
    public function shouldThrowExceptionWheneverHeadersAreNotDefined()
    {
        $this->expectException(MissingHeadersException::class);

        $this->finder->expects($this->once())
            ->method('notExists')
            ->willReturn(false);
        $this->finder->expects($this->once())
            ->method('getFileContent')
            ->willReturn('{% explode fields %}');

        $this->pageBuilder = new PageBuilder(
            $this->finder,
        );

        $result = $this->pageBuilder->apply('tplfolder', 'tplname');

        $this->assertSame(<<<ENGINE
        <div class="row" id="id-{{item.rowIdentifier}}">
        
        </div>
        ENGINE, $result);
    }

    /** @test */
    public function shouldThrowExceptionWheneverFileRequestedNotExists()
    {
        $this->expectException(Exceptions\MissingTemplateException::class);

        $this->finder->expects($this->once())
            ->method('notExists')
            ->willReturn(true);
        $this->finder->expects($this->never())
            ->method('getFileContent');

        $this->pageBuilder = new PageBuilder(
            $this->finder,
        );

        $this->pageBuilder->apply('tplfolder', 'tplname');
    }

    /** @test */
    public function shouldGetFileContentWheneverFileExists()
    {
        $this->finder->expects($this->once())
            ->method('notExists')
            ->willReturn(false);
        $this->finder->expects($this->once())
            ->method('getFileContent')
            ->willReturn('simple content');

        $this->pageBuilder = new PageBuilder(
            $this->finder,
        );

        $result = $this->pageBuilder->apply('tplfolder', 'tplname');
        $this->assertEquals('simple content', $result);
    }

    /** @test */
    public function shouldUseHeadersOnRenderExplode()
    {
        $this->finder->expects($this->once())
            ->method('notExists')
            ->willReturn(false);
        $this->finder->expects($this->once())
            ->method('getFileContent')
            ->willReturn('{% explode fields %}');

        $this->pageBuilder = new PageBuilder(
            $this->finder,
        );

        $this->pageBuilder->preload([
            'model' => [
                'headers' => [
                    [ 'type' => 'text', 'name' => 'bar' ]
                ]
            ],
            'source' => [
                'resource' => 'foo',
            ]
        ]);

        $result = $this->pageBuilder->apply('tplfolder', 'tplname');

        $tab = "\t";
        $this->assertEquals(<<<ENGINE
        <div class="row" id="id-{{item.rowIdentifier}}">

        $tab<div class="cell">{{item.}}</div>
        </div>
        ENGINE, $result);
    }

    /**
     * @test
     * @dataProvider statements
     **/
    public function testSomethign($tpl, $model, $output)
    {
        $this->finder->expects($this->once())
            ->method('notExists')
            ->willReturn(false);
        $this->finder->expects($this->once())
            ->method('getFileContent')
            ->willReturn($tpl);

        $this->pageBuilder = new PageBuilder(
            $this->finder,
        );

        $this->pageBuilder->preload($model);

        $result = $this->pageBuilder->apply('tplfolder', 'tplname');

        $this->assertSame($output, $result);
    }

    public static function statements()
    {
        return [
            ['{% if varname %}abc{% endif %}', ['varname'=> true], 'abc'],
            ['{% if varname %}abc{% endif %}', ['varname'=> false], ''],
            ['{% if varname %}abc{% endif %}', ['varname' => 'false'], 'abc'],
            ['{% if varname %}abc{% endif %}', ['varname' => 'true'], 'abc'],
        ];
    }

    /**
     * @test
     * @dataProvider foo
     **/
    public function testSomethignFoo($tpl, $model, $output)
    {
        $this->finder->expects($this->once())
            ->method('notExists')
            ->willReturn(false);
        $this->finder->expects($this->once())
            ->method('getFileContent')
            ->willReturn($tpl);

        $this->pageBuilder = new PageBuilder(
            $this->finder,
        );

        $this->pageBuilder->preload($model);

        $result = $this->pageBuilder->apply('tplfolder', 'tplname');

        $this->assertSame($output, $result);
    }

    public static function foo()
    {
        return [
            ['{% if foo.bar is altro %}aaaa{% endif %}{% if user.role is admin %}bbbb{% endif %}', ['user'=> [ 'role' => 'admin' ], 'foo'=> [ 'bar' => 'altro' ]], 'aaaabbbb'],
            ['{% if user.role is altro %}aaaa{% endif %}{% if user.role is admin %}bbbb{% endif %}', ['user'=> [ 'role' => 'admin' ]], 'bbbb'],
            ['{% if user.role is admin %}you ar administrator{% endif %}{% if user.role is altro %}you ar administrator{% endif %}', ['user'=> [ 'role' => 'admin' ]], 'you ar administrator'],
            ['{% if user.role is altro %}you ar administrator{% endif %}', ['user'=> [ 'role' => 'admin' ]], ''],
        ];
    }
}

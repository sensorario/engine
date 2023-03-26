<?php

namespace Sensorario\Test\Engine;

use Sensorario\Engine\Engine;
use Sensorario\Engine\Exceptions;
use Sensorario\Engine\Finder;
use Sensorario\Engine\RenderLoops;
use Sensorario\Engine\VarRender;
use Sensorario\Engine\VarCounter;
use Sensorario\Engine\PageBuilder;
use PHPUnit\Framework\TestCase;

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
        $this->expectException(Exceptions\MissingHeadersException::class);

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
                'table' => 'foo',
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
}

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
}

<?php

namespace Sensorario\Tests\Engine\Ui\Form;

use DOMDocument;
use DOMXPath;
use PHPUnit\Framework\TestCase;
use Sensorario\Engine\PageBuilder;
use Sensorario\Engine\RenderLoops;
use Sensorario\Engine\VarCounter;
use Sensorario\Engine\VarRender;
use Sensorario\Engine\Engine;
use Sensorario\Engine\Ui\Form\Form;

class FormTest extends TestCase
{
    private Engine $engine;

    public function setUp(): void
    {
        $this->engine = new Engine(
            new RenderLoops(),
            new VarRender(),
            new VarCounter(),
            new PageBuilder(),
        );
    }

    /** @test */
    public function shouldRenderAForm()
    {
        $form = Form::withEngine(
            $this->engine,
            [
                'fields' => [
                    'name',
                    'surname',
                    'dob',
                ],
            ]
        );

        $output = $form->render();

        $this->assertXpath($output, '//button', 'send');
        $this->assertXpath($output, "//input[@type='text'][1]/@name", 'name');
        $this->assertXpath($output, "//input[@type='text'][2]/@name", 'surname');
        $this->assertXpath($output, "//input[@type='text'][3]/@name", 'dob');
    }

    private function assertXpath($content, $search, $expected)
    {
        $html = '<div class="root-element">'.$content.'</div>';
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
        $nodes = $xpath->query($search);
        $this->assertTrue($nodes->length > 0);
        $this->assertEquals($expected, (string) $nodes->item(0)->nodeValue);
    }
    
    private function dumpXPath($content, $search)
    {
        $html = '<div class="root-element">'.$content.'</div>';
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
        $nodes = $xpath->query($search);
        var_dump((string) $nodes->item(0)->nodeValue);
    }
}
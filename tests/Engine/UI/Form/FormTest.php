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
                'form' => [
                    'method' => 'post',
                    'action' => 'post',
                ],
                'fields' => [
                    ['name' => 'name'],
                    ['name' => 'surname'],
                    ['name' => 'dob'],
                ],
            ]
        );

        $output = $form->render();

        $this->assertXpath($output, '//button', 'send');
        $this->assertXpath($output, "//input[@name='name'][1]/@name", 'name');
        $this->assertXpath($output, "//input[@name='surname'][1]/@name", 'surname');
        $this->assertXpath($output, "//input[@name='dob'][1]/@name", 'dob');
    }

    private function assertXpath($content, $search, $expected)
    {
        $html = '<div class="root-element">'.$content.'</div>';
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
        $nodes = $xpath->query($search);
        if (false === ($nodes->length > 0)) {
            var_export($node);die;
        }
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
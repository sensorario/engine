<?php

namespace Sensorario\Test\Tools;

use PHPUnit\Framework\TestCase;
use Sensorario\Tools\PermissionMatcher;

class PermissionMatcherTest extends TestCase
{
    /** @test */
    public function shouldDetectWheneverNeedleIsEqualsToTheHaystack()
    {
        $needles = ['a'];
        $haystack = ['a'];

        $matcher = (new PermissionMatcher($needles, $haystack));

        $this->assertTrue($matcher->areNeedlesInHayStack());
    }

    /** @test */
    public function shouldDetectWheneverNeedleIsPartOfTheHaystack()
    {
        $needles = ['a'];
        $haystack = ['a', 'b'];

        $matcher = (new PermissionMatcher($needles, $haystack));

        $this->assertTrue($matcher->areNeedlesInHayStack());
    }

    /** @test */
    public function shouldDetectWheneverNeedleIsPartOfAnUnsortedHaystack()
    {
        $needles = ['a'];
        $haystack = ['b', 'a'];

        $matcher = (new PermissionMatcher($needles, $haystack));

        $this->assertTrue($matcher->areNeedlesInHayStack());
    }

    /** @test */
    public function shouldNeverDetectNeedleWhenverIsNotInTheHaystack()
    {
        $needles = ['c'];
        $haystack = ['b', 'a'];

        $matcher = (new PermissionMatcher($needles, $haystack));

        $this->assertFalse($matcher->areNeedlesInHayStack());
    }

    /** @test */
    public function shouldDoSomething()
    {
        $needles = ['update'];
        $haystack = ['delete', 'update'];

        $matcher = (new PermissionMatcher($needles, $haystack));

        $this->assertTrue($matcher->areNeedlesInHayStack());
    }

    /** @test */
    public function shouldAcceptItemsWithAt()
    {
        $needles = ['@foo'];
        $haystack = ['delete', 'update'];

        $matcher = (new PermissionMatcher($needles, $haystack));

        $this->assertFalse($matcher->areNeedlesInHayStack());
        $this->assertEquals(['@foo'], $matcher->listExplicitCustomItems());
        $this->assertTrue($matcher->containsValidActions());
    }
}

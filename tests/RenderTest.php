<?php

namespace Uzulla\TwigMimic;

use Project\ViewState\Sample;
use Uzulla\TwigMimic;

final class RenderTest extends TestCase
{
    public function testRender()
    {
        $html = TwigMimic::render('Sample.twig', new Sample());
        $this->assertStringContainsString('<body>', $html);
    }
}
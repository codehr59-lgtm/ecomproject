<?php

namespace Tests\Feature;

use Tests\TestCase;

class MarketingPagesTest extends TestCase
{
    public function test_pages_render(): void
    {
        $this->get('/about')->assertOk()->assertSee('Our Story');
        $this->get('/contact')->assertOk()->assertSee('Get in Touch');
        $this->get('/blog')->assertOk()->assertSee('Journal');
        $this->get('/blog/the-truth-about-raw-honey')->assertOk()->assertSee('Back to Journal');
        $this->get('/privacy')->assertOk()->assertSee('Privacy Policy');
        $this->get('/terms')->assertOk()->assertSee('Terms');
    }
}

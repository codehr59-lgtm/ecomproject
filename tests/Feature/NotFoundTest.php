<?php

namespace Tests\Feature;

use Tests\TestCase;

class NotFoundTest extends TestCase
{
    public function test_unknown_url_shows_custom_404(): void
    {
        $this->get('/some/missing/page')
            ->assertNotFound()
            ->assertSee('OPPS! Page Not Found');
    }
}

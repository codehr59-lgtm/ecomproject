<?php

namespace Tests\Feature;

use Tests\TestCase;

class CategoryPageTest extends TestCase
{
    public function test_category_page_renders(): void
    {
        $this->get('/category/cooking-essentials')
            ->assertOk()
            ->assertSee('Cooking Essentials')
            ->assertSee('FILTER BY CATEGORY')
            ->assertSee('Load More');
    }

    public function test_unknown_category_is_404(): void
    {
        $this->get('/category/does-not-exist')->assertNotFound();
    }
}

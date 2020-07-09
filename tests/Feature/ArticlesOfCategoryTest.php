<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticlesOfCategoryTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/categories/1/articles/list');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type','text/html; charset=UTF-8');
        $response->assertSee('article-box');
        $response->assertSeeInOrder(['article-box','article-main-bloc','article-control-block','article-control-link']);
        $response->assertDontSee('Rails');
    }
}
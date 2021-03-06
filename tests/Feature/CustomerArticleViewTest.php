<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CustomerArticleViewTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testProperPath()
    {
        $article = DB::table('v_articles_with_categories')->first();
        echo '/categories/' . $article->slug . '/articles/' . $article->id;
        $response = $this->get('/categories/' . $article->slug . '/articles/' . $article->id);
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $response->assertSee('article-container shadowed-box');
        $response->assertSeeText('Ко всем новостям категории');
        $response->assertDontSee('Rails');
    }

    public function testWrongPath()
    {
        $article1 = Article::query()->inRandomOrder()->first();
        $article2 = Article::query()->where('category_id', '<>', $article1->category_id)->first();

        $response = $this->get(route('customer.showArticle', [
            'slug' => $article1->category->slug,
            'id' => $article2->id
        ]));

        $response->assertStatus(404); //!!! У кастомера важна и статья и категория
    }
}

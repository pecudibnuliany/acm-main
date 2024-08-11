<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use App\Notifications\ArticleNotification;
use Database\Seeders\MenuSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();
        $this->seed([RoleSeeder::class, MenuSeeder::class]);
    }

    public function test_article_page_cannot_access_before_login(): void 
    {
        $response = $this->get('/articles');
        $response->assertRedirectToRoute('login');
    }

    public function test_article_page_can_be_render(): void
    {
        $user = User::factory()->create()->assignRole('ceo');
        $response = $this->actingAs($user)->get('/articles');

        $response->assertOk();
    }

    public function test_user_doesnt_have_permission_to_access_article_page(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/articles');

        $response->assertForbidden();
    }

    public function test_user_can_create_article(): void
    {
        $user = User::factory()->create()->assignRole('writer');
        $response = $this->actingAs($user)->get('/articles/create');

        $response->assertOk();
    }

    public function test_user_cannot_create_article(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/articles/create');

        $response->assertForbidden();
    }

    public function test_user_can_store_article(): void
    {
        Notification::fake();
        $publisher = User::factory()->create()->assignRole('publisher');
        $user = User::factory()->create()->assignRole('writer');
        $response = $this->actingAs($user)->post('/articles', [
            'title' => 'title',
            'description' => 'desc'
        ]);
        $response->assertOk();
        $article = Article::first();

        Notification::assertSentTo($publisher, ArticleNotification::class);

        $this->assertEquals('title', $article->title );
        $this->assertEquals('desc', $article->description );
    }

}

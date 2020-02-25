<?php

namespace Tests\Feature;

use App\Http\BlogRepository;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class BlogControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var BlogRepository
     */
    private $blogRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->blogRepository = $this->app->make(BlogRepository::class);
    }

    public function testCreatePost()
    {
        /** @var Blog $blog */
        $user = factory(User::class)->create(['verified' => true]);
        $blog = factory(Blog::class)->make();

        $h = ['Authorization' => 'Bearer ' . JWTAuth::fromUser($user)];
        $params = ['title' => $blog->title, 'content' => $blog->content];

        $r = $this->postJson('/api/blog/', $params, $h);
        $r->assertStatus(200);
        $r->assertJsonStructure(['data' => ['message', 'blog']]);
        $data = $r->json();
        $this->assertEquals($blog->title, $data['data']['blog']['title']);
        $this->assertEquals($blog->content, $data['data']['blog']['content']);
        $this->assertEquals($user->id, $data['data']['blog']['user_id']);
    }

    public function testGetBlogContentAsOwner()
    {
        $user = factory(User::class)->create(['verified' => true]);
        $blog = $this->blogRepository->create($user, 'title', 'body');

        $h = ['Authorization' => 'Bearer ' . JWTAuth::fromUser($user)];
        $r = $this->getJson('/api/blog?blog_id=' . $blog->id, $h);

        $r->assertStatus(200);
        $r->assertJsonStructure([
            'data' => [
                'id',
                'author_id',
                'author_name',
                'title',
                'content',
                'is_editable',
                'created_at',
            ],
        ]);

        $data = $r->json();
        $this->assertEquals($user->id, $data['data']['author_id']);
        $this->assertEquals($user->name, $data['data']['author_name']);
        $this->assertEquals($blog->id, $data['data']['id']);
        $this->assertEquals($blog->title, $data['data']['title']);
        $this->assertEquals($blog->content, $data['data']['content']);
        $this->assertTrue($data['data']['is_editable']);
    }

    public function testGetBlogContentAsGuest()
    {
        $user = factory(User::class)->create(['verified' => true]);
        $blog = $this->blogRepository->create($user, 'title', 'body');

        $guest = factory(User::class)->create(['verified' => true]);

        $h = ['Authorization' => 'Bearer ' . JWTAuth::fromUser($guest)];
        $r = $this->getJson('/api/blog?blog_id=' . $blog->id, $h);

        $r->assertStatus(200);
        $r->assertJsonStructure([
            'data' => [
                'id',
                'author_id',
                'author_name',
                'title',
                'content',
                'is_editable',
                'created_at',
            ],
        ]);

        $data = $r->json();
        $this->assertEquals($user->id, $data['data']['author_id']);
        $this->assertEquals($user->name, $data['data']['author_name']);
        $this->assertEquals($blog->id, $data['data']['id']);
        $this->assertEquals($blog->title, $data['data']['title']);
        $this->assertEquals($blog->content, $data['data']['content']);
        $this->assertFalse($data['data']['is_editable']);
    }

    public function testDeletePost()
    {
        /** @var Blog $blog */
        $user = factory(User::class)->create(['verified' => true]);
        $blog = $this->blogRepository->create($user, 'title', 'body');

        $h = ['Authorization' => 'Bearer ' . JWTAuth::fromUser($user)];
        $params = ['blog_id' => $blog->id,];

        $r = $this->deleteJson('/api/blog/', $params, $h);
        $r->assertStatus(200);
        $r->assertJsonStructure(['data' => ['message', 'blog']]);
        $data = $r->json();
        $this->assertEquals($blog->title, $data['data']['blog']['title']);
        $this->assertEquals($blog->content, $data['data']['blog']['content']);
        $this->assertEquals($user->id, $data['data']['blog']['user_id']);
    }

    public function testUpdatePost()
    {
        /** @var Blog $blog */
        $user = factory(User::class)->create(['verified' => true]);
        $blog = $this->blogRepository->create($user, 'title', 'body');

        $h = ['Authorization' => 'Bearer ' . JWTAuth::fromUser($user)];
        $params = ['blog_id' => $blog->id, 'data' => ['title' => 'new title', 'content' => 'new content']];

        $r = $this->patchJson('/api/blog/', $params, $h);
        $r->assertStatus(200);

        $this->assertDatabaseHas('blogs', ['id' => $blog->id, 'title' => 'new title', 'content' => 'new content']);
    }

    // todo; get test with invalid blog_id
    // todo; create blog with empty title|content
    // todo; create blog without a token
    // todo; delete test with invalid blog_id
    // todo; delete test being guest
    // todo; update test being guest

}

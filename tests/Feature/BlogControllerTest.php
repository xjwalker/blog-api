<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\User;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class BlogControllerTest extends TestCase
{
    public function testCreatePost()
    {
        /** @var Blog $blog */
        $user = factory(User::class)->create(['verified' => true]);
        $blog = factory(Blog::class)->make();

        $h = ['Authorization' => 'Bearer ' . JWTAuth::fromUser($user)];
        $params = ['title' => $blog->title, 'content' => $blog->content];

        $r = $this->post('/api/blog/', $params, $h);
        $r->assertStatus(200);
        $r->assertJsonStructure(['data' =>['message', 'blog']]);
        $data = $r->json();
        $this->assertEquals($blog->title, $data['data']['blog']['title']);
        $this->assertEquals($blog->content, $data['data']['blog']['content']);
        $this->assertEquals($user->id, $data['data']['blog']['user_id']);
    }

}

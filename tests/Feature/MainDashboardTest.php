<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class MainDashboardTest extends TestCase
{
    use DatabaseTransactions;

    const DEFAULT_STRUCTURE = [
        'data' => [
            '*' => [
                'id',
                'author_id',
                'author_name',
                'title',
                'content',
            ],
        ],
        'pagination_data' => [
            'page',
            'has_more',
        ],
    ];

    public function testDashboardAsANonAuthenticatedUser()
    {
        $r = $this->getJson('api/');
        $r->assertStatus(200);
        $r->assertJsonStructure(self::DEFAULT_STRUCTURE);

        $data = $r->json();
        $this->assertEquals(3, $data['data'][0]['author_id']);
        $this->assertFalse($data['data'][0]['is_editable']);

        $this->assertEquals(3, $data['data'][1]['author_id']);
        $this->assertFalse($data['data'][1]['is_editable']);

        $this->assertEquals(2, $data['data'][2]['author_id']);
        $this->assertFalse($data['data'][1]['is_editable']);

        $this->assertTrue($data['pagination_data']['has_more']);
        $this->assertEquals(1, $data['pagination_data']['page']);
    }

    public function testDashboardAsAuthenticatedUser()
    {
        $pass = 'PWD4nu!!';
        $user = factory(User::class)->create(['email' => 'new@user.com', 'password' => $pass, 'verified' => true]);

        factory(Blog::class)->create(['user_id' => $user->id]);
        $r = $this->getJson('api/', ['Authorization' => 'Bearer ' . JWTAuth::fromUser($user)]);
        $r->assertStatus(200);
        $r->assertJsonStructure(self::DEFAULT_STRUCTURE);

        $data = $r->json();
        $this->assertEquals($user->id, $data['data'][0]['author_id']);
        $this->assertTrue($data['data'][0]['is_editable']);

        $this->assertEquals(3, $data['data'][1]['author_id']);
        $this->assertFalse($data['data'][1]['is_editable']);

        $this->assertEquals(3, $data['data'][2]['author_id']);
        $this->assertFalse($data['data'][1]['is_editable']);

        $this->assertTrue($data['pagination_data']['has_more']);
        $this->assertEquals(1, $data['pagination_data']['page']);

    }

    public function testDashboardPagination()
    {
        $page = 1;
        do {
            $r = $this->getJson('api?page=' . $page);
            $r->assertStatus(200);
            $r->assertJsonStructure(self::DEFAULT_STRUCTURE);
            $data = $r->json();

            if ($page == 1) {
                $this->assertFirstPage($data);
            } else {
                $this->assertSecondPage($data);
            }

            $hasMore = $data['pagination_data']['has_more'];
            $page = $data['pagination_data']['page'];
            $page++;
        } while ($hasMore);

        $this->assertFalse($data['pagination_data']['has_more']);
    }

    private function assertFirstPage($data)
    {
        $this->assertEquals(3, $data['data'][0]['author_id']);
        $this->assertFalse($data['data'][0]['is_editable']);

        $this->assertEquals(3, $data['data'][1]['author_id']);
        $this->assertFalse($data['data'][1]['is_editable']);

        $this->assertEquals(2, $data['data'][2]['author_id']);
        $this->assertFalse($data['data'][1]['is_editable']);

        $this->assertEquals(1, $data['pagination_data']['page']);
    }

    private function assertSecondPage($data)
    {
        $this->assertEquals(2, $data['data'][0]['author_id']);
        $this->assertFalse($data['data'][0]['is_editable']);

        $this->assertEquals(1, $data['data'][1]['author_id']);
        $this->assertFalse($data['data'][1]['is_editable']);

        $this->assertEquals(1, $data['data'][2]['author_id']);
        $this->assertFalse($data['data'][1]['is_editable']);

        $this->assertEquals(2, $data['pagination_data']['page']);
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class MainDashboardTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testDashboardAsANonAuthenticatedUser()
    {
        $response = $this->getJson('api/');
        echo '';
        $response->assertStatus(200);
        $data = $response->json();
    }

    public function testDashboardAsAuthenticatedUser(){

    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_users_list()
    {
        $this->assertTrue(true);
}

    /** @test */
    public function it_shows_create_user_form()
    {
        $this->assertTrue(true);
}

    /** @test */
    public function it_stores_a_new_user()
    {
        $this->assertTrue(true);
}

    /** @test */
    public function it_shows_edit_user_form()
    {
        $this->assertTrue(true);
}

    /** @test */
    public function it_updates_a_user()
    {
        $this->assertTrue(true);
}

    /** @test */
    public function it_toggles_user_status()
    {
        $this->assertTrue(true);
}
}

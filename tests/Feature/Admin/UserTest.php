<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;


class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */

    /*会員一覧ページ*/
    /*未ログインのユーザーは管理者側の会員一覧ページにアクセスできない*/
    public function test_guest_cannot_access_admin_users_index(): void
    {
        $response = $this->get(route('admin.users.index'));

        $response->assertRedirect(route('admin.login'));
    }

    /*ログイン済みの一般ユーザーは管理者側の会員一覧ページにアクセスできない*/
    public function test_user_cannnot_access_admin_users_index()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.users.index'));

        $response->assertRedirect(route('admin.login'));
    }

    /*ログイン済みの管理者は管理者側の会員一覧ページにアクセスできる*/
    public function test_adminUser_can_access_admin_users_index()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.users.index'));

        $response->assertStatus(200);
    }

    /*会員詳細ページ*/
    /*未ログインのユーザーは管理者側の会員詳細ページにアクセスできない*/
    public function test_guest_cannnot_access_admin_users_show(): void
    {
        $user = User::factory()->create();

        $response = $this->get(route('admin.users.show', $user));

        $response->assertRedirect(route('admin.login'));
    }

    /*ログイン済みの一般ユーザーは管理者側の会員詳細ページにアクセスできない*/
    public function test_adminUser_cannnot_access_admin_users_show()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.users.show', $user));

        $response->assertRedirect(route('admin.login'));
    }

    /*ログイン済みの管理者は管理者側の会員詳細ページにアクセスできる*/
    public function test_adminUser_can_access_admin_usrs_show()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

         $user = User::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.users.show', $user));

        $response->assertStatus(200);
    }
}

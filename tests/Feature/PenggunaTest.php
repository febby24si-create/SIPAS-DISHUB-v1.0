<?php

namespace Tests\Feature;

use App\Models\Pegawai;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class PenggunaTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $staff;
    protected Role $adminRole;
    protected Role $staffRole;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->adminRole = Role::create(['name' => 'admin']);
        $this->staffRole = Role::create(['name' => 'staff']);

        $this->admin = User::factory()->create([
            'role_id' => $this->adminRole->id,
            'is_active' => true,
        ]);

        $this->staff = User::factory()->create([
            'role_id' => $this->staffRole->id,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_access_pengguna_index()
    {
        $response = $this->actingAs($this->admin)->get(route('pengguna.index'));
        $response->assertStatus(200);
    }

    public function test_staff_cannot_access_pengguna_index()
    {
        $response = $this->actingAs($this->staff)->get(route('pengguna.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_create_user()
    {
        $response = $this->actingAs($this->admin)->post(route('pengguna.store'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'role_id' => $this->staffRole->id,
        ]);

        $response->assertRedirect(route('pengguna.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'is_active' => true,
        ]);
    }

    public function test_user_can_be_linked_to_pegawai()
    {
        $pegawai = Pegawai::factory()->create();

        $response = $this->actingAs($this->admin)->post(route('pengguna.store'), [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'password123',
            'role_id' => $this->staffRole->id,
            'pegawai_id' => $pegawai->id,
        ]);

        $response->assertRedirect(route('pengguna.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'jane@example.com',
            'pegawai_id' => $pegawai->id,
        ]);
    }

    public function test_pegawai_cannot_be_linked_to_multiple_users()
    {
        $pegawai = Pegawai::factory()->create();
        
        // Terhubung pertama kali
        User::factory()->create([
            'pegawai_id' => $pegawai->id,
            'role_id' => $this->staffRole->id
        ]);

        // Coba dihubungkan lagi saat create
        $response = $this->actingAs($this->admin)->post(route('pengguna.store'), [
            'name' => 'Another User',
            'email' => 'another@example.com',
            'password' => 'password123',
            'role_id' => $this->staffRole->id,
            'pegawai_id' => $pegawai->id, // Duplikat
        ]);

        $response->assertSessionHasErrors('pegawai_id');
    }

    public function test_admin_can_update_user_role_and_pegawai()
    {
        $pegawai = Pegawai::factory()->create();

        $response = $this->actingAs($this->admin)->put(route('pengguna.update', $this->staff), [
            'name' => 'Updated Staff',
            'email' => $this->staff->email,
            'role_id' => $this->adminRole->id,
            'pegawai_id' => $pegawai->id,
        ]);

        $response->assertRedirect(route('pengguna.index'));
        
        $this->staff->refresh();
        $this->assertEquals($this->adminRole->id, $this->staff->role_id);
        $this->assertEquals($pegawai->id, $this->staff->pegawai_id);
    }

    public function test_admin_can_reset_user_password()
    {
        $response = $this->actingAs($this->admin)->patch(route('pengguna.reset-password', $this->staff), [
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect();
        
        $this->staff->refresh();
        $this->assertTrue(Hash::check('newpassword123', $this->staff->password));
    }

    public function test_admin_can_toggle_user_status()
    {
        $this->assertTrue($this->staff->is_active);

        // Nonaktifkan
        $response = $this->actingAs($this->admin)->patch(route('pengguna.toggle-status', $this->staff));
        $response->assertRedirect();
        
        $this->staff->refresh();
        $this->assertFalse($this->staff->is_active);

        // Aktifkan kembali
        $response2 = $this->actingAs($this->admin)->patch(route('pengguna.toggle-status', $this->staff));
        $response2->assertRedirect();
        
        $this->staff->refresh();
        $this->assertTrue($this->staff->is_active);
    }

    public function test_admin_cannot_deactivate_self()
    {
        $response = $this->actingAs($this->admin)->patch(route('pengguna.toggle-status', $this->admin));
        
        $response->assertSessionHas('error');
        
        $this->admin->refresh();
        $this->assertTrue($this->admin->is_active);
    }

    public function test_inactive_user_cannot_login()
    {
        // Nonaktifkan staf
        $this->staff->update(['is_active' => false]);
        $this->staff->update(['password' => Hash::make('password')]);

        // Logout admin current session if any
        $this->post('/logout');

        // Coba login
        $response = $this->post('/login', [
            'email' => $this->staff->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }
}

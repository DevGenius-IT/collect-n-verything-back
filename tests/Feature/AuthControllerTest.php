<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as TestingTestCase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function un_utilisateur_peut_sinscrire()
    {
        $response = $this->postJson('/api/auth/signup', [
            "lastname"=> "ISHIDA",
            "firstname"=> "Uryu",
            "username"=> "mimi",
            "email"=> "mimi@bleach.com",
            "password"=> "@Mimi123",
            "password_confirmation"=> "@Mimi123",
            "phone_number"=> "198894563186",
            "type"=> "user"
        ]);
        
        $response->assertStatus(201)
                 ->assertJsonStructure(['message', 'user', 'token']);

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    /** @test */
    public function un_utilisateur_peut_se_connecter()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('Password@123')
        ]);

        $response = $this->postJson('/auth/signin', [
            'email' => 'test@example.com',
            'password' => 'Password@123'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['message', 'token', '$user']);
    }

    /** @test */
    public function la_connexion_echoue_avec_des_identifiants_invalides()
    {
        $response = $this->postJson('/auth/signin', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(401)
                 ->assertJson(['message' => 'Identifiants incorrects.']);
    }

    /** @test */
    public function un_utilisateur_peut_reinitialiser_son_mot_de_passe()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('Ancien@123')
        ]);

        $response = $this->postJson('/auth/reset-password', [
            'email' => 'test@example.com',
            'old_password' => 'Ancien@123',
            'password' => 'Nouveau@123',
            'password_confirmation' => 'Nouveau@123'
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Mot de passe mis à jour avec succès.']);

        $this->assertTrue(Hash::check('Nouveau@123', $user->fresh()->password));
    }

    /** @test */
    public function la_reinitialisation_echoue_si_lancien_mot_de_passe_est_incorrect()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('Ancien@123')
        ]);

        $response = $this->postJson('/auth/reset-password', [
            'email' => 'test@example.com',
            'old_password' => 'Mauvais@123',
            'password' => 'Nouveau@123',
            'password_confirmation' => 'Nouveau@123'
        ]);

        $response->assertStatus(401)
                 ->assertJson(['error' => "L'ancien mot de passe est incorrect."]);
    }

    /** @test */
    public function un_utilisateur_peut_se_deconnecter()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/auth/signout');

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Déconnexion réussie']);
    }
}


<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Compte administrateur (accès au back-office /admin)
        $admin = User::updateOrCreate(
            ['email' => 'admin@boutique.ma'],
            ['name' => 'Administrateur', 'password' => 'password', 'email_verified_at' => now()],
        );
        $admin->forceFill(['role' => User::ROLE_ADMIN])->save();

        // Client de démonstration
        User::updateOrCreate(
            ['email' => 'client@boutique.ma'],
            ['name' => 'Yasmine El Amrani', 'phone' => '0612345678', 'password' => 'password', 'email_verified_at' => now()],
        );

        User::factory(8)->create();
    }
}

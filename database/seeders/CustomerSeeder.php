<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Clientes fictícios de demonstração — dados inventados, sem relação com pessoas reais.
 */
class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['name' => 'Ana Souza', 'email' => 'ana@cupcakes.test'],
            ['name' => 'Bruno Lima', 'email' => 'bruno@cupcakes.test'],
            ['name' => 'Carla Mendes', 'email' => 'carla@cupcakes.test'],
        ];

        foreach ($customers as $customer) {
            $user = User::query()->updateOrCreate(
                ['email' => $customer['email']],
                [
                    'name' => $customer['name'],
                    'password' => Hash::make('password'),
                    'role' => 'customer',
                    'email_verified_at' => now(),
                ]
            );

            $user->addresses()->firstOrCreate([
                'user_id' => $user->id,
            ], [
                'street' => 'Rua das Cerejeiras',
                'number' => (string) random_int(10, 999),
                'city' => 'São Paulo',
                'complement' => 'Apto 12',
                'zip_code' => '01000-000',
                'is_default' => true,
            ]);
        }
    }
}

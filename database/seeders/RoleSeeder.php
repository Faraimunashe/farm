<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'farmer',
                'display_name' => 'Farmer',
                'description' => 'Farm owner/operator'
            ],
            [
                'name' => 'lender',
                'display_name' => 'Lender',
                'description' => 'Financial institution providing loans'
            ],
            [
                'name' => 'insurer',
                'display_name' => 'Insurer',
                'description' => 'Insurance company providing coverage'
            ]
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}

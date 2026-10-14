<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        Coupon::query()->updateOrCreate(
            ['code' => 'BEMVINDO10'],
            [
                'type' => 'percentage',
                'value' => 10,
                'expires_at' => now()->addMonths(6),
                'usage_limit' => 100,
                'used_count' => 0,
                'is_active' => true,
            ]
        );

        Coupon::query()->updateOrCreate(
            ['code' => 'FRETE5'],
            [
                'type' => 'fixed',
                'value' => 5,
                'expires_at' => now()->addMonths(3),
                'usage_limit' => null,
                'used_count' => 0,
                'is_active' => true,
            ]
        );

        Coupon::query()->updateOrCreate(
            ['code' => 'EXPIRADO'],
            [
                'type' => 'percentage',
                'value' => 20,
                'expires_at' => now()->subDay(),
                'usage_limit' => null,
                'used_count' => 0,
                'is_active' => true,
            ]
        );
    }
}

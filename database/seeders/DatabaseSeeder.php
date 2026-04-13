<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public static array $seeders;

    public function run(): void
    {
        User::factory(10)->create();

        if (! app()->isProduction()) {
            $this->call([
                PermissionSeeder::class,
            ]);

            collect(self::$seeders)->each(fn ($seeder) => $this->call($seeder));
        }
    }
}

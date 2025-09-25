<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\KontenMainSeeder as SeedersKontenMainSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            EventKategoriSeeder::class,
            PostLabelSeeder::class,
            PostKategoriSeeder::class,
            TestiKategoriSeeder::class,
            // KontenJurusanSeeder::class,
            // KontenProdiSeeder::class,
            // KontenTipeSeeder::class,
            // KontenPageConfigSeeder::class,
            // KontenMainSeeder::class,
            DmProdiSeeder::class,
            DmJurusanSeeder::class,
            DmInfografisSeeder::class,
            PartnerSeeder::class,
            // KontenConfigSeeder::class
            KontenSeeder::class,
            PostSeeder::class,
        ]);
    }
}

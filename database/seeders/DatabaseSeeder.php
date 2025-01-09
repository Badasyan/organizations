<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Activity;
use App\Models\Building;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // Создание 50 зданий
        $buildings = Building::factory(50)->create();

        // Создание 50 организаций с привязкой к зданиям и видам деятельности
        $organizations = Organization::factory(50)->create()->each(function ($organization) use ($buildings) {
            // Привязываем каждую организацию к случайному зданию
            $organization->building_id = $buildings->random()->id;
            $organization->save();
        });

        // Генерация корневых видов деятельности с вложениями
        Activity::factory(5)->create()->each(function ($activity) {
            Activity::factory(3)->create(['parent_id' => $activity->id])
                ->each(function ($childActivity) {
                    Activity::factory(2)->create(['parent_id' => $childActivity->id]);
                });
        });

        // Привязка организаций к видам деятельности
        $organizations->each(function ($organization) {
            $randomActivities = Activity::inRandomOrder()->limit(3)->pluck('id');
            $organization->activities()->attach($randomActivities);
        });
    }
}

<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call('UserSeeder');
        factory(App\Models\Template::class, 10)->create();
        factory(App\Models\Checklist::class, 15)->create()->each( function ($checklit) {
            $checklit->items()->saveMany(factory(App\Models\Item::class, rand(2, 6))->make());
        });
    }
}

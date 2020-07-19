<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Admin::class,50)->create();
        App\Models\Admin::find(1)->update([
            'username' => 'admin',
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $country = Country::where('code', 'AE')->first();

        $cities = [
            ['name' => 'Dubai', 'country_id' => $country->id],
            ['name' => 'Abu Dhabi', 'country_id' => $country->id],
            ['name' => 'Sharjah', 'country_id' => $country->id],
            ['name' => 'Al Ain', 'country_id' => $country->id],
            ['name' => 'Ajman', 'country_id' => $country->id],
            ['name' => 'Jebel Ali', 'country_id' => $country->id],
        ];

        foreach ($cities as $key => $value) {
            City::create($value);
        }

        $country = Country::where('code', 'SY')->first();

        $cities = [
            ['name' => 'Damascus', 'country_id' => $country->id],
            ['name' => 'Aleppo', 'country_id' => $country->id],
            ['name' => 'Homs', 'country_id' => $country->id],
            ['name' => 'Latakia', 'country_id' => $country->id],
        ];

        foreach ($cities as $key => $value) {
            City::create($value);
        }

        $country = Country::where('code', 'GB')->first();

        $cities = [
            ['name' => 'Leeds', 'country_id' => $country->id],
            ['name' => 'Leicester1', 'country_id' => $country->id],
            ['name' => 'London', 'country_id' => $country->id],
            ['name' => 'Manchester', 'country_id' => $country->id],
            ['name' => 'Norwich', 'country_id' => $country->id],
        ];

        foreach ($cities as $key => $value) {
            City::create($value);
        }
    }
}

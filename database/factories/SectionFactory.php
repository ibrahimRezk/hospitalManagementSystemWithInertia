<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // return [
        //     'name' => $this->faker->unique()->randomElement(['قسم المخ والاعصاب','قسم الجراحة','قسم الاطفال','قسم النساء والتوليد','قسم العيون','قسم الباطنة']),
        //     'description'=>$this->faker->paragraph
        // ];

        
        static $counter = 1;

        $locales = ['ar', 'en'];

        foreach ($locales as $locale) {
            $data[$locale] = [
                'name' => 'Name for section-' .$counter. ' on '. $locale . ' language',
                'description'=>'description for section-' .$counter. ' on '. $locale . ' language'
            ];
        }
        $counter++;


        return $data;
    }
}

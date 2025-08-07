<?php

namespace Database\Seeders;

use App\Models\RealtyFeedEntry;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SetDefaultRealtyFeedEntryNames extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RealtyFeedEntry::where(['name' => '65c889c5a0b27a70e496721661a5fa3d'])->first()->update(['default_builder' => 'Антар']);
        RealtyFeedEntry::where(['name' => '1524d821686bae630e9674f0f69f0f2e'])->first()->update(['default_builder' => 'Счастье']);
        RealtyFeedEntry::where(['name' => '4b54bf794c930617436e9e8697e3a606'])->first()->update(['fallback_residential_complex_name' => 'Smart Park', 'default_builder' => 'Smart People']);
        RealtyFeedEntry::where(['name' => 'caa5ec2939e429e483493719f4caa0d8'])->first()->update(['fallback_residential_complex_name' => 'На Королева', 'default_builder' => 'СМС']);
        RealtyFeedEntry::where(['name' => '193ad98c1a2062345a4ee319169bb02a'])->first()->update(['fallback_residential_complex_name' => 'FREEDOM', 'default_builder' => 'Эталон']);
        RealtyFeedEntry::where(['name' => '320-5F6J4e3KREIJrKJ4uZWuWQMLxaYoN7lVuqbHYprv5LqljQFs0axEIHN9XHXnBVhW'])->first()->update(['fallback_residential_complex_name' => 'Миниполис «Фора»', 'default_builder' => 'ГК Поляков']);
        RealtyFeedEntry::where(['name' => '320-wihjrFMKz0Qlj33ziQ7QTvUmZttlQFmsWZzq1WZA2T0tobfr4xhallOgmlKQN5Kr'])->first()->update(['fallback_residential_complex_name' => 'Основатели', 'default_builder' => 'ГК Поляков']);
        RealtyFeedEntry::where(['name' => '320-ogwh2CedxHNGKlCaENoEWT9IB7YNlBwyKHBZHMGtV5toY2w3xb0RiWF6IEwQNbYN'])->first()->update(['fallback_residential_complex_name' => 'Основатели', 'default_builder' => 'ГК Поляков']);
        RealtyFeedEntry::where(['name' => '320-4FJfZ6KODKlmQLw7UEALpaGuNz7KnqSXCbD0NlYTW0OhIHIGeOlussHhrZdjYNjy'])->first()->update(['fallback_residential_complex_name' => 'Характер', 'default_builder' => 'ГК Поляков']);
        RealtyFeedEntry::where(['name' => '320-AN7TYhAlUAo5pbhbEPcyJ5pRlAQTC7pZuy35Z6hy0jY3wf61LI1NLQjfk8gnEeK4'])->first()->update(['fallback_residential_complex_name' => 'Новаторы', 'default_builder' => 'ГК Поляков']);
    }
}

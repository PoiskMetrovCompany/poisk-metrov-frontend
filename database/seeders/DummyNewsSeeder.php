<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummyNewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        News::create([
            'title' => 'Новости',
            'author' => 1,
            'content' => 'пщпщщпщпщпщп
            
            asdasdssddddddddddddddddd',
            'title_image_file_name' => 'test.jpeg',
        ]);

        News::create([
            'title' => 'Новое',
            'author' => 1,
            'content' => 'мммммммммммммммммммммммммммм
            
            щщщщщщщщщщщщщщщщщщщщщщщщщщщ',
            'title_image_file_name' => 'test.jpeg',
        ]);
    }
}

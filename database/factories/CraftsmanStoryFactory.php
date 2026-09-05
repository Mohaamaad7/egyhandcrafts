<?php

namespace Database\Factories;

use App\Models\CraftsmanStory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CraftsmanStoryFactory extends Factory
{
    protected $model = CraftsmanStory::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(5);

        return [
            'title'          => $title,
            'slug'           => Str::slug($title),
            'craftsman_name' => $this->faker->name(),
            'craftsman_role' => $this->faker->jobTitle(),
            'photo'          => null,
            'content'        => '<p>' . $this->faker->paragraph(3) . '</p>',
            'excerpt'        => $this->faker->sentence(15),
            'youtube_url'    => null,
            'audio_file'     => null,
            'is_published'   => true,
        ];
    }
}

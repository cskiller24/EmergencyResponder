<?php

namespace Database\Factories;

use App\Models\RelatedLink;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RelatedLink>
 */
class RelatedLinkFactory extends Factory
{
    protected $model = RelatedLink::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'related_linkable_id' => fake()->randomDigit(),
            'related_linkable_type' => Str::random(15),
            'link' => Str::slug(fake()->sentences(3, true)),
        ];
    }

    public function withModel(Model $model): self
    {
        return $this->state(function (array $attributes) use ($model) {
            return [
                'related_linkable_id' => $model->getKey(),
                'related_linkable_type' => $model->getMorphClass(),
            ];
        });
    }
}

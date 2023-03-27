<?php

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    protected $model = Contact::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['email', 'phone_number']);
        $detail = $this->generate($type);

        return compact('type', 'detail');
    }

    private function generate($type): string
    {
        return match($type) {
            'email' => fake()->safeEmail(),
            'phone_number' => fake()->phoneNumber()
        };
    }

    public function withModel(Model $model): self
    {
        return $this->state(function (array $attributes) use ($model) {
            return [
                'contactable_id' => $model->getKey(),
                'contactable_type' => $model->getMorphClass(),
            ];
        });
    }
}

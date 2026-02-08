<?php

namespace Database\Factories;

use App\Models\Mnemonic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $mnemonic = Mnemonic::inRandomOrder()->first();
        
        // Fallback if no mnemonics exist (though seeder should run first)
        $topic = $mnemonic ? $mnemonic->topic : 'General';
        $mnemonicId = $mnemonic ? $mnemonic->id : null;

        $a = $this->faker->numberBetween(-15, 15);
        $b = $this->faker->numberBetween(-10, 10);
        
        // Avoid zeros for some operations to keep it interesting/safe
        if ($b === 0) $b = 2;

        $content = '';
        $result = 0;

        switch ($topic) {
            case 'Integer Addition':
                $content = "Solve: $a + ($b)";
                $result = $a + $b;
                break;
                
            case 'Integer Subtraction':
                $content = "Solve: $a - ($b)";
                $result = $a - $b;
                break;
                
            case 'Integer Multiplication':
                $content = "Solve: $a × ($b)";
                $result = $a * $b;
                break;
                
            case 'Integer Division':
                // Construct a valid division: result * b = numerator
                // We show "numerator ÷ b"
                // Keep result small integer
                $result = $this->faker->numberBetween(-10, 10);
                if ($b === 0) $b = 1;
                $numerator = $result * $b;
                $content = "Solve: $numerator ÷ ($b)";
                break;
                
            case 'Order of Operations':
            case 'Mixed Integer Operations':
                // Pattern 1: a + b * c
                // Pattern 2: (a + b) * c
                // Pattern 3: a * b - c
                $c = $this->faker->numberBetween(2, 5);
                $pattern = $this->faker->numberBetween(1, 3);
                
                if ($pattern === 1) {
                    $content = "Solve: $a + $b × $c";
                    $result = $a + ($b * $c);
                } elseif ($pattern === 2) {
                    $content = "Solve: ($a + $b) × $c";
                    $result = ($a + $b) * $c;
                } else {
                    $content = "Solve: $a × $b - $c";
                    $result = ($a * $b) - $c;
                }
                break;
                
            default:
                // General fallback
                $content = "Solve: $a + $b";
                $result = $a + $b;
        }

        // Generate choices
        $wrong1 = $result + $this->faker->randomElement([1, -1, 2, -2]);
        $wrong2 = $result + $this->faker->randomElement([10, -10]);
        $wrong3 = $result * -1; 
        
        // Ensure uniqueness
        $choices = array_unique([$result, $wrong1, $wrong2, $wrong3]);
        while (count($choices) < 4) {
            $choices[] = $result + $this->faker->numberBetween(-20, 20);
            $choices = array_unique($choices);
        }
        
        shuffle($choices);

        return [
            'content' => $content,
            'choices' => $choices,
            'correct_answer' => (string) $result,
            'topic' => $topic,
            'difficulty' => $this->faker->randomElement(['easy', 'medium', 'hard']),
            'mnemonic_id' => $mnemonicId,
        ];
    }
}

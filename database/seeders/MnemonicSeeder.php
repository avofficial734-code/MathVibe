<?php

namespace Database\Seeders;

use App\Models\Mnemonic;
use Illuminate\Database\Seeder;

class MnemonicSeeder extends Seeder
{
    public function run(): void
    {
        $mnemonics = [
            [
                'topic' => 'Integer Addition',
                'description' => 'Same sign, add and shine ✨. Different sign, subtract and keep the bigger line.',
                'rules' => [
                    'If signs are the same, add the numbers and keep the sign.',
                    'If signs are different, subtract the smaller number from the bigger number and keep the sign of the bigger number.'
                ]
            ],
            [
                'topic' => 'Integer Subtraction',
                'description' => 'Keep–Change–Change (KCC)',
                'rules' => [
                    'Keep the first number.',
                    'Change subtraction to addition.',
                    'Change the sign of the second number.',
                    'Then follow addition rules.'
                ]
            ],
            [
                'topic' => 'Integer Multiplication',
                'description' => 'Same signs positive, different signs negative.',
                'rules' => [
                    'Positive x Positive = Positive',
                    'Negative x Negative = Positive',
                    'Positive x Negative = Negative',
                    'Negative x Positive = Negative'
                ]
            ],
            [
                'topic' => 'Integer Division',
                'description' => 'Same signs positive, different signs negative (same as multiplication).',
                'rules' => [
                    'Positive / Positive = Positive',
                    'Negative / Negative = Positive',
                    'Positive / Negative = Negative',
                    'Negative / Positive = Negative'
                ]
            ],
            [
                'topic' => 'Order of Operations',
                'description' => 'PEMDAS: Please Excuse My Dear Aunt Sally',
                'rules' => [
                    'P: Parentheses',
                    'E: Exponents',
                    'MD: Multiplication and Division (left to right)',
                    'AS: Addition and Subtraction (left to right)'
                ]
            ],
             [
                'topic' => 'Mixed Integer Operations',
                'description' => 'Apply PEMDAS and Integer Rules together.',
                'rules' => [
                    'Follow order of operations.',
                    'Use integer rules for each step.'
                ]
            ]
        ];

        foreach ($mnemonics as $m) {
            Mnemonic::create($m);
        }
    }
}

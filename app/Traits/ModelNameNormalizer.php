<?php

namespace App\Traits;

trait ModelNameNormalizer
{
    public function normalizeModelName(string $gradable): string
    {
        $modelMap = [
            'pre-seminar' => 'PreSeminar',
            'exam' => 'Exam',
            'seminar' => 'Seminar',
        ];

        $modelName = $modelMap[$gradable] ?? str_replace(' ', '', ucwords(str_replace('-', ' ', $gradable)));

        return 'App\\Models\\' . $modelName;
    }
}

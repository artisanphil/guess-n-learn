<?php

namespace App\Services\Interfaces;

interface SentenceInterface
{
    public function __construct();

    public function handle(string $chosenAttribute, string $correctSentence): array;
}

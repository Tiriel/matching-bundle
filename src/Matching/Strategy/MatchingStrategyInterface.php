<?php

namespace App\Matching\Strategy;

use App\Interface\MatchableUserInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(tags: ['app.matching_strategy'], lazy: true)]
interface MatchingStrategyInterface
{
    public function match(MatchableUserInterface $user): iterable;

    public static function getName(): string;
}

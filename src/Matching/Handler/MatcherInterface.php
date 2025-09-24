<?php

namespace App\Matching\Handler;

use App\Interface\MatchableUserInterface;

interface MatcherInterface
{
    public function match(MatchableUserInterface $user, string $strategyName): iterable;
}

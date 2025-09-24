<?php

namespace Tiriel\MatchingBundle\Matching\Handler;

use Tiriel\MatchingBundle\Interface\MatchableUserInterface;

interface MatcherInterface
{
    public function match(MatchableUserInterface $user, string $strategyName): iterable;
}

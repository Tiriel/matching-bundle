<?php

namespace Tiriel\MatchingBundle\Matching\Ranking;

use Tiriel\MatchingBundle\Interface\MatchableUserInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;

class RankingService
{
    public function __construct(
        #[AutowireLocator('app.ranking_strategy')]
        private iterable $strategies
    ) {}

    public function rankMatchings(MatchableUserInterface $user, iterable $matchings, string $strategy): iterable
    {
        if (!\class_exists($strategy)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $strategy));
        }

        return $this->strategies->get($strategy)->rank($user, $matchings);
    }
}

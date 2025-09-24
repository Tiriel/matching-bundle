<?php

namespace Tiriel\MatchingBundle\Matching\Strategy;

use Psr\Log\LoggerInterface;
use Tiriel\MatchingBundle\Interface\MatchableEntityInterface;
use Tiriel\MatchingBundle\Interface\MatchableUserInterface;

class TraceableMatchingStrategy implements MatchingStrategyInterface
{
    public function __construct(
        protected readonly MatchingStrategyInterface $inner,
        protected readonly LoggerInterface $logger,
    ) {}

    public function match(MatchableUserInterface $user): iterable
    {
        $matches = $this->inner->match($user);
        $ids = \array_map(fn(MatchableEntityInterface $match) => $match->getId(), (array) $matches);

        $this->logger->info(sprintf('Matched user "%s" with strategy "%s" (ids: %s)',
            $user->getId(),
            $this->inner::class,
            \implode(', ', $ids)
        ));

        return $matches;
    }
}

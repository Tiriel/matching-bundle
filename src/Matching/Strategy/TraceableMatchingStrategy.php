<?php

namespace Tiriel\MatchingBundle\Matching\Strategy;

use Psr\Log\LoggerInterface;
use Tiriel\MatchingBundle\Interface\MatchableEntityInterface;
use Tiriel\MatchingBundle\Interface\MatchableUserInterface;

class TraceableMatchingStrategy implements MatchingStrategyInterface
{
    protected static string $name;

    public function __construct(
        protected readonly MatchingStrategyInterface $inner,
        protected readonly LoggerInterface $logger,
    ) {
        static::$name = $this->inner->getName();
    }

    public function match(MatchableUserInterface $user): iterable
    {
        $matches = $this->inner->match($user);
        $ids = \array_map(fn(MatchableEntityInterface $match) => $match->getId(), (array) $matches);

        $this->logger->info(sprintf('Matched user "%s" with objects of type "%s" (ids: %s)',
            $user->getId(),
            static::getName(),
            \implode(', ', $ids)
        ));

        return $matches;
    }

    public static function getName(): string
    {
        return static::$name;
    }
}

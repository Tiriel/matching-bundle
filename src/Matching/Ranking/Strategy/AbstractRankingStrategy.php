<?php

namespace Tiriel\MatchingBundle\Matching\Ranking\Strategy;

use Tiriel\MatchingBundle\Interface\MatchableEntityInterface;
use Tiriel\MatchingBundle\Interface\MatchableUserInterface;

abstract class AbstractRankingStrategy implements RankingStrategyInterface
{
    public function rank(MatchableUserInterface $user, iterable $matchings): iterable
    {
        $userMatchable = $this->getMatchableFromUser($user);
        $userMatchableNames = [];

        // Extract matchable names from user matchables
        foreach ($userMatchable as $matchable) {
            /** @var MatchableEntityInterface $matchable */
            $userMatchableNames[] = $matchable->getName();
        }

        // Convert iterable to array for sorting
        $matches = iterator_to_array($matchings);

        // Sort objects by number of matching entities (descending)
        usort($matches, function (object $a, object $b) use ($userMatchableNames) {
            $aMatches = $this->countMatchingEntities($a, $userMatchableNames);
            $bMatches = $this->countMatchingEntities($b, $userMatchableNames);

            return $bMatches <=> $aMatches; // Descending order
        });

        return $matches;
    }

    private function countMatchingEntities(object $entity, array $userMatchableNames): int
    {
        $matches = 0;

        foreach ($this->getMatchablesFromEntity($entity) as $matchable) {
            if (!$matchable instanceof MatchableEntityInterface) {
                throw new \InvalidArgumentException(sprintf("Object must implement %s to be matchable", MatchableEntityInterface::class));
            }

            if (in_array($matchable->getName(), $userMatchableNames, true)) {
                $matches++;
            }
        }

        return $matches;
    }

    abstract public function getMatchableFromUser(MatchableUserInterface $user): array;

    abstract public function getMatchablesFromEntity(object $entity): iterable;
}

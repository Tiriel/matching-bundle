<?php

namespace Tiriel\MatchingBundle\Matching\Strategy;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Tiriel\MatchingBundle\Interface\MatchableEntityInterface;
use Tiriel\MatchingBundle\Interface\MatchableUserInterface;

abstract class AbstractMatchingStrategy implements MatchingStrategyInterface
{
    protected ?ServiceEntityRepositoryInterface $repository = null;

    public function match(MatchableUserInterface $user): iterable
    {
        $ids = $this->getMatchableIdsFromUser($user);
        $basePrefix = substr($this->getBaseEntityName(), 0, 1);
        $matchPrefix = substr($this->getMatchableName(), 0, 1);

        $qb = $this->repository->createQueryBuilder($basePrefix);

        return $qb
            ->innerJoin(sprintf("%s.%s", $basePrefix, $this->getMatchableName()), $matchPrefix)
            ->where($qb->expr()->in(sprintf("%s.id", $matchPrefix), ':ids'))
            ->setParameter('ids', $ids)
            ->groupBy(sprintf("%s.id", $basePrefix))
            ->orderBy($qb->expr()->count(sprintf("%s.id", $matchPrefix), ':ids'), 'DESC')
            ->getQuery()
            ->getResult();
    }

    protected function getMatchableIdsFromUser(MatchableUserInterface $user): array
    {

        $matchables = iterator_to_array($this->getMatchablesFromUser($user));

        return \array_map(function (MatchableEntityInterface $matchable) {
            return $matchable->getId();
        }, $matchables);
    }

    abstract protected function getMatchablesFromUser(MatchableUserInterface $user);

    abstract protected function getMatchableName(): string;

    abstract protected function getBaseEntityName(): string;
}

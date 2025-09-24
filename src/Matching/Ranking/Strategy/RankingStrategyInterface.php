<?php

namespace Tiriel\MatchingBundle\Matching\Ranking\Strategy;

use Tiriel\MatchingBundle\Interface\MatchableUserInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('matching_bundle.ranking_strategy')]
interface RankingStrategyInterface
{
    public function rank(MatchableUserInterface $user, iterable $matchings): iterable;
}

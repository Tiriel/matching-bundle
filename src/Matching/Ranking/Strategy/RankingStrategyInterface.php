<?php

namespace App\Matching\Ranking\Strategy;

use App\Interface\MatchableUserInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.ranking_strategy')]
interface RankingStrategyInterface
{
    public function rank(MatchableUserInterface $user, iterable $matchings): iterable;
}

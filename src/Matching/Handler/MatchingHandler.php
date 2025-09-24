<?php

namespace App\Matching\Handler;

use App\Interface\MatchableUserInterface;
use App\Matching\Ranking\RankingService;
use App\Matching\Strategy\MatchingStrategyInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

#[AsAlias]
class MatchingHandler implements MatcherInterface
{
    public function __construct(
        /**
         * @var MatchingStrategyInterface[]
         */
        #[AutowireIterator('app.matching_strategy', defaultIndexMethod: 'getName')]
        private iterable $strategies, // ['tag'=> new TagBasedStrategy, ...]
        private readonly RankingService $ranking,
    )
    {
        $this->strategies = $strategies instanceof \Traversable ? iterator_to_array($strategies) : $strategies;
    }

    public function match(MatchableUserInterface $user, string $strategyName): iterable
    {
        $rankingStrategy = sprintf("App\\Matching\\Ranking\\Strategy\\%sRankingStrategy", ucfirst($strategyName));
        $rawMatchings = $this->strategies[$strategyName]->match($user);

        return $this->ranking->rankMatchings($user, $rawMatchings, $rankingStrategy);
    }
}

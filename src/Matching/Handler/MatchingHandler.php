<?php

namespace Tiriel\MatchingBundle\Matching\Handler;

use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;
use Tiriel\MatchingBundle\Interface\MatchableUserInterface;
use Tiriel\MatchingBundle\Matching\Ranking\RankingService;
use Tiriel\MatchingBundle\Matching\Strategy\MatchingStrategyInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias]
class MatchingHandler implements MatcherInterface
{
    private string $strategyNamespace = '';

    private string $rankingNamespace = '';

    public function __construct(
        /**
         * @var MatchingStrategyInterface[]
         */
        #[AutowireLocator('matching_bundle.matching_strategy')]
        private iterable $strategies,
        private readonly RankingService $ranking,
    ) {}

    public function match(MatchableUserInterface $user, string $strategyName): iterable
    {
        $matchingStrategy = sprintf('%s\\%sMatchingStrategy',strtr($this->strategyNamespace, '\\', '\\\\') , ucfirst($strategyName));
        $rankingStrategy = sprintf('%s\\%sRankingStrategy',strtr($this->rankingNamespace, '\\', '\\\\') , ucfirst($strategyName));
        $rawMatchings = $this->strategies->get($matchingStrategy)->match($user);

        return $this->ranking->rankMatchings($user, $rawMatchings, $rankingStrategy);
    }

    public function setStrategyNamespace(string $strategyNamespace): void
    {
        $this->strategyNamespace = $strategyNamespace;
    }

    public function setRankingNamespace(string $rankingNamespace): void
    {
        $this->rankingNamespace = $rankingNamespace;
    }
}

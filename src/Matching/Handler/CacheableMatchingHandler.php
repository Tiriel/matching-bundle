<?php

namespace App\Matching\Handler;

use App\Interface\MatchableUserInterface;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[AsDecorator(MatcherInterface::class)]
class CacheableMatchingHandler implements MatcherInterface
{
    public function __construct(
        private readonly MatcherInterface $inner,
        private readonly CacheInterface $apiCache,
        private readonly SluggerInterface $slugger,
    ) {}

    public function match(MatchableUserInterface $user, string $strategyName): iterable
    {
        $key = $this->slugger->slug(sprintf("matching_%s_%s", $user->getUserIdentifier(), $strategyName));

        return $this->apiCache->get((string) $key, function (ItemInterface $item) use ($user, $strategyName) {
            $item->expiresAfter(300);

            return $this->inner->match($user, $strategyName);
        });
    }
}

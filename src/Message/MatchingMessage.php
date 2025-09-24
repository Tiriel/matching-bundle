<?php

namespace Tiriel\MatchingBundle\Message;

use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage('sync')]
final class MatchingMessage
{
    public function __construct(
        public readonly int $userId,
        public readonly string $strategyName,
    ) {
    }
}

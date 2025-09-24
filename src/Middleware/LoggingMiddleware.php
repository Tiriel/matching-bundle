<?php

namespace App\Middleware;

use App\Message\MatchingMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

#[AutoconfigureTag('messenger.middleware')]
final class LoggingMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();

        if ($message instanceof MatchingMessage) {
            $this->logger->info(sprintf("New message : %s", \json_encode($message)));
        }

        return $stack->next()->handle($envelope, $stack);
    }
}

<?php

namespace Tiriel\MatchingBundle\MessageHandler;

use Tiriel\MatchingBundle\Matching\Handler\MatcherInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class MatchingMessageHandler
{
    private ?ServiceEntityRepositoryInterface $userRepository = null;

    public function __construct(
        private readonly MatcherInterface $handler,
    ) {}

    public function __invoke(MatchingMessage $message): void
    {
        if (null === $this->userRepository) {
            throw new \LogicException("UserRepository is missing from configuration.");
        }

        $user = $this->userRepository->find($message->userId);
        if (null === $user) {
            throw new \InvalidArgumentException('User not found');
        }

        $strategy = $this->handler->match($user, $message->strategyName);

        dump($strategy);
    }

    public function setUserRepository(ServiceEntityRepositoryInterface $userRepository): void
    {
        $this->userRepository = $userRepository;
    }
}

<?php

namespace Tiriel\MatchingBundle\Interface;

interface MatchableEntityInterface
{
    public function getId(): mixed;

    public function getName(): ?string;
}

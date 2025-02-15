<?php

namespace Core\Clock;

/**
 * Class ClockInterface
 * Implements the ClockInterface to provide the current time.
 */
interface ClockInterface
{
    /**
     * Get the current time as a DateTimeImmutable object.
     *
     * @return \DateTimeImmutable
     */
    public function now(): \DateTimeImmutable;
}

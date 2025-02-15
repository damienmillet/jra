<?php

namespace Core\Clock;

/**
 * Class Clock
 */
class Clock implements ClockInterface
{
    /**
     * Returns the current time as a DateTimeImmutable object.
     *
     * @return \DateTimeImmutable
     */
    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }
}

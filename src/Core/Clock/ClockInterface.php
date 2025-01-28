<?php

/**
 * Clock file for defining the Clock Interface.
 * php version 8.2
 *
 * @category Core
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */

namespace Core\Clock;

/**
 * Class ClockInterface
 * Implements the ClockInterface to provide the current time.
 *
 * @category Core
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
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

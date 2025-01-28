<?php

/**
 * Event file for defining the ListenerProvider Interface.
 * php version 8.2
 *
 * @category Core
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */

namespace Core\Event;

/**
 * Interface ListenerProviderInterface
 *
 * @category Core
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */
interface ListenerProviderInterface
{
    /**
     * Get the listeners for a specific event.
     *
     * @param object $event An event for which to return the relevant listeners.
     *
     * @return iterable<callable> An iterable (array, iterator, or generator)
     *   of callables.
     *   Each callable MUST be type-compatible with $event.
     */
    public function getListenersForEvent(object $event): iterable;
}

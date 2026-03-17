<?php
/**
 * Lazy process handler for arrays (last modified: 2026.03.17).
 *
 * This file is a part of the "common classes package", utilised by a number of
 * packages and projects, including CIDRAM and phpMussel.
 * @link https://github.com/Maikuolan/Common
 *
 * License: GNU/GPLv2
 * @see LICENSE.txt
 *
 * "COMMON CLASSES PACKAGE" COPYRIGHT 2019 and beyond by Caleb Mazalevskis.
 * *This particular class*, COPYRIGHT 2026 and beyond by Caleb Mazalevskis.
 */

namespace Maikuolan\Common;

class LazyArray extends CommonAbstract implements \ArrayAccess, \Countable, \IteratorAggregate
{
    /**
     * @var array The array to be populated by the closure.
     */
    public $Data = [];

    /**
     * @var bool Whether the closure has been triggered already.
     */
    private $Triggered = false;

    /**
     * @var callable|null The closure to call when triggered.
     */
    private $Closure = null;

    /**
     * @var mixed The raw data to be processed by the closure.
     */
    private $Raw = null;

    /**
     * Construct the object.
     *
     * @param callable $Closure The closure to call when triggered.
     * @param mixed $Raw The raw data to be processed by the closure.
     * @return void
     */
    public function __construct(callable $Closure, $Raw)
    {
        $this->Closure = $Closure;
        $this->Raw = $Raw;
    }

    /**
     * Trigger.
     *
     * @return void
     */
    public function trigger(): void
    {
        if ($this->Triggered) {
            return;
        }
        $this->Data = \call_user_func($this->Closure, $this->Raw);
        $this->Triggered = true;
        $this->Closure = null;
        $this->Raw = null;
    }

    /**
     * Check whether an element exists.
     *
     * @param mixed $Offset The offset.
     * @return bool Whether it exists.
     */
    public function offsetExists($Offset): bool
    {
        $this->trigger();
        if (!\is_scalar($Offset) || !\is_array($this->Data)) {
            return false;
        }
        return isset($this->Data[$Offset]);
    }

    /**
     * Fetch an element.
     *
     * Note: "ReturnTypeWillChange" applied due to that "mixed" as a return type
     * keyword was introduced only since PHP8, and Common Classes Package v2 is
     * supposed to be compatible with PHP7.2 onward. We can refactor the attribute
     * out and the return type keyword in for a future major version (e.g., CCPv3).
     *
     * @param mixed $Offset The offset.
     * @throws OutOfBoundsException if the requested element does not exist.
     * @return mixed The fetched element.
     */
    #[\ReturnTypeWillChange]
    public function &offsetGet($Offset)
    {
        if (!$this->offsetExists($Offset)) {
            throw new \OutOfBoundsException('The requested element does not exist');
        }
        return $this->Data[$Offset];
    }

    /**
     * Set an element.
     *
     * @param mixed $Offset The offset.
     * @param mixed $Value The element value.
     * @return void
     */
    public function offsetSet($Offset, $Value): void
    {
        $this->trigger();
        if (!\is_array($this->Data)) {
            return;
        }
        $this->Data[$Offset] = $Value;
    }

    /**
     * Destroy an element.
     *
     * @param mixed $Offset The offset.
     * @return void
     */
    public function offsetUnset($Offset): void
    {
        $this->trigger();
        if (!\is_array($this->Data)) {
            return;
        }
        unset($this->Data[$Offset]);
    }

    /**
     * Count cache entries.
     *
     * @return int The number of cache entries attached to the current instance.
     */
    public function count(): int
    {
        $this->trigger();
        return \is_array($this->Data) || ($this->Data instanceof \Countable) ? \count($this->Data) : 0;
    }

    /**
     * Allows foreach to iterate over the processed data.
     *
     * @return \Traversable An instance of \ArrayIterator.
     */
    public function getIterator(): \Traversable
    {
        $this->trigger();
        return new \ArrayIterator($this->Data);
    }

    /**
     * Necessary for tests because closures can't be serialised (i.e., $this->Closure).
     *
     * @return array The key-value pairs to be serialised.
     */
    public function __serialize(): array
    {
        $this->trigger();
        return $this->Data;
    }

    /**
     * Necessary for tests because closures can't be serialised (i.e., $this->Closure).
     *
     * @param array $Data The key-value pairs to be unserialised.
     * @return void
     */
    public function __unserialize(array $Data): void
    {
        $this->Data = $Data;
        $this->Triggered = true;
        $this->Closure = null;
        $this->Raw = null;
    }
}

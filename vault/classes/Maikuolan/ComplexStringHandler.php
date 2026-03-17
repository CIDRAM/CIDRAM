<?php
/**
 * Complex string handler (last modified: 2026.03.17).
 *
 * This file is a part of the "common classes package", utilised by a number of
 * packages and projects, including CIDRAM and phpMussel.
 * @link https://github.com/Maikuolan/Common
 *
 * License: GNU/GPLv2
 * @see LICENSE.txt
 *
 * "COMMON CLASSES PACKAGE", as well as the earliest iteration and deployment
 * of this class, COPYRIGHT 2019 and beyond by Caleb Mazalevskis (Maikuolan).
 */

namespace Maikuolan\Common;

class ComplexStringHandler extends CommonAbstract implements \ArrayAccess, \Countable, \IteratorAggregate
{
    /**
     * @var string Supplied to the class at object instantiation or thereafter.
     */
    public $Input = '';

    /**
     * @var array The data to be worked upon by the class.
     */
    private $Working = [];

    /**
     * @var array Markers and pattern matches defined by generateMarkers.
     */
    private $Markers = [];

    /**
     * Constructor.
     *
     * @param string $Data The data supplied to the class at object instantiation.
     * @param string $Pattern An optional pattern to immediately call $this->generateMarkers.
     * @param callable|null $Closure An optional closure to immediately call $this->iterateClosure.
     * @return void
     */
    public function __construct(string $Data = '', string $Pattern = '', ?callable $Closure = null)
    {
        if ($Data !== '') {
            $this->Input = $Data;
            if ($Pattern !== '') {
                $this->generateMarkers($Pattern);
                if (\is_callable($Closure)) {
                    $this->iterateClosure($Closure);
                }
            }
        }
    }

    /**
     * PHP's magic "__toString" method to act as an alias for "recompile".
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->recompile();
    }

    /**
     * Generate markers and working data.
     *
     * @param string $Pattern The pattern to use to generate the markers.
     * @return void
     */
    public function generateMarkers(string $Pattern): void
    {
        \preg_match_all($Pattern, $this->Input, $this->Markers, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
        $Start = 0;
        $this->Working = [];
        foreach ($this->Markers as $Marker) {
            if (!\is_array($Marker[0]) || !isset($Marker[0][0]) || \is_array($Marker[0][0]) || !isset($Marker[0][1])) {
                break;
            }
            $this->Working[] = \substr($this->Input, $Start, $Marker[0][1] - $Start);
            $Start = $Marker[0][1] + \strlen($Marker[0][0]);
        }
        $this->Working[] = \substr($this->Input, $Start);
    }

    /**
     * Iterate over the working data using a given closure.
     *
     * @param callable $Closure
     * @param bool $Glue Whether to work on the markers or the working data.
     * @return void
     */
    public function iterateClosure(callable $Closure, bool $Glue = false): void
    {
        if (empty($this->Input)) {
            return;
        }
        if (!$Glue) {
            foreach ($this->Working as &$Segment) {
                $Segment = $Closure($Segment);
            }
            return;
        }
        foreach ($this->Markers as &$Marker) {
            if (isset($Marker[0][0]) && !\is_array($Marker[0][0])) {
                $Marker[0][0] = $Closure($Marker[0][0]);
            }
        }
    }

    /**
     * Recompile all data after all work has finished and return it.
     *
     * @return string
     */
    public function recompile(): string
    {
        $Output = '';
        $Glue = 0;
        foreach ($this->Working as $Segment) {
            if (!\is_string($Segment)) {
                $Segment = \is_scalar($Segment) ? (string)$Segment : '';
            }
            $Output .= $Segment;
            if (isset($this->Markers[$Glue][0][0]) && !\is_array($this->Markers[$Glue][0][0])) {
                if (!\is_string($this->Markers[$Glue][0][0])) {
                    $this->Markers[$Glue][0][0] = \is_scalar($this->Markers[$Glue][0][0]) ? (string)$this->Markers[$Glue][0][0] : '';
                }
                $Output .= $this->Markers[$Glue][0][0];
                $Glue++;
            }
        }
        return $Output;
    }

    /**
     * Check whether a segment exists via array access.
     *
     * @param mixed $Segment The segment.
     * @return bool Whether it exists.
     */
    public function offsetExists($Segment): bool
    {
        return \is_scalar($Segment) && isset($this->Working[$Segment]);
    }

    /**
     * Fetch a segment via array access.
     *
     * Note: "ReturnTypeWillChange" applied due to that "mixed" as a return type
     * keyword was introduced only since PHP8, and Common Classes Package v2 is
     * supposed to be compatible with PHP7.2 onward. We can refactor the attribute
     * out and the return type keyword in for a future major version (e.g., CCPv3).
     *
     * @param mixed $Segment The segment.
     * @return mixed The fetched segment.
     */
    #[\ReturnTypeWillChange]
    public function &offsetGet($Segment)
    {
        return $this->Working[$Segment];
    }

    /**
     * Set a segment via array access.
     *
     * @param mixed $Segment The segment.
     * @param mixed $Value The segment value.
     * @return void
     */
    public function offsetSet($Segment, $Value): void
    {
        if (!\is_scalar($Segment) || !\is_scalar($Value)) {
            return;
        }
        $this->Working[$Segment] = (string)$Value;
    }

    /**
     * Destroy a segment via array access.
     *
     * @param mixed $Offset The segment.
     * @return void
     */
    public function offsetUnset($Segment): void
    {
        if (!\is_scalar($Segment)) {
            return;
        }
        $this->Working[$Segment] = null;
    }

    /**
     * Count the number of segments in the working data.
     *
     * @return int The number of segments in the working data.
     */
    public function count(): int
    {
        return \count($this->Working);
    }

    /**
     * Allows foreach to iterate over working data via \IteratorAggregate.
     *
     * @return \Traversable An instance of \ArrayIterator.
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->Working);
    }
}

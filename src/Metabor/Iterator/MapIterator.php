<?php
namespace Metabor\Iterator;
use MetaborStd\CallbackInterface;

/**
 * @author Oliver Tischlinger
 *
 */
class MapIterator extends \IteratorIterator
{

    /**
     * @var CallbackInterface
     */
    private $callback;

    /**
     * @param CallbackInterface $callback Callback to map current value
     */
    public function __construct(\Traversable $iterator,
            CallbackInterface $callback)
    {
        parent::__construct($iterator);
        $this->callback = $callback;
    }

    /**
     * 
     * @see IteratorIterator::current()
     */
    public function current()
    {
        return $this->callback->__invoke(parent::current());
    }
}

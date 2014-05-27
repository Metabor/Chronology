<?php
namespace Metabor\Chronology;
use MetaborStd\CallbackInterface;

/**
 * @author Oliver Tischlinger
 *
 */
class CombineTimelinesIterator implements \Iterator
{

    /**
     * @var \SplObjectStorage
     */
    private $timelines;

    /**
     * @var \DateTime
     */
    private $currentDate;

    /**
     * 
     */
    public function __construct()
    {
        $this->timelines = new \SplObjectStorage();
    }

    /**
     * @param \Iterator $timeline
     */
    public function attach(\Iterator $timeline)
    {
        $this->timelines->attach($timeline);
    }

    /**
     * @param \Iterator $timeline
     */
    public function detach(\Iterator $timeline)
    {
        $this->timelines->detach($timeline);
    }

    /**
     * @see Iterator::current()
     */
    public function current()
    {
    	$date = $this->currentDate;
    	$callback = function (\Iterator $timeline) use ($date)
    	{
    		return ($timeline->valid()
    				&& ($timeline->current()->getDate() == $date));
    	};
    	return new \CallbackFilterIterator($this->timelines, $callback);
    }

    /**
     * current timestamp
     * (DateTime as key is invalid until PHP 5.5)
     * @see Iterator::key()
     */
    public function key()
    {
        return $this->currentDate->getTimestamp();
    }

    protected function determineCurrentDate()
    {
        $dates = array();
        foreach ($this->timelines as $timeline) {
        	if ($timeline->valid()) {
        		$dates[] = $timeline->current()->getDate();
        	}
        }
        $this->currentDate = min($dates);
    }

    /**
     * @see Iterator::next()
     */
    public function next()
    {
        foreach ($this->current() as $timeline) {
            $timeline->next();
        }
        $this->determineCurrent();
    }

    /**
     * @see Iterator::rewind()
     */
    public function rewind()
    {
        foreach ($this->timelines as $timeline) {
            $timeline->rewind();
        }
        $this->determineCurrent();
    }

    /**
     * @see Iterator::valid()
     */
    public function valid()
    {
        foreach ($this->timelines as $timeline) {
            if ($timeline->valid()) {
                return true;
            }
        }
        return false;
    }

}
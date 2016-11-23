<?php
/**
 * Created by PhpStorm.
 * User: zhaoqing
 * Date: 16/11/23
 * Time: 下午5:22
 */

namespace Comos\Drapper;


class CollectionIterator implements \Iterator
{
    /**
     * @var Collection
     */
    protected $collection;

    protected $index = 0;

    /**
     * CollectionIterator constructor.
     * @param Collection $this
     */
    public function __construct($collection)
    {
        $this->collection = $collection;
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return Bean
     * @since 5.0.0
     */
    public function current()
    {
        return $this->collection->getBeanAt($this->index);
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        ++$this->index;
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return $this->collection->hasBeanAt($this->index);
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->index = 0;
    }
}
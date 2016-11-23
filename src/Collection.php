<?php
/**
 * Created by PhpStorm.
 * User: zhaoqing
 * Date: 16/11/19
 * Time: 下午4:25
 */

namespace Comos\Drapper;

use Comos\Drapper\Exceptions\TypeErrorException;

class Collection extends DataContainer implements \Countable, \IteratorAggregate
{
    protected function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param array|\ArrayAccess $data
     * @return Collection
     * @throws TypeErrorException
     */
    public static function fromArray($data)
    {
        if (!is_array($data) && !($data instanceof \ArrayAccess && $data instanceof \Countable)) {
            throw new TypeErrorException('the argument must be array or ArrayAccess');
        }
        return new Collection($data);
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * @param $index
     * @return Bean
     */
    public function getBeanAt($index)
    {
        if (!isset($this->subs[$index])) {
            $this->subs[$index] = $this->generateSubBean($index, true);
        }
        return $this->subs[$index];
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return \Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new CollectionIterator($this);
    }

    public function hasBeanAt($index)
    {
        return key_exists($index, $this->data);
    }
}
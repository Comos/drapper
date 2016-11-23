<?php
/**
 * Created by PhpStorm.
 * User: zhaoqing
 * Date: 16/11/21
 * Time: 下午3:59
 */

namespace Comos\Drapper;

use Comos\Drapper\Exceptions\OutOfBoundsException;
use Comos\Drapper\Exceptions\TypeErrorException;

abstract class DataContainer
{
    /**
     *
     * @var array
     */
    protected $data;
    /**
     * @var Bean[]
     */
    protected $subs;

    /**
     * @return array
     */
    public function rawData()
    {
        return $this->data;
    }

    /**
     * @param $key
     * @param bool $strict
     * @return Bean
     * @throws \TypeError
     */
    protected function generateSubBean($key, $strict = false)
    {
        if (!array_key_exists($key, $this->data)) {
            if ($strict) {
                throw new OutOfBoundsException('sub node does not exist. FIELD['.$key.']');
            }
            return Bean::fromArray([]);
        }
        $arr = $this->data[$key];
        if (is_array($arr)) {
            return Bean::fromArray($arr);
        }
        if (is_object($arr)) {
            return Bean::fromArray((array)$arr);
        }
        throw new TypeErrorException('type error, expects array or object. FIELD[' . $key.']');
    }
}
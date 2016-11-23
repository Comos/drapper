<?php
/**
 * Created by PhpStorm.
 * User: zhaoqing2
 * Date: 15/8/6
 * Time: 下午4:32
 */

namespace Comos\Drapper;

use Comos\Drapper\Exceptions\Exception;
use Comos\Drapper\Exceptions\TypeErrorException;

class Bean extends DataContainer
{


    /**
     * @var Collection
     */
    protected $subCollections;

    /**
     * @param array|\ArrayAccess $data
     * @return Bean
     * @throws TypeErrorException
     */
    public static function fromArray($data)
    {
        if (!is_array($data) && !$data instanceof \ArrayAccess) {
            throw new TypeErrorException('the argument must be array or ArrayAccess');
        }
        return new Bean($data);
    }

    /**
     *
     * @param array $data
     */
    protected function __construct($data)
    {
        $this->data = $data;
    }

    public function strictSub($key) {
        if (!isset($this->subs[$key])) {
            $this->subs[$key] = $this->generateSubBean($key, true);
        }
        return $this->subs[$key];
    }

    public function sub($key)
    {
        if (!isset($this->subs[$key])) {
            $this->subs[$key] = $this->generateSubBean($key);
        }
        return $this->subs[$key];
    }

    /**
     * @return Collection
     */
    public function collection($key)
    {
        if (!isset($this->subCollections[$key])) {
            $data = isset($this->data[$key]) ? $this->data[$key] : [];
            $this->subCollections[$key] = Collection::fromArray($data);
        }
        return $this->subCollections[$key];
    }

    /**
     * @return array
     */
    public function keys()
    {
        return array_keys($this->data);
    }

    /**
     * @param mixed $key
     * @param mixed $default
     * @return null|string
     * @throws Exception
     */
    public function str($key, $default = null)
    {
        if (!array_key_exists($key, $this->data)) {
            return $default;
        }

        $value = $this->data[$key];

        if (is_null($value)) {
            return null;
        }


        if (is_string($value)) {
            return $value;
        }

        if (is_object($value) && method_exists($value, '__toString')) {
            return strval($value);
        }

        if (is_int($value) || is_float($value) || is_bool($value)) {
            return strval($value);
        }
        throw new TypeErrorException('type error, field: ' . $key);
    }

    public function int($key, $default = null)
    {
        if (!array_key_exists($key, $this->data)) {
            return $default;
        }
        $value = $this->data[$key];

        if (is_null($value)) {
            return null;
        }

        if (is_int($value)) {
            return $value;
        }

        if (is_float($value)) {
            return intval($value);
        }

        if (is_string($value) && is_numeric($value)) {
            return intval($value);
        }

        throw new TypeErrorException('type error, field: ' . $key);
    }

    /**
     * @param $key
     * @param mixed $default
     * @return float|null
     * @throws TypeErrorException
     */
    public function float($key, $default = null)
    {
        if (!array_key_exists($key, $this->data)) {
            return $default;
        }
        $value = $this->data[$key];

        if (is_null($value)) {
            return null;
        }

        if (is_float($value)) {
            return $value;
        }

        if (is_int($value)) {
            return floatval($value);
        }

        if (is_string($value) && is_numeric($value)) {
            return floatval($value);
        }

        throw new TypeErrorException('type error, field: ' . $key);
    }

    /**
     * @param $key
     * @param mixed $default
     * @return bool|null
     * @throws TypeErrorException
     */
    public function bool($key, $default = null)
    {
        if (!array_key_exists($key, $this->data)) {
            return $default;
        }
        $value = $this->data[$key];

        if (is_null($value)) {
            return null;
        }
        
        if (is_bool($value)) {
            return $value;
        }

        if (is_int($value) || is_float($value)) {
            return boolval($value);
        }

        if (is_string($value)) {
            if (strlen($value) == 5 && strtolower($value) == 'false') {
                return false;
            }
            return boolval($value);
        }

        throw new TypeErrorException('type error, field: ' . $key);
    }

    /**
     * get string field value in strict mode.
     * @param mixed $key
     * @throws Exception
     * @return string
     */
    public function strictStr($key)
    {
        return $this->strictValue($key, 'str');
    }

    /**
     * @param $key
     * @return int|null
     * @throws Exception
     */
    public function strictInt($key)
    {
        return $this->strictValue($key, 'int');
    }

    /**
     * @param $key
     * @return float|null
     * @throws Exception
     */
    public function strictFloat($key)
    {
        return $this->strictValue($key, 'float');
    }

    /**
     * @param $key
     * @return boolean|null
     * @throws Exception
     */
    public function strictBool($key)
    {
        return $this->strictValue($key, 'bool');
    }

    /**
     * @param $key
     * @param $typeMethod
     * @return mixed
     * @throws Exception
     */
    protected function strictValue($key, $typeMethod)
    {
        $value = $this->$typeMethod($key);
        if (is_null($value)) {
            throw new Exception('miss required field: ' . $key);
        }
        return $value;
    }


}
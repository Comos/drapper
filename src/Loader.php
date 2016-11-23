<?php
/**
 * Created by PhpStorm.
 * User: zhaoqing
 * Date: 16/4/19
 * Time: 下午10:30
 */

namespace Comos\Drapper;


use Comos\Drapper\Exceptions\Exception;

class Loader
{
    /**
     * @param $pathToJsonFile
     * @return Bean
     * @throws Exception
     */
    public static function fromJsonFile($pathToJsonFile)
    {
        if (!file_exists($pathToJsonFile)) {
            throw new Exception("cannot find the conf file. \tFILE[$pathToJsonFile]");
        }
        $jsonString = @file_get_contents($pathToJsonFile);
        if ($jsonString === false) {
            throw new Exception("cannot read conf file.\tFILE[$pathToJsonFile]");
        }
        return self::fromJson($jsonString);
    }

    /**
     * @param $jsonString
     * @return Bean
     * @throws Exception
     */
    public static function fromJson($jsonString)
    {
        $data = @json_decode($jsonString, true);
        if (!is_array($data)) {
            throw new Exception('bad format');
        }
        return Bean::fromArray($data);
    }
} 
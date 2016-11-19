<?php
/**
 * Created by PhpStorm.
 * User: zhaoqing2
 * Date: 15/8/14
 * Time: 上午11:18
 */
require __DIR__.'/bootstrap.php';

var_dump(\Comos\Drapper\Bean::fromArray(['a'=>1, 'b'=>['c'=>'z']])->sub('b')->str('c'));

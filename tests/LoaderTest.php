<?php
/**
 * Created by PhpStorm.
 * User: zhaoqing
 * Date: 16/4/19
 * Time: 下午11:28
 */

namespace Comos\Drapper;


class LoaderTest extends \PHPUnit_Framework_TestCase
{
    protected $unreadableJsonFile;
    protected function setUp()
    {
        parent::setUp();
        $this->unreadableJsonFile = __DIR__.'/conf-unreadable.json';
        touch($this->unreadableJsonFile);
        chmod($this->unreadableJsonFile, 0077);
    }

    protected function tearDown()
    {
        unlink($this->unreadableJsonFile);
        parent::tearDown();
    }

    public function testFromJsonFile()
    {
        $jsonFile = __DIR__.'/conf.json';
        $conf = Loader::fromJsonFile($jsonFile);
        $this->assertInstanceOf(Bean::class, $conf);
        $this->assertEquals('1', $conf->strictStr('b'));
    }

    /**
     * @expectedException \Comos\Drapper\Exception
     * @expectedExceptionMessage cannot find the conf file
     */
    public function testFromJsonFile_CannotFindFile()
    {
        $jsonFile = __DIR__.'/conf-x.json';
        Loader::fromJsonFile($jsonFile);
    }
    /**
     * @expectedException \Comos\Drapper\Exception
     * @expectedExceptionMessage cannot read conf file
     */
    public function testFromJsonFile_FailToReadFile()
    {
        Loader::fromJsonFile($this->unreadableJsonFile);
    }
    /**
     * @expectedException \Comos\Drapper\Exception
     * @expectedExceptionMessage bad format
     */
    public function testFromJsonFile_BadFormat()
    {
        Loader::fromJsonFile(__DIR__.'/bad.json');
    }
}
 
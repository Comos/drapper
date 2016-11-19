<?php
/**
 * Created by PhpStorm.
 * User: zhaoqing2
 * Date: 15/8/6
 * Time: 下午4:36
 */

namespace Comos\Drapper;

class BeanTest extends \PHPUnit_Framework_TestCase
{

    public function testFromArray()
    {
        $this->assertTrue(Bean::fromArray(['a' => 1]) instanceof Bean);
        $this->assertTrue(Bean::fromArray(new \ArrayObject(['a' => 1])) instanceof Bean);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFromArray_InvalidArgument()
    {
        Bean::fromArray('x');
    }

    /**
     * @dataProvider getParamMethodsProvider
     */
    public function testGetParamMethods($method, $data, $key, $default, $expectedValue)
    {
        $result = Bean::fromArray($data)->$method($key, $default);
        $this->assertTrue($expectedValue === $result);
    }

    public function getParamMethodsProvider()
    {
        return [
            //$method, $data, $key, $default, $expectedValue
            ['str', ['a' => '1', 'b' => 2], 'a', null, '1'],
            ['str', ['a' => '1', 'b' => 2], 'b', null, '2'],
            ['str', ['a' => '1', 'b' => 2], 'c', null, null],
            ['str', ['a' => new __StringObj(), 'b' => 2], 'a', null, 'x'],
            ['str', ['a' => '1', 'b' => 2], 'c', 'x', 'x'],
            ['str', ['a', 'b', 'c'], 2, null, 'c'],
            ['str', ['a', 'b', 'c'], '2', null, 'c'],
            ['int', ['a' => 1], 'a', null, 1],
            ['int', ['a' => 1.0], 'a', null, 1],
            ['int', ['a' => "1.0"], 'a', null, 1],
            ['int', ['a' => "1.0"], 'b', null, null],
            ['float', ['a' => "1.0"], 'a', null, 1.0],
            ['float', ['a' => "1.0"], 'b', 1.1, 1.1],
            ['float', ['c' => 2], 'c', 1.1, 2.0],
            ['float', ['c' => 2], 'c', 1.1, 2.0],
            ['float', ['c' => 0], 'c', 1.1, 0.0],
            ['bool', ['c' => 0], 'c', null, false],
            ['bool', ['c' => 'true'], 'c', null, true],
            ['bool', ['c' => true], 'c', null, true],
            ['bool', ['c' => 1], 'c', null, true],
            ['bool', ['d' => false], 'd', null, false],
            ['bool', ['d' => 0], 'd', null, false],
            ['bool', ['c' => 'False'], 'c', null, false],
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetParamMethods_TypeError
     * @expectedException Exception
     * @expectedExceptionMessage type error
     */
    public function testGetParamMethods_TypeError($method, $data, $key)
    {
        Bean::fromArray($data)->$method($key);
    }

    public function dataProviderForTestGetParamMethods_TypeError()
    {
        return [
            ['strictInt', ['a' => 'x'], 'a'],
            ['strictInt', ['a' => []], 'a'],
            ['int', ['a' => false], 'a'],
            ['int', ['a' => 'x'], 'a'],
            ['float', ['a' => []], 'a'],
            ['float', ['a' => new \stdClass()], 'a'],
            ['float', ['a' => true], 'a'],
            ['bool', ['a' => []], 'a'],
            ['strictBool', ['a' => []], 'a'],
            ['bool', ['a' => new \stdClass()], 'a'],
        ];
    }

    /**
     * @dataProvider getParamMethodsProvider_RestrictMode_MissRequiredField_DataProvider
     * @expectedException Exception
     */
    public function testGetParamMethods_RestrictMode_MissRequiredField($method, $data, $key)
    {
        Bean::fromArray($data)->$method($key);
    }

    public function getParamMethodsProvider_RestrictMode_MissRequiredField_DataProvider()
    {
        return [
            //$method, $data, $key
            ['strictStr', ['a' => '1', 'b' => 2], 'c'],
            ['strictStr', ['a' => '1', 'b' => 2], 'x'],
            ['strictStr', ['a', 'b', 'c'], 3],
            ['strictStr', ['a', 'b', null], 2],
            ['strictInt', ['a' => 1], 'c'],
            ['strictInt', ['a', 'x'], 3],
            ['strictFloat', ['a', 'x'], 3],
            ['strictBool', ['a', 'x'], 3],
        ];
    }

    /**
     * @param string $method
     * @param array $data
     * @param string $key
     * @param string $expectedValue
     * @dataProvider dataProviderForGetParamMethods_RestrictMode
     */
    public function testGetParamMethods_RestrictMode($method, $data, $key, $expectedValue)
    {
        $result = Bean::fromArray($data)->$method($key);
        $this->assertTrue($expectedValue === $result);
    }

    public function dataProviderForGetParamMethods_RestrictMode()
    {
        return [
            //$method, $data, $key, $expectedValue
            ['strictStr', ['a' => '1', 'b' => 2], 'b', '2'],
            ['strictStr', ['a' => '1', 'b' => 2], 'a', '1'],
            ['strictStr', ['a' => new __StringObj(), 'b' => 2], 'a', 'x'],
            ['strictStr', ['a', 'b', 'c'], 2, 'c'],
            ['strictInt', ['a', '2', null], 1, 2],
            ['strictInt', ['a', '2', null], 1, 2],
            ['strictFloat', ['a' => '2'], 'a', 2.0],
            ['strictFloat', ['a' => 2], 'a', 2.0],
            ['strictFloat', ['a' => '2.1'], 'a', 2.1],
            ['strictFloat', ['a' => -1.111], 'a', -1.111],
            ['strictBool', ['a' => 'x'], 'a', true],
            ['strictBool', ['a' => '1'], 'a', true],
            ['strictBool', ['a' => '0'], 'a', false],
            ['strictBool', ['a' => 0], 'a', false],
        ];
    }

    public function testSub()
    {
        $data = [
            'a' => 1,
            'b' => ['2', 3, 4],
            'c' => ['a' => 1, 'b' => ['x' => 1, 'y' => 3]],
            'd' => null,
            'e' => (object)['f'=>true]
        ];
        $conf = Bean::fromArray($data);
        $this->assertTrue($conf->sub('b') === $conf->sub('b'));
        $this->assertEquals(1, $conf->int('a'));
        $this->assertEquals('2', $conf->sub('b')->str(0));
        $this->assertEquals('3', $conf->sub('c')->sub('b')->str('y'));
        $this->assertInstanceOf(Bean::class, $conf->sub('x'));
        $this->assertNull($conf->sub('x')->str('a'));
        $this->assertEquals(true, $conf->sub('e')->bool('f'));
    }

    public function testRsub()
    {
        $data = [
            'a' => 1,
            'b' => ['2', 3, 4],
            'c' => ['a' => 1, 'b' => ['x' => 1, 'y' => 3]],
            'd' => null,
        ];
        $conf = Bean::fromArray($data);
        $this->assertTrue($conf->strictSub('b') === $conf->strictSub('b'));
        $this->assertEquals('2', $conf->strictSub('b')->str(0));
        $this->assertEquals('3', $conf->strictSub('c')->strictSub('b')->str('y'));

        try {
            $conf->strictSub('a');
            $this->fail('expects Exception');
        } catch (Exception $ex) {
            $this->assertEquals('type error, expects array or object. FIELD[a]', $ex->getMessage());
        }

        try {
            $conf->strictSub('x');
            $this->fail('expects Exception');
        } catch (Exception $ex) {
            $this->assertEquals('sub node does not exist. FIELD[x]', $ex->getMessage());
        }
    }

    public function testGetKeys()
    {
        $data = [
            'a' => 1,
            'b' => ['2', 3, 4],
            'c' => ['a' => 1, 'b' => ['x' => 1, 'y' => 3]],
            'd' => null,
        ];
        $conf = Bean::fromArray($data);
        $this->assertEquals(['a', 'b', 'c', 'd'], $conf->keys());
    }

    public function testGetRawData()
    {
        $data = ['a' => 1, 'b'=>['x'=>'y']];
        $conf = Bean::fromArray($data);
        $this->assertEquals($data, $conf->rawData());
        $this->assertEquals(['x'=>'y'], $conf->sub('b')->rawData());
    }

}

class __StringObj {
    public function __toString()
    {
        return 'x';
    }
}
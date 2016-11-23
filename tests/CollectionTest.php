<?php
/**
 * Created by PhpStorm.
 * User: zhaoqing
 * Date: 16/11/21
 * Time: 下午3:11
 */

namespace Comos\Drapper;


class CollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Bean
     */
    protected $bean;

    protected function setUp()
    {
        parent::setUp();
        $data = [
            'c' => [
                [
                    'id' => 1,
                    'name' => 'n1',
                ],
                [
                    'id' => 2,
                    'name' => 'n2',
                ]
            ],
            'e' => null,
        ];
        $this->bean = Bean::fromArray($data);
    }

    public function testBeanGetCollection()
    {
        $collection = $this->bean->collection('c');
        $this->assertInstanceOf(Collection::class, $collection);

        // test instance cache
        $collection1 = $this->bean->collection('c');
        $this->assertSame($collection, $collection1);
    }

    public function testBeanGetCollection_ButTheFieldDoesNotExist()
    {
        $collection = $this->bean->collection('d');
        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertEquals(0, $collection->count());
    }

    public function testBeanGetCollection_FromANullField()
    {
        $collection = $this->bean->collection('e');
        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertEquals(0, $collection->count());
    }

    public function testCount()
    {
        $this->assertEquals(2, $this->bean->collection('c')->count());
        $this->assertEquals(2, count($this->bean->collection('c')));

    }

    public function testBeanGetCollection_FromNullField()
    {
        $collection = $this->bean->collection('d');
        $this->assertEquals(0, count($collection));
    }

    public function testGetBeanAt()
    {
        $collection = $this->bean->collection('c');
        $bean = $collection->getBeanAt(0);
        $this->assertEquals('n1', $bean->str('name'));

        $bean1 = $collection->getBeanAt(0);
        $this->assertSame($bean, $bean1);
    }

    /**
     * @expectedException \Comos\Drapper\Exceptions\OutOfBoundsException
     */
    public function testGetBeanAt_OutOfRange()
    {
        $this->bean->collection('c')->getBeanAt(3);
    }

    /**
     * @expectedException \Comos\Drapper\Exceptions\OutOfBoundsException
     */
    public function testGetBeanAt_OutOfRange_N1()
    {
        $this->bean->collection('c')->getBeanAt(-1);
    }

    public function testForeach()
    {
        $keys = [];
        $names = [];
        $innerKeys = [];
        $innerNames = [];
        foreach ($this->bean->collection('c') as $key => $bean) {
            $keys[] = $key;
            $names[] = $bean->str('name');
            foreach ($this->bean->collection('c') as $innerKey => $innerBean) {
                $innerKeys[] = $innerKey;
                $innerNames[] = $innerBean->str('name');
            }
        }
        $this->assertEquals([0, 1], $keys);
        $this->assertEquals([0, 1, 0, 1], $innerKeys);

        $this->assertEquals(['n1', 'n2'], $names);
        $this->assertEquals(['n1', 'n2', 'n1', 'n2'], $innerNames);
    }

    /**
     * @expectedException \Comos\Drapper\Exceptions\TypeErrorException
     */
    public function testFromArray_InputIsNotAnArray()
    {
        $input = 'a';
        Collection::fromArray($input);
    }
    
    /**
     * @expectedException \Comos\Drapper\Exceptions\TypeErrorException
     */
    public function testABeanGetsCollection_ButTheFieldIsNotAnArray()
    {
        $data = ['a' => 1];
        Bean::fromArray($data)->collection('a');
    }
}
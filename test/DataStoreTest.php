<?php

/**
 * Created by PhpStorm.
 * User: mb
 * Date: 17.04.16
 * Time: 15:35
 */
class DataStoreTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     */
    public function testDataStore()
    {
        $store = new \Brunt\Store\DataStore();
        $store->put('a', 'b');
        $this->assertEquals($store->get('a'), 'b');
    }

    public function testMagic()
    {
        $store = new \Brunt\Store\DataStore();
        $store->a = 'b';
        $store->b = 'a';
        $this->assertEquals($store->{'a'}, 'b');
        $this->assertEquals($store->{'a'}, $store->a);
        $this->assertNotEquals($store->a, $store->b);
    }


}
<?php
use Brunt\Store\GlobalStore;
use Brunt\Store;

/**
 * Created by PhpStorm.
 * User: mb
 * Date: 17.04.16
 * Time: 15:35
 */
class GlobalStoreTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     */
    public function testDataStore()
    {
        $store = GlobalStore::init();
        $store->put('a', 'b');
        $this->assertEquals($store->get('a'), 'b');

    }

    public function testMagic()
    {
        $store = GlobalStore::init();
        $store->a = 'b';
        $this->assertEquals($store->{'a'}, 'b');
    }

    public function testMagicStaticCallSet()
    {
        GlobalStore::a('b');
        $store = GlobalStore::init();
        $this->assertEquals($store->{'a'}, 'b');
    }

    public function testMagicStaticCallGet()
    {
        $store = GlobalStore::init();
        $store->a = 'b';
        $this->assertEquals(GlobalStore::a(), 'b');
    }

}
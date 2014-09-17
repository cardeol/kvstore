<?php

class KVStoreTest extends PHPUnit_Framework_TestCase
{
    // ...

    public function testCreatingFile()
    {

        $kv = new KVStore();
        $kv->Save();
        $this->assertTrue(file_exists("kvstore.txt"),"File do not exists");
    }


    public function testPersistence()
    {
        $kv = new KVStore();
        $kv->setPersistence(true);
        $v = rand(1,100000);
        $kv->set("persistencekey",$v);
        $kj = new KVStore();
        $y = $kj->get("persistencekey");
        $this->assertEquals($v,$y,"Persistence values are different");
        $kj->Save();
        $ky = new KVStore();
        $z = $ky->get("persistencekey");
        $this->assertEquals($v,$z,"Persistence values are different after save");

    }

    public function testGetSet()
    {
        $kv = new KVStore();

        $x = rand(1,100000);        
        $kv->set("persistencekey",$x);        
        $y = $kv->get("persistencekey");
        $this->assertEquals($x,$y,"get value is different than set");
    }

    public function testGetDel()
    {
        $kv = new KVStore();
        $x = rand(1,100000);        
        $kv->set("testkey",$x);        
        $y = $kv->getDel("testkey");
        $this->assertEquals($x,$y,"get value is different than set");
        $this->assertFalse($kv->keyexists("testkey"),"Test key still exists");
    }

    // ...
}


?>
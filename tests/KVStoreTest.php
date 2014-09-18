<?php


class KVStoreTest extends PHPUnit_Framework_TestCase
{
    
    public function testCreatingFile()
    {

        $kv = new KVStore\KVStore();
        $kv->Save();
        $file = "kvstore_data.json";
        $this->assertTrue(file_exists($file),"File $file do not exists");
    }


    public function testPersistence()
    {
        $kv = new KVStore\KVStore();
        $kv->setPersistence(true);
        $v = rand(1,100000);
        $kv->set("persistencekey",$v);
        $kj = new KVStore\KVStore();
        $y = $kj->get("persistencekey");
        $this->assertEquals($v,$y,"Persistence values are different");
        $kj->Save();
        $ky = new KVStore\KVStore();
        $z = $ky->get("persistencekey");
        $this->assertEquals($v,$z,"Persistence values are different after save");

    }

    public function testKeyPatterns() {
        $kv = new KVStore\KVStore();
        $kv->drop();
        $kv->set("a1",1);
        $kv->set("aa",1);
        $kv->set("c3",1);
        $kv->set("x4",1);
        $kv->set("ybx",1);
        $kv->set("yb",1);
        $kv->set("abc",1);
        $kv->set("abx",1);
        $kv->set("xbx",1);
        $kv->set("d:3",1);
        $kv->set("d:8",1);        
        $torun = array("a*,4","??,5","???,6","a*,4","ab*,2","c?,1","d:*,2");
        foreach($torun as $k) {
            $v = explode(",", $k);
            $n = count($kv->getKeys($v[0]));
            $this->assertEquals($n,$v[1],"Key Pattern ".$v[0]." expects ".$v[1]." items, getting ".$n);            
        }
    }

    public function testGetSet()
    {
        $kv = new KVStore\KVStore();

        $x = rand(1,100000);        
        $kv->set("persistencekey",$x);        
        $y = $kv->get("persistencekey");
        $this->assertEquals($x,$y,"get value is different than set");
    }

    public function testGetDel()
    {
        $kv = new KVStore\KVStore();
        $x = rand(1,100000);        
        $kv->set("testkey",$x);        
        $y = $kv->getDel("testkey");
        $this->assertEquals($x,$y,"get value is different than set");
        $this->assertFalse($kv->keyexists("testkey"),"Test key still exists");
    }

    // ...
}


?>
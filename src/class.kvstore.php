<?php


class KVStore {
	private $obj;
	private $tosave; 
	private $toremove;
	private $ispersistent;
	private $kvfile;

	private function object_to_array($obj) {
		if(is_object($obj)) $obj = (array) $obj;
		if(is_array($obj)) {
			$new = array();
			foreach($obj as $key => $val) {
				$new[$key] = $this->object_to_array($val);
			}
		} else $new = $obj;
		return $new;
	}

	function __construct($file = "kvstore.txt") {
		$this->obj = array();		
		$this->tosave = array();
		$this->kvfile = $file;	
		$this->toremove = array();
		$this->_readContent();
	}

	
	private function _saveContent() {
		$output = json_encode($this->obj);
		file_put_contents($this->kvfile,$output, LOCK_EX);
	}


	private function _readContent() {
		if(file_exists($this->kvfile)) {
			$c = file_get_contents($this->kvfile);
			$j = json_decode($c);
			$this->obj = $this->object_to_array($j);
		} 			
	}

	public function reload() {
		$this->_readContent();
	}

	public function getData() {
		return $this->obj;
	}

	public function getKeys() {
		return array_keys($this->obj);
	}

	public function setPersistence($v) {
		$this->ispersistent = $v==true;		
	}

	public function keyexists($key) {
		return isset($this->obj[$key]);
	}
	
	public function get($key) {
		if($this->keyexists($key)) return $this->obj[$key];
		return "";
	}

	public function getDel($key) {
		if($this->keyexists($key)) {
			$v = $this->obj[$key];
			unset($this->obj[$key]);
			$this->toremove[] = $key;
			if($this->ispersistent) $this->Save();	
			return $v;
		}
		return "";
	}

	public function set($key,$value) {
		if($key=="" || $key==null) return false;
		if(isset($this->obj[$key])) {
			if($this->obj[$key]==$value) return true;
		}
		$this->obj[$key] = $value;
		$this->tosave[$key] = $value;
		if($this->ispersistent) $this->Save();
		return true;
	}

	public function del($key) {
		if($key=="" || $key==null) return false;
		if($this->keyexists($key)) unset($this->obj[$key]);
		if($this->ispersistent) $this->Save();
	}
	

	public function Save() {
		if(!$this->ispersistent) {
			$this->reload();
			foreach($this->tosave as $k => $v) {
				$this->obj[$k] = $v;
			}
			foreach($this->toremove as $k => $v) {
				if(isset($this->obj[$k])) unset($this->obj[$k]);
			}
		}
		$this->_saveContent();
		return true;
	}
}

?>
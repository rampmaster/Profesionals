<?php
namespace User\GuessBundle\Model;

Class Contact {
	public $name;
	public $message;
	public $email;

	public function setName($name){
		$this->name = $name;
	}
	public function setEmail($name){
		$this->email = $name;
	}
	public function setMessage($name){
		$this->message = $name;
	}
}
?>
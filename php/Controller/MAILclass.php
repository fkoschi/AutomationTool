<?php 
	
	class MAIL {
	
	public $_to;
	public $to;
	public $subject;
	public $message;
	public $from;
	public $Cc;
	public $Bc;
	public $_from;
	public $_Cc;
	public $_subject;
	public $_message;
	public $_headers;
	public $_parameters;
	public $_mail;
	
	public function set_Empfaenger($to) {
		$this->_to = $to;
	}
	public function set_Betreff($subject) {
		$this->_subject = $subject ;
	}
	public function set_Message($message) {
		$this->_message = $message;
	}
	public function set_Headers($from, $Cc, $Bc){
		$this->_from = $from;
		$this->_Cc = $Cc;
		$this->_Bc = $Bc;
		
		if($this->_Cc == "" && $this->_Bc == ""){
			$this->_headers = "From : " . $this->_from ;
		} else if($this->_Bc == "") {
			$this->_headers = "From : " . $this->_from . "\r\n Cc : "  . $this->_Cc;
		} else {
			$this->_headers = "From : " . $this->_from . "\r\n Cc : "  . $this->_Cc . "\r\n\ Bc : " .$this->_Bc;
		}
		//$this->_headers .= 
	}
	
	public function send_Mail(){
		if ( false == mail($this->_to, $this->_subject , $this->_message, $this->_headers ) )
		{
			die ("Mail konnte nicht versandt werden!");
		} 
		
	}
	
	}
?>
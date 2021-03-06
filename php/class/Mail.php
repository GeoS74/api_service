<?php
class Mail
{
  protected $to = array();
  protected $subject = '';
  protected $message = '';
  protected $from = '';
  
  public function getTo()
  {
    return $this -> to;
  }
  

  public function send()
  {
	mail(
	    implode(', ', $this -> to),
	    $this -> subject,
	    $this -> message,
		"From: ".$this -> from."\r\nContent-type: text/html; charset=utf-8\r\n"."X-Mailer: PHP mail script"
	  );
	return $this;
  }

  public function clear()
  {
	$this -> to = array();
	$this -> subject = '';
	$this -> message = '';
	$this -> from = '';
	return $this;
  }

  public function setTo( $to )
  {
	trimer($to);

    if( is_string($to) )
	{
	  if( !in_array($to, $this -> to) ) $this -> to[] = $to;
	  return $this;
	}
	
	if( is_array($to) )
	{
	  foreach( $to as $val )
	  {
		  if( !$val ) continue;
		  if( !in_array($val, $this -> to) ) $this -> to[] = $val;
	  }
	}
	return $this;
  }

  public function setSubject( $subject )
  {
    $this -> subject .= (string)$subject;
	return $this;
  }
  
  public function setMessage( $message )
  {
    $this -> message .= (string)$message;
    return $this;
  }
  
  public function setFrom( $from )
  {
	trimer($from);

    $this -> from = (string)$from;
    return $this;
  }
}

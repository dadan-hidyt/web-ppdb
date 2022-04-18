<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
class Email 
{
	private $config;
	private $host;
	private $secure;
	private $port;
	private $email;
	private $password;
	private $debug;
	private $mail;
	private $to;
	private $toName;
	private $from;
	private $replyto;
	private $subject;
	private $fromName;
	private $content;
	public function __construct($debug = false) {
		$this->config = $GLOBALS['config'];
		if ($debug == true) {
			$this->debug = true;
		}
		$this->mail = new PHPMailer($this->debug);

	}
	public function to($to = "", $toName = "") {
		$this->to = $to;
		$this->toName = $toName;
		return $this;
	}
	public function from($from = "", $fromName = "") {
		$this->from = $from;
		$this->fromName = $fromName;
		return $this;
	}
	public function subject($subject) {
		$this->subject = $subject;
		return $this;
	}
	public function replyto($reply = "") {
		$this->replyto = $replyto;
		return $this;
	} 
	public function content($text = "") {
		$this->content = $text;
		return $this;
	}
	public function _server_config() {
		$this->mail->isSMTP();
		$this->mail->SMTPDebug = false;
		$this->mail->SMTPAuth = true;
		$this->mail->SMTPSecure = $this->config->smtp_config->smtp_secure;
		$this->mail->Host = $this->config->smtp_config->host;
		$this->mail->Port = $this->config->smtp_config->port;
		$this->mail->Username = $this->config->smtp_config->username;
		$this->mail->Password = $this->config->smtp_config->password;
	}
	public function send() {
		$this->_server_config();
		$from = $this->config->smtp_config->username;
		$name = $this->config->smtp_config->name;
		if (empty($this->from) || empty($this->fromName)) {
			$this->from = $from;
			$this->fromName = $name;
		}
		//setting recive
		$this->mail->isHTML(true);
		$this->mail->AddAddress($this->to, $this->toName);
		$this->mail->SetFrom($this->from, $this->fromName);
		if (!empty($this->replyto)) {
			$this->mail->AddReplyTo($this->replyto, "AUTEN");
		}
		$this->mail->Subject = $this->subject;
		$this->mail->Body = $this->content;
		$this->mail->AltBody = "lorem";
		if ($this->mail->send()) {
			return true;
		} else {
			return false;
		}

	}
}

?>
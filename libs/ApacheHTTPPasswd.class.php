<?php

/*
GPL

Code by myrdin, part of the nanoweb project (http://nanoweb.si.kz)
*/

define(DEFAULT_AUTH_FILE, ".htpassword");

class ApacheHTTPPasswd
{
	private $filename;

	private $file;

	private $entries;

	private function gen_salt()
	{
		$random = 0;
		$rand64 = "";
		$salt = "";

		$random = rand();
		$rand64= "./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
		$salt = substr($rand64, $random  %  64, 1).substr($rand64, ($random / 64) % 64, 1);
		$salt = substr($salt, 0, 2);

		return $salt;
	}

	private function write_file()
	{
		ftruncate($this->file, 0);
		foreach ($this->entries as $entry) {
			$str = $entry[0] . ':' . $entry[1] . "\n";
			fputs($this->file, $str);
		}
		close_file($this->file);
	}


	private function set_user_password($login, $password)
	{
		$salt = $this->gen_salt();
		$crypt_pwd = crypt($password, $salt);
		$this->entries[$login] = $crypt_pwd;
	}


	public function __construct($filename = DEFAULT_AUTH_FILE)
	{
		$this->filename = $filename;
		$this->file = fopen($this->filename, 'a+');
		$this->entries = array();

		$raw_content = fread($this->file, filesize($this->file));
		$lines = explode("\n", $raw_content);
		foreach ($lines as $line) {
			$entry = explode(":", $line);
			$this->entries[$entry[0]] = $entry[1];
		}
	}

	public function __destruct()
	{
		$this->write_file();
		fclose($this->file);
	}

	public function has_user($login)
	{
		if (isset($this->entries[$login])) {
			return true;
		}
		return false;
	}


	public function add_user($login, $password)
	{
		if (! $login || ! $password ) {
			return;
		}

		if ($this->has_user($login) == false) {
			$this->set_user_password($login, $password);
		}
	}

	public function modify_user($login, $password)
	{
		if (! $login || ! $password ) {
			return;
		}

		if ($this->has_user($login) == true) {
			$this->set_user_password($login, $password);
		}
	}	
}

?>

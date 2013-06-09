<?
class mail_parse {
	# https://code.google.com/p/php-mime-mail-parser/
	# sudo pecl install mailparse
	# vi /etc/php5/cli/php.ini (add extension=mailparse.so)
	# http://www.berkes.ca/guides/postfix_virtual.html (Postfix virtual mapping)
	# vi /etc/aliases # user: "| /usr/bin/php /var/www/site/script/email_incoming.php" 
	# vi /etc/postfix/virtual 

	public $parsed;

	function __construct($data) {
		require_once VENDOR_DIR.'/mail_parse/MimeMailParser.class.php';
		$this->parsed = new MimeMailParser;
		$this->parsed->setText($data);
	}

	function parts() {
		return $this->parsed->parts;
	}

	function from() {
		$from = trim($this->parsed->getHeader('from')); 
		$has_name = strpos($from, ' ') !== false;
		if ($has_name && preg_match('/<(.*)>/', $from, $matches))
		return $matches[1];
	}

	function from_name() {
		$from = trim($this->parsed->getHeader('from')); 
		$has_name = strpos($from, ' ') !== false;
		if (!$has_name) return '';
		preg_match('/([^<]*)/', $from, $matches);
		return trim($matches[0]);
	}

	function to() {
		return $this->parsed->getHeader('to');
	}

	function cc() {
		return $this->parsed->getHeader('cc');
	}

	function subject() {
		return $this->parsed->getHeader('subject');
	}

	function body(array $o=[]) {
		$o = array_merge([
			'signature' => false,
			'mode' => 'html', # or text
		], $o);

		$out = $this->parsed->getMessageBody($o['mode']);

		# Remove signature
		if (!$o['signature'])
			$out = preg_replace('/--.*[\r\n|\n]+.*/s', '', $out);

		return $out;
	}

	function attachments() {
		return $this->parsed->getAttachments();
	}

}

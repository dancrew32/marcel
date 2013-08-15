<?
class upload {

	/*
	 * $upload = Upload::factory('/path');
	 * $upload->file($_FILES['test']);
	 * $results = $upload->upload();
	 */

	const DEFAULT_DIR_PERMISSIONS = 750;

	protected $files_post = array();
	protected $destination;
	protected $finfo;
	public $file = array();
	protected $max_file_size;
	protected $mimes = array();
	protected $external_callback_object;
	protected $external_callback_methods = array();
	protected $tmp_name;
	protected $validation_errors = array();
	protected $filename;
	private $callbacks = array();

	static function factory($destination) {
		return new self($destination);
	}

	function __construct($destination) {
		// set & create destination path
		if (!$this->set_destination($destination))
			throw new Exception('Upload: Can\'t create destination.');
		
		//create finfo object
		$this->finfo = new finfo();
	}
	
	function upload() {
		if ($this->check())
			$this->save();
		
		// return state data
		return $this->get_state();
	}
	
	function save() {
		$this->save_file();
		return $this->get_state();
	}
	
	function check() {
		//execute callbacks (check filesize, mime, also external callbacks
		$this->validate();
		
		//add error messages
		$this->file['errors'] = $this->get_errors();
		
		//change file validation status
		$this->file['status'] = empty($this->validation_errors);
		
		return $this->file['status'];
	}
	
	function get_state() {
		return $this->file;
	}
	
	protected function save_file() {
		//create & set new filename
		$this->create_new_filename();
		
		//set filename
		$this->file['filename']	= $this->filename;
		
		//set full path
		$this->file['full_path'] = $this->destination . $this->filename;
		
		$status = move_uploaded_file($this->tmp_name, $this->file['full_path']);
		
		//checks whether upload successful
		if (!$status)
			throw new Exception('Upload: Can\'t upload file.');
		
		//done
		$this->file['status']	= true;
	}
	
	protected function set_file_data() {
		$file_size = $this->get_file_size();
		
		$this->file = array(
			'status'				=> false,
			'destination'			=> $this->destination,
			'size_in_bytes'			=> $file_size,
			'size_in_mb'			=> $this->bytes_to_mb($file_size),
			'mime'					=> $this->get_file_mime(),
			'original_filename'		=> $this->file_post['name'],
			'tmp_name'				=> $this->file_post['tmp_name'],
			'post_data'				=> $this->file_post,
		);
		
	}

	function set_error($message) {
		$this->validation_errors[] = $message;
	}
	
	function get_errors() {
		return $this->validation_errors;
	}

	function callbacks($instance_of_callback_object, $callback_methods) {
		if (empty($instance_of_callback_object))
			throw new Exception('Upload: $instance_of_callback_object can\'t be empty.');
		
		if (!is_array($callback_methods))
			throw new Exception('Upload: $callback_methods data type need to be array.');
		
		$this->external_callback_object	 = $instance_of_callback_object;
		$this->external_callback_methods = $callback_methods;
	}
	
	protected function validate() {
		//get curent errors
		$errors = $this->get_errors();
		
		if (empty($errors)) {
			//set data about current file
			$this->set_file_data();
			
			//execute internal callbacks
			$this->execute_callbacks($this->callbacks, $this);
		
			//execute external callbacks
			$this->execute_callbacks($this->external_callback_methods, $this->external_callback_object);
		}
	}
	
	protected function execute_callbacks($callbacks, $object) {
		foreach($callbacks as $method)
			$object->$method($this);
	}
	
	protected function check_mime_type($object) {
		if (!empty($object->mimes)) {
			if (!in_array($object->file['mime'], $object->mimes))
				$object->set_error('Mime type not allowed.');
		}
	}
	
	function set_allowed_mime_types($mimes) {
		$this->mimes		= $mimes;
		
		//if mime types is set -> set callback
		$this->callbacks[]	= 'check_mime_type';
	}
	
	protected function check_file_size($object) {
		if (!empty($object->max_file_size)) {
			$file_size_in_mb = $this->bytes_to_mb($object->file['size_in_bytes']);
			if ($object->max_file_size <= $file_size_in_mb)
				$object->set_error('File is too big.');
		}
	}

	function set_max_file_size($size) {
		$this->max_file_size	= $size;
		
		//if max file size is set -> set callback
		$this->callbacks[]	= 'check_file_size';
	}
	
	function file($file) {
		$this->set_file_array($file);
	}
	
	protected function set_file_array($file) {
		//checks whether file array is valid
		if (!$this->check_file_array($file))
			$this->set_error('Please select file.');
		
		//set file data
		$this->file_post = $file;

		//set tmp path
		$this->tmp_name  = $file['tmp_name'];
	}
	
	protected function check_file_array($file) {
		return isset($file['error']) 
			&& !empty($file['name']) 
			&& !empty($file['type']) 
			&& !empty($file['tmp_name']) 
			&& !empty($file['size']);
	}

	protected function get_file_mime() {
		return $this->finfo->file($this->tmp_name, FILEINFO_MIME_TYPE);
	}
	
	protected function get_file_size() {
		return filesize($this->tmp_name);
	}

	protected function set_destination($destination) {
		$this->destination = $destination . DIRECTORY_SEPARATOR;
		return $this->destination_exist() ?: $this->create_destination();
	}
	
	protected function destination_exist() {
		return is_writable($this->destination);
	}
	
	protected function create_destination() {
		return mkdir(ROOT_DIR . $this->destination, self::DEFAULT_DIR_PERMISSIONS, true);
	}
	
	protected function create_new_filename() {
		$this->file['extension'] = pathinfo($this->file['original_filename'], PATHINFO_EXTENSION);
		$this->filename = sha1(mt_rand(1, 9999) . $this->destination . uniqid()) . time() . '.' . $this->file['extension'];
	}
	
	protected function bytes_to_mb($bytes) {
		return round(($bytes / 1048576), 2);
	}
	
}

<?php
namespace Seven\File;


/**
 * Should be used in a model class that defines all of the initialised variables 
 *
 * @package ModelTrait
 * @author Elisha Temiloluwa a.k.a TemmyScope (temmyscope@protonmail.com)
 **/

trait UploaderTrait{

	/**
	* The following protected properties must be implemented, defined and declared in the class using this trait
	* @var $_dest for upload destination directory
	* @example __DIR__.'/cdn/'
	*
	* @var [] $_allowed  file types => [ filetypes => file mimetype ]
	* @example  [
	*	'jpg' => 'image/jpeg',
	*	'png' => 'image/png'
	* ]
	*
	* @var int _limit
	* @example 5024768; This is the default value, which is approximately 5mb
	*/

	protected $resize = false;
	protected $dim = [];

	public function upload($file): array
	{
		if($file['size'] > $this->_limit){
			return ['status' => false, 'value' => "The file limit of ".($this->_limit/1048576)." mb has been exceeded"];
		}
		[$target, $type] = $this->getNewName($file['name']);
		if( $this->move( $file["tmp_name"], $target ) ){
        	if( $this->allowed( $type, $this->getMime($target) ) ){
		    	return ['status' => false, 'value' => "This file type or mime is not allowed"];
			}else{
				if ( $this->resize ) {
					$this->resizer( $target, $type, $this->dim[0], $this->dim[1] );
				}
            	return ['status' => true, 'value' => $target, 'type' => $type, 'size' => $file['size'] ];
			}
        }
	}

	//only applicable for image uploads
	public function resize($width, $height): self
	{
		$this->resize = true;
		$this->dim = [ $width, $height ];
		return $this;
	}

	private function resizer($file, $ext, $w, $h)
	{
		list($width, $height) = getimagesize($file);
	   	if($ext == "jpg" || $ext == "jpeg"){
		   	$src = imagecreatefromjpeg($file);
		   	$dst = imagecreatetruecolor($w, $h);
		   	imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $width, $height);
		   	// again for jpg
		   	imagejpeg($dst, $file);
	   	}elseif($ext == "png"){
	   		$src = imagecreatefrompng($file);
	   		$dst = imagecreatetruecolor($w, $h);
		   	imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $width, $height);
		   	// again for png
		   	imagepng($dst, $file);
	   	}elseif($ext == "gif"){
	   		$src = imagecreatefromgif($file);
	   		$dst = imagecreatetruecolor($w, $h);
		   	imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $width, $height);
		   	// again for png
		   	imagegif($dst, $file);
	   	}
	}

	private function move($source, $dest): bool
	{
		return move_uploaded_file($source, $target);
	}

	private function getMime($filename)
	{
		return mime_content_type($filename);
	}

	/**
	*	@param 
	*/
	private function allowed($type, $mime): bool
	{
		return ( array_key_exists($type, $this->_allowed) && $this->_allowed[$type] == $mime );
	}

	/**
	*	@param string $name
	*	@return array containing target name of file and file type 
	*/
	private function getNewName(string $name): array
	{
		$name = basename($name);
		$type = strtolower(pathinfo($name, PATHINFO_EXTENSION));
		$target = $this->_dest.Strings::get_unique_name($name).'.'.$type;
		return [
			$target, $type
		];
	}

}
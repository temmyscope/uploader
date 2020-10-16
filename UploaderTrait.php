<?php
namespace Seven\File;
use Seven\Vars\Strings;

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

	public function upload($file, $dim = []): array
	{
		if (is_null($file)) {
			return ['status' => false, 'value' => "No file was sent."];
		}
		if($file['size'] > $this->_limit){
			return ['status' => false, 'value' => "The file limit of ".($this->_limit/1048576)." mb has been exceeded"];
		}
		[$target, $type] = $this->getNewName( $file['name'] );
		if ( empty($file["tmp_name"]) ) {
			return ['status' => false, 'value' => "This file has no temporary name in its data, use another file."];
		}
    	if ($this->move( $file["tmp_name"], $target )){
    		if( $this->allowed( $type, $this->getMime($target) ) ){
    			if ( !empty($dim) ) {
					$this->resizer( $target, $type, $dim[0], $dim[1] );
				}
	        	return ['status' => true, 'value' => $target, 'type' => $type, 'size' => $file['size'] ];
    		}else{
    			rmdir($target);
    		}
    		return ['status' => false, 'value' => "This file type or mime is not allowed"];
		}
		return ['status' => false, 'value' => "An unknown error occurred."];
	}

	private function resizer($file, $ext, $w, $h)
	{
		list($width, $height) = getimagesize($file);
	   	if($ext === "jpg" || $ext === "jpeg"){
		   	$src = imagecreatefromjpeg($file);
		   	$dst = imagecreatetruecolor($w, $h);
		   	imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $width, $height);
		   	// again for jpg
		   	imagejpeg($dst, $file);
	   	}elseif($ext === "png"){
	   		$src = imagecreatefrompng($file);
	   		$dst = imagecreatetruecolor($w, $h);
		   	imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $width, $height);
		   	// again for png
		   	imagepng($dst, $file);
	   	}elseif($ext === "gif"){
	   		$src = imagecreatefromgif($file);
	   		$dst = imagecreatetruecolor($w, $h);
		   	imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $width, $height);
		   	// again for png
		   	imagegif($dst, $file);
	   	}
	}

	private function move($source, $dest): bool
	{
		return move_uploaded_file($source, $dest);
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
		$target = $this->_dest.'/'.Strings::get_unique_name($name).'.'.$type;
		return [
			$target, $type
		];
	}

}
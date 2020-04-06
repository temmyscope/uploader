
```php

##An Example use case of this trait and how to fuse it right into your model classes is shown below:

//import the library into your model class namespace
use Seven\File\UploaderTrait;


//setup your model class and the variables (with these names) necessary for the trait

class Uploader
{
	use UploaderTrait;

	protected $_dest = __DIR__.'/cdn/';
	protected $_allowed  = [
		'jpg' => 'image/jpeg',
		'png' => 'image/png'
	];
	protected $_limit = 5024768; //5mb

}

//Example Use case
class UserController extends Controller
{
	
	public function createProfile(Uploader $uploader)
	{
		//returns an array containing the status of upload true || false
		//and the value containig address of uploaded file or error message
		$upload = $uploader->upload($_FILE['profile_pic']);
		if ( $upload['status'] === true ) { //upload was successful
			$file_address = $upload['value'];
			//These 2 keys are only set when upload is successful and status returns true 
			$file_size = $upload['size'];
			$file_type = $upload['type'];
		}else{
			//You can show the user an error message from $upload['value'];
		}
	}
}

```
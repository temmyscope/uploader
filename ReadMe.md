## Seven File Uploader
	
	- It is part of the libraries used on the altvel framework project but can be used 
	in any applicable file upload scenario.

	- File Uploader a.k.a uploader-trait is developed by Elisha Temiloluwa a.k.a TemmyScope.

	- Developed to make easier the routine of file upload on traditional file servers.

 
	- Install using composer
```bash
composer require sevens/uploader-trait
```
### Usage: Implementating & Extending
##

***There are two ways to use this library in your project***

	- One way would be to call the Uploader constructor

```php
use Seven\File\Uploader;

$uploader = new Uploader(
 string $destination = __DIR__.'/cdn', 
 array $allowedTypes = [ 'jpg' => 'image/jpeg', 'png' => 'image/png' ],
 int $sizeLimit =  5024768
);

$uploader->upload('image');
```

	- Another way would be to extend the Uploader Class and provide the necessary properties

***If you don't provide the necessary properties, default values have already being provided in the Uploader Class ***

```php
use Seven\File\Uploader;

class FileUploader extends Uploader{

 protected $destination = __DIR__.'/cdn';

 protected $allowedTypes = [ 'jpg' => 'image/jpeg', 'png' => 'image/png' ];

 protected $sizeLimit =  5024768;

}
```	

### Usage: Calling Methods
##

***There are a couple useful methods to use in this library***

```php
	$file = new FileUploader();
```

	- Upload $_FILES['image']
```php
$file->upload('image'); 
```

	- To get uploaded file name only
```php
$file->name()
```

	- To get uploaded file address, containing file address and name
```php
$file->fullName(); 
```

	- To get uploaded file type
```php
$file->type();
```
	- To get uploaded status; returns True if upload was successful
```php
$file->status();
```

	- To get error message; it would be empty if upload status is true
```php
$file->statusMessage();
```
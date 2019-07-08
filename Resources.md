# Slim First Application Walkthrough
#### Step 1 : Install Composer
To install Composer (localy) run thoses lines in the terminal when you are within your project directory.
```
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === '48e3236262b34d30969dca3c37281b3b4bbe3221bda826ac6a9a62d6444cdb0dcd0615698a5cbe587c3f0fe57a54d8f5') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```
Composer is a tool for dependency management in PHP.  
It allows you to declare the libraries your project depends on and will manage (install/update) them for you.  
Composer manages dependencies on a per-project basis installing them in a directory (e.g. **vendor**) inside your project.  
  
*(See also autoload feature)*.
#### Step 2 : Install Slim Framework
To install Slim without skeleton, run this line in the terminal when inside the directory where **composer.phar** is located.
```
php composer.phar require slim/slim
```
This does two things:
* Add the Slim Framework depedency to **composer.json**
* Run **composer install** so that thoses dependencies are availables in the application.
#### Step 3 : Source Control Setup
Create file named **.gitignore** and write this line inside: vendor/*
```
touch .gitignore
echo "vendor/*" >> .gitignore
```
We want to let composer manage these dependencies rather than including them in our source control repository.
#### Step 4 : Create Application
Inside the **public** folder create the **index.php** file.  
You may use the Slim tutorial for example :
```php
<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../../vendor/autoload.php';

$app = new \Slim\App;
$app->get('/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Team $name is the best !");

    return $response;
});
$app->run();
```
This **php** file contains :
* The **use** statements at the top of the script are just bringing the **Request and Response classes** into our script so we don’t have to refer to them by their long-winded names.  
* Next we **include the vendor/autoload.php** file - this is created by Composer and allows us to refer to the Slim and other related dependencies we installed earlier.  
* Finally we create the **$app** object which is the start of the application.  
The **$app->get()** call is our first **“route”**, when we make a GET request to /hello/someone then this is the code that will respond to it.  
Don’t forget you need that final **$app->run()** line to tell Slim that we’re done configuring and it’s time to get on with the main event.  
#### Step 5 : Run Web Server
Run PHP build-in web server using this command when you are in the **public** folder.
```
php -S localhost:8080
```
Then you can explore the local server and see the result.
```
http://localhost:8080/Addanc
```
Cheers ! :wink:

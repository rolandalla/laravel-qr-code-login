
# Laravel Framework 5.4 ,Sentinel,Crud Generator ,Qr Code Login
[Check our article ](http://www.rolandalla.com/laravel-login-qr-code/) 

##Check how it works on youtube :
[![Laravel QR Code generator and login](https://img.youtube.com/vi/PZjzAgGticE/0.jpg)](https://www.youtube.com/watch?v=PZjzAgGticE)

## Starter Site based on on Laravel 5.4 
* [Features](#feature1)
* [Requirements](#feature2)
* [How to install](#feature3)
* [Troubleshooting](#feature5)
* [License](#feature6)
* [Additional information](#feature7)
* [How Starter site is look like](#feature8)
* [Crud Generator](#feature9)

<a name="feature1"></a>
## Starter Site Features:
* Laravel 5.4.x
* Twitter Bootstrap 3.x
* Back-end
	* Automatic install and setup website.
	* User management.
	* Role management.
	* Dashboard.
	* Gentelella Dashboard Ready.
* Front-end
	* User login, registration
	* soon will be more...
* Packages included:
	* Datatables Bundle
	* Sentinel
	* Crud generator

-----
<a name="feature2"></a>
##Requirements

	PHP >= 5.6.4
	OpenSSL PHP Extension
	PDO PHP Extension
	Mbstring PHP Extension
	Tokenizer PHP Extension
	XML PHP Extension

-----
<a name="feature3"></a>
##How to install:
* [Step 1: Get the code](#step1)
* [Step 2: Use Composer to install dependencies](#step2)
* [Step 3: Create database](#step3)
* [Step 4: Install](#step4)
* [Step 5: Start Page](#step5)

-----
<a name="step1"></a>
### Step 1: Get the code - Download the repository
	https://github.com/roladn/laravel-qr-code-login/archive/master.zip
    
    OR Clone this repository:
     https://github.com/roladn/laravel-qr-code-login.git

Extract it in www(or htdocs if you using XAMPP or MAMP) folder and put it for example in laravelStartersite folder.

-----
<a name="step2"></a>
### Step 2: Use Composer to install dependencies

Laravel utilizes [Composer](http://getcomposer.org/) to manage its dependencies. First, download a copy of the composer.phar.
Once you have the PHAR archive, you can either keep it in your local project directory or move to
usr/local/bin to use it globally on your system.
On Windows, you can use the Composer [Windows installer](https://getcomposer.org/Composer-Setup.exe).
Open terminal and go to the project foleder
Then run:

    composer dump-autoload
    composer install --no-scripts

-----
<a name="step3"></a>
### Step 3: Create database

If you finished first three steps, now you can create database on your database server(MySQL). You must create database
with utf-8 collation(uft8_general_ci), to install and application work perfectly.
Just go to the phpmyadmin and create the new database
After that, copy .env.example and rename it as .env and put connection and change default database connection name, only database connection, put name database, database username and password.

-----
<a name="step4"></a>
### Step 4: Install

Now that you have the environment configured, you need to create a database configuration for it. For create database tables use this command:

    php artisan migrate

And to initial populate database use this:

    php artisan db:seed

If you install on your localhost in folder laravelStartersite, you can type on web browser:

	http://localhost/laravel-qr-code-login/public/

OR Run the command " php artisan serv ", and open on the browser the url you get in console :):


-----
<a name="step5"></a>
### Step 5: Start Page

You can now login to admin part of Laravel Framework 5.4  Site:

    username: admin@admin.com
    password: admin


-----
<a name="feature5"></a>
## Troubleshooting

### RuntimeException : No supported encrypter found. The cipher and / or key length are invalid.

    php artisan key:generate

### Site loading very slow

	composer dump-autoload --optimize
OR

    php artisan dump-autoload

-----
<a name="feature6"></a>
## License

This is free software distributed under the terms of the MIT license

-----
<a name="feature7"></a>
## Additional information

Inspired by Laravel 5.4 and based on:
[Crud Generator](https://github.com/roladn/laravelcrud)
[Laravel Starter ](https://github.com/roladn/laravel-sentinel-crud-starter)
----
<a name="feature8"></a>
##How Starter Site is look like

![Qr Code Sacaning](http://i63.tinypic.com/21l4pbq.png)
![Login](http://i64.tinypic.com/23vku8w.png)
![Register new user](http://i63.tinypic.com/21jaybp.png)
![Admin dashboard](http://i66.tinypic.com/a4bj9l.png)
![Admin list users](http://i63.tinypic.com/2ivzio9.png)
![Perrmissons user/role ](http://i67.tinypic.com/35jgn52.png)

----


Read More From my Plog Post
[Read Step by step integration ](http://www.rolandalla.com/laravel-login-qr-code/)
http://www.rolandalla.com/laravel-login-qr-code/



## Support on Beerpay
Hey dude! Help me out for a couple of :beers:!

[![Beerpay](https://beerpay.io/roladn/laravel-qr-code-login/badge.svg?style=beer-square)](https://beerpay.io/roladn/laravel-qr-code-login)  [![Beerpay](https://beerpay.io/roladn/laravel-qr-code-login/make-wish.svg?style=flat-square)](https://beerpay.io/roladn/laravel-qr-code-login?focus=wish)
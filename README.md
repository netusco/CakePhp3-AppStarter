# CakePHP Application Starter

It's a simple App Starter with a login system, forgot password, profile that uses [PhotoCrop](https://github.com/netusco/CakePhp-PhotoCrop) plugin to upload and crop pictures plus the simple Articles example from the docs of Cakephp. It has an Ownership component and some other little methods I use...
It's open to any colaboration improvement, fork, etc... and I might not be maintaining it.

## Requirements

* CakePHP 3.0.0 or greater
* PHP 5.4.16 or greater

## Copyright and license

This software is registered under the MIT license. Copyright(c) 2015 - Ernest Conill

## Installation

1. If it's the first time run on the console from root: php composer.phar install --prefer-dist
1b. If it's a pull from origin then just update: php composer.phar update --prefer-dist

You should now be able to see the vendors folder filled with the repositories of the modules needed.

[more info](http://book.cakephp.org/3.0/en/installation.html)

2. Create database 'app_starter' and add the tables found in config/schema/app_starter
3. To be able to use the methods that send Emails without an error you should install an email server.
I've installed ssmtp server on my ubuntu 14 (see Anexes for instructions).
The email given to the ssmtp (or other) email server should be changed in config/bootstrap_specific.php
4. To be able to use the ChartJs plugin actualised you should run git submodule init and then git submodule update

### Reporting Issues

If you have a problem with this plugin please open an issue.

### Contributing

I'm not actively maintaining this plugin, but it's open for community contributions.

# Documentation 
 
## Running the app 

* To run the app type `bin/cake server` on the console and you should see the app running on your browser at http://localhost:8765/

## Configuration

Read and edit `config/app.php` and setup the 'Datasources' and any other
configuration relevant for your application.

The file config/bootstrap_specific.php contains configuration personal to each local environment.


## Baking 

To bake Controllers, Models, Tests, etc... user bin/cake bake
For tests bin/cake bake test <type> <name>


## Testing 

To run all tests type `vendor/bin/phpunit`
To run particular tests add the test case url behind like: `vendor/bin/phpunit tests/TestCase/Model/Table/ArticlesTableTest`
To run a subset of test methods do it this way: `vendor/bin/phpunit --filter testSave tests/TestCase/Model/Table/ArticlesTableTest`
You can generate code coverage reports from the command line using PHPUnit’s built-in code coverage tools. 
`vendor/bin/phpunit --coverage-html webroot/coverage tests/TestCase/Model/Table/ArticlesTableTest`
This will put the coverage results in your application’s webroot directory. 
You should be able to view the results by going to http://localhost/your_app/coverage.

If you run into problems with email errors read the 3rd point of ## Installation

TIP: you can use `debug($var)` within tests ;)

## Anexes 

1. Installing ssmtp for Ubuntu:

**Enter sSMTP.**

```sh
apt-get install ssmtp
Now open /etc/ssmtp/ssmtp.conf in your favorite text editor and, to get it working on an example gmail account, set it up like so:
```

```sh
root=youremail@gmail.com
mailhub=smtp.gmail.com:587
rewriteDomain=gmail.com
hostname=localhost
AuthUser=youremail@gmail.com
AuthPass=your-super-4556*hUEvOn+66764_password!
UseTLS=YES
UseSTARTTLS=YES
AuthMethod=LOGIN
```

Save the file, and you’re done.

Lastly, update the permissions

```sh
chown root:mail /etc/ssmtp/ssmtp.conf
chmod 640 /etc/ssmtp/ssmtp.conf
```

Unprivileged users who have a need to send mail using sendmail must be a member of the mail group, or they will receive the following error:

```sh
mail: Cannot open mailhub:25
```

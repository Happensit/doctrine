Doctrine Simple Framework Service Provider
========================================

Installation
------------

Use [composer](http://getcomposer.org) to install the provider

    composer require happensit/doctrine
    
Usage
-----

Register the service provider on your app:

    $app->register(new Happensit\Doctrine\DoctrineServiceProvider());
    
Check .env file to root directory application and do change settings database connection  
    
    
#Livefyre Gigya Integration Example#

This sample application shows how to integrate Livefyre with Gigya's APIs to log a user in. PHP has been used as the example language
but the solution is language-agnostic.

##SALIENT POINTS##
While this example code is for demonstration purposes and will require modification for your installation, there are a number 
of best practices suggestions incorporated into the code. 

1. Abstraction of configurations in `config/config.php`
1. Delegation of all login/logout methods to Livefyre. To avoid repeating code, it's best to control all login/logout
methods in one defined place, whether that's your site's overall login or Livefyre. In this example, Livefyre handles the functionality.
1. Use of the new `Livefyre.require` pattern instead of `fyre.conv`


##INSTALL ME##

`git clone https://github.com/Livefyre/livefyre-gigya-example.git`


##PREP ME##

`cd livefyre-gigya-example`

fill in `config/config.php`


##RUN ME##

`php -S localhost:4444`




###Special Notes in server/ajax/token-endpoint.php###




####DEPENDENCY LIST:####
There isn't really anything to do here, but take note perhaps, unless your PHP version doesn't include the necessary extensions


SERVER:

PHP 						>= 	5.4
PHP_JSON					>=	5.2
Gigya SDK					=	2.15.2
Livefyre-php-utilities		=	2.0.2
Requests					=	1.6


CLIENT:

jQuery
jQuery Cookie

(while these pieces aren't specifically required for the integration, they've been included to make life a little easier)
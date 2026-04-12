# Desarrollo de software 7 php

## Install

### Windows


**server**
```
C:\xampp\htdocs\
```


## Server

```
http://localhost:80
http://localhost/
http://localhost/public/index.php

load cache = ctrl + shift + r
```
### FreeBSD

```
pkg install 
php84
php84-opcache 
php84-mbstring
php84-composer
php84-pecl-xdebug

debug:
php84-fpm
mod_php84
```

### Composer

(getcomposer)[https://getcomposer.org]

(getcomposer download)[https://getcomposer.org/download]

(debug_backtrace)[https://www.php.net/manual/en/function.debug-backtrace.php]



## Commands

```
php --version
php --ini

<?php phpinfo() ?>
```

```
mkdir -p /usr/local/www/debug/
/usr/local/www/debug/
```

```
composer --version
composer init
```

## Makefile
```
make debug input="parcial_1"
```

## Glosario

```
https://www.w3schools.com/PHP/func_misc_exit.asp
https://www.w3schools.com/PHP/func_misc_die.asp
https://docs.php.earth/faq/misc/structure/
https://stackoverflow.com/questions/41209349/requirevendor-autoload-php-failed-to-open-stream
```

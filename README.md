# DS7

## Xampp Config

![xampp_config_1](./assets/xampp_config_1.jpg)

![xampp_config_2](./assets/xampp_config_2.jpg)

```
C:/xampp/apache/conf/httpd.conf =>

Listen 80
Listen 8080
Listen 8081
Listen 8082
Listen 8083
Listen 8084
Listen 8085
Listen 8086
Listen 8087
Listen 8088
Listen 8089
Listen 8090
```
![xampp_config_3](./assets/xampp_config_3.jpg)

```
C:/xampp/apache/conf/httpd.conf =>

# Virtual hosts
Include conf/extra/httpd-vhosts.conf
```

![xampp_config_4](./assets/xampp_config_4.jpg)

```
C:/xampp/apache/conf/extra/httpd-vhosts.conf =>

<VirtualHost *:8080>
    DocumentRoot "C:/xampp/htdocs/web8080/Public"
    ServerName localhost

    # Block everything above Public/
    <Directory "C:/xampp/htdocs/web8080">
        Require all denied
    </Directory>

    # Only allow Public/
    # Options FollowSymLinks => requiered for RewriteRule
    # AllowOverride All => allows .htaccess to work
    # Require all granted => allows access keep
    <Directory "C:/xampp/htdocs/web8080/Public">
        Options FollowSymLinks        
        AllowOverride All            
        Require all granted          
    </Directory>
</VirtualHost>



<VirtualHost *:8081>
    DocumentRoot "C:/xampp/htdocs/web8081/Public"
    ServerName localhost

    # Block everything above Public/
    <Directory "C:/xampp/htdocs/web8081">
        Require all denied
    </Directory>

    # Only allow Public/
    # Options FollowSymLinks => requiered for RewriteRule
    # AllowOverride All => allows .htaccess to work
    # Require all granted => allows access keep
    <Directory "C:/xampp/htdocs/web8081/Public">
        Options FollowSymLinks        
        AllowOverride All            
        Require all granted          
    </Directory>
</VirtualHost>


<VirtualHost *:8082>
    DocumentRoot "C:/xampp/htdocs/web8082/Public"
    ServerName localhost

    # Block everything above Public/
    <Directory "C:/xampp/htdocs/web8082">
        Require all denied
    </Directory>

    # Only allow Public/
    # Options FollowSymLinks => requiered for RewriteRule
    # AllowOverride All => allows .htaccess to work
    # Require all granted => allows access keep
    <Directory "C:/xampp/htdocs/web8082/Public">
        Options FollowSymLinks        
        AllowOverride All            
        Require all granted          
    </Directory>
</VirtualHost>

<VirtualHost *:8083>
    DocumentRoot "C:/xampp/htdocs/web8083/Public"
    ServerName localhost

    # Block everything above Public/
    <Directory "C:/xampp/htdocs/web8083">
        Require all denied
    </Directory>

    # Only allow Public/
    # Options FollowSymLinks => requiered for RewriteRule
    # AllowOverride All => allows .htaccess to work
    # Require all granted => allows access keep
    <Directory "C:/xampp/htdocs/web8083/Public">
        Options FollowSymLinks        
        AllowOverride All            
        Require all granted          
    </Directory>
</VirtualHost>
```
### Files

![xampp_config_5](./assets/xampp_config_5.jpg)

![xampp_config_6](./assets/xampp_config_6.jpg)

### Ports

![xampp_config](./assets/xampp_config_7.jpg)

```
http://localhost:8081/
http://localhost:8082/
http://localhost:8082/
```

## PHP CLI


**VS17 x64 Non Thread Safe => zip**

[php cli](https://www.php.net/downloads.php?os=windows)

## Composer config

### Download


[composer page](https://getcomposer.org/)

[composer github](https://github.com/composer/composer)


```
composer dump-autoload -o
```

## Make

[make](https://gnuwin32.sourceforge.net/packages/make.htm)

```
https://sourceforge.net/projects/gnuwin32/

C:\Program Files (x86)\GnuWin32\bin
```

### MySql

```
C:\xampp\mysql\bin\mysql.exe
C:\xampp\mysql\bin\mysql.exe -u root -p
$env:Path += ";C:\xampp\mysql\bin"
mysql -u root -p

cmd: 
  mysql -u root -p < db.sql
powershell: 
  Get-Content db.sql | mysql -u root -p
mysql -u root -p -D empresa -e "SELECT * FROM usuarios;"

cmd: (Correct)
cmd /c "mysql -u root -p --default-character-set=utf8mb4 < db.sql"
```


#### sql debug config

```shell
doas nvim ~/.my.cnf
chmod 600 ~/.my.cnf

[client]
user=root
password=password123
host=localhost

mysql < db.sql
mysql -D empresa -e "SELECT * FROM usuario;"

mysql < db.sql

ALTER USER 'root'@'localhost' IDENTIFIED BY 'NewPassword123!';
FLUSH PRIVILEGES;
exit

mysql -D movies_db -e "SELECT * FROM usuarios;"
```


#### sql debug inser
```
admin
#@A1234567890a#
```




### Env Example

```env
DB_USUARIO="root"
# DB_CONTRASENA=""
DB_CONTRASENA="password123"
# Data Source Name
# Windows
DB_DSN_WINDOWS="mysql:host=localhost;dbname=rh_system;charset=utf8"

# FreeBSD
DB_DSN_BSD="mysql:unix_socket=/var/run/mysql/mysql.sock;dbname=rh_system;charset=utf8"

# OpenSSL
# openssl rand -base64 32
# openssl rand -base64 64
# {
#  echo "SECRET_KEY_1=\"$(openssl rand 32 | base64 -w 0)\""
#  echo "SECRET_KEY_2=\"$(openssl rand 64 | base64 -w 0)\""
#  echo "SECRET_KEY_3=\"$(openssl rand 32 | base64 -w 0)\""
# } > keys.txt
# cat keys.txt
SECRET_KEY_1="rKlRSc7yejxUK2qNSn5qIzP+JyHxS/Nybh1l65yzwRk="
SECRET_KEY_2="+jt4umoG04x7FXsgEYKZN0pj8P1z4kQoZ88J9gDxV+L0uiDCGDUgNrmAzEohm7Mx86HxQCQBWz5n+aq3SykQbw=="
SECRET_KEY_3="AAQadjSBqHxWK5L9gwzINOT7DDW3IS2e5K9VmFiaB+I="
```

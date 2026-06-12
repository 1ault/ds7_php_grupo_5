# semestral


## sql debug not config
```shell
mysql -u root -p < db.sql
mysql -u root -p -D empresa -e "SELECT * FROM usuarios;"
```

## sql debug config

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


## sql debug inser
```
admin
#@A1234567890a#
```

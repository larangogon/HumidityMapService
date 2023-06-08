## HumidityMapService

## Installation

### Requirements

- PHP 8.1
- composer

### 1.2.1 use API

The following vars are required

| Variable            | Description                 |
|---------------------|-----------------------------|
| APPID_OPEN_WEATHER  | key user to exchange api    |


### Create Database

```mysql
mysql> select version();
+-----------+
| version( > 5.7) |
+-----------|

create database test charset utf8;
grant all privileges on test.* to test_usr@'%' identified by 'password';
```

```bash
cp -p .env.example .env
php artisan key:generate

# Setup DB_*
```

Run migrations

```
php artisan migrate
php artisan db:seed --class=CitySeeder
```


## Testing

```
cp -p .env.testing.example .env.testing
php artisan test
```

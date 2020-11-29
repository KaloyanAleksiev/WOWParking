<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Laravel API

This project handle the following task:

Задачата е да разработи REST service за паркинг система.
Услугата трябва да отчита времето на престой на всяко превозно средство, както и да
формира цената за заплащане при излизане, базирайки се на следните критерии:

Един лек автомобил заема 1 място.

Дневна тарифа: от 08:00 до 18:00ч - 3лв./час

Нощна тарифа: от 18:00 до 08:00ч - 2лв./час

Паркингът разполага с 200 места.

За коректна интеграция, услугата трябва да разполага с endpoints за следните операции:
1. Проверка на брой свободни места на паркинга.
2. Проверка на текущо дължима сума на превозно средство с даден номер.
3. Вход в паркинга
    * регистрация на превозното средство
4. Изход от паркинга
    * дерегистрация на превозното средство
    * изчисляване на дължима сума.

## Running the API
It's very simple to get the API up and running. First, create the database (and database user if necessary) and add them to the .env file.

```
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_password
```

Then install, migrate, seed, serve, test and that's all folks:

1. `composer install`
2. `php artisan migrate`
3. `php artisan db:seed`
4. `php artisan serve`
5. `composer test`

The API will be running on `localhost:8000`.


## Usage of the API

Endpoints:

1. `GET api/available_slots` - return available slots at the parking system
2. `POST api/register_vehicle/{register_number}` - register vehicle at the parking system by given register number
3. `GET api/check_vehicle_fees/{register_number}` - return vehicle parking fees by register number
4. `DELETE api/sign_out_vehicle/{register_number}` - sign out vehicle from the parking system by given number

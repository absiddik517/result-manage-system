<p align="center"><a href="https://laravel.com" target="_blank">
<img src="https://github.com/absiddik517/about/blob/main/600x600-80kb.jpg" width="400"></a></p>

<h2 align="center">
  A.B. Siddik
</h2>

<p align="center">
<a href="https://facebook.com/absiddik.u" target="_blank">Facebook</a>
<a href="mailto:absiddik517@gmail.com">Mail</a>
</p>

## About the project

This is single page application to manage a manage school result. All the required systems are included in this application. 

## Installation

Run this composer command in your laravel application:

### Install ext-gd extension first.
```
pkg install php-gd
```

### Clone git repository 
```
git clone https://github.com/absiddik517/result-manage-system.git rms && cd rms 
```

### Install Required Dependancies
```
composer install && npm install && npm run dev 
```

### Final touch !
```
cp env.example .env && touch database/database.sqlite && php artisan migrate --seed && php artisan storage:link && php artisan serve
```

### If there is any error with media (i.e. photo) run the command bellow.
Before running this command make sure you have deleted public/storage folder if it is exist. 

```
php artisan cache:clear
```



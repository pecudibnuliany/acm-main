

Download source code , extract file
& masuk ke directory project

jalankan perintah berikut pada terminal:

install package laravel, pastikan di komputer kamu sudah terinstall 
composer

```bash
composer install
```

install dependencies & build, pastikan di komputer kamu sudah terinstall node js
```bash
npm install && npm run build
```

Buat file .env dengan cara copy file .env.example atau bisa menggunakan terminal
```bash
cp .env.example .env
```

Generate key
```bash
php artisan key:generate
```

> Sesuaikan settingan database pada .env 

ubah .env bagian QUEUE_CONNECTION dari sync menjadi database
```bash
QUEUE_CONNECTION=database
```

kemudian jalankan perintah ini untuk membuat database & data dari seeder
```bash
php artisan migrate
php artisan db:seed
```

>Copy folder vendor dari template arfa pada folder dist/vendor dan paste ke project ini pada folder public

```bash
php artisan serve
```

Project selesai diinisialisasi, Selamat Belajar

TATU UF Hemis tizimi talaba API si orqali dars jadvali olib telegramda 
yuboruvchi bot.
<br>
Yaratish jarayonida <a href="https:://heroku.com">Heroku</a> dan foydalanilgani uchun commitlar soni ko'p.
<br>
Botning ishlash tartibi:<br>
har soatda bot hemis tizimidan dars jadvalani olib malumotlar bazasiga yozadi.
buning uchun 'cron.php' ni ishga tushirish lozim.
<br>
Heroku tizimida foydalanilgan add-onlari:<br>
[ClearDB](https://elements.heroku.com/addons/cleardb) - malumotlar bazasi
<br>
[Scheduler](https://elements.heroku.com/addons/scheduler) - cron ishga tushirish uchun
<br>
Malumitlar bazasida faqat `dars` jadvali mavjud:
<br>
<img src="./db-design.jpg">
<img src="./db-example.jpg">

`env.example` faylidan `.env` faylini yaratib, uni to'ldiring.
Boshqa guruhlar uchun foydalanish uchun `.env` fayldagi maydonlarni to'ldirish lozim
`TELEGRAM_BOT_TOKEN` - bot tokeni
`HEMIS_USERNAME` - hemis logini
`HEMIS_PASSWORD` - hemis paroli
qaysi talaba login paroli kiritilsa shu talaba o'quvchi gurh dars jadvalini olishi mumkin.

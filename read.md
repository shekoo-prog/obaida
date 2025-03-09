c:\xampp\htdocs\obaida\
├── app/
│   ├── Controllers/
│   │   ├── Auth/
│   │   │   ├── LoginController.php
│   │   │   └── RegisterController.php
│   │   ├── Dashboard/
│   │   │   └── DashboardController.php
│   │   └── HomeController.php
│   ├── Models/
│   │   └── User.php
│   ├── Middleware/
│   │   ├── AuthMiddleware.php
│   │   ├── GuestMiddleware.php
│   │   ├── CSRFProtection.php
│   │   └── RateLimiter.php
│   ├── Helper/
│   │   ├── Auth/
│   │   │   └── AuthHelper.php
│   │   └── Cache/
│   │       └── CacheHelper.php
│   └── Exceptions/
│       ├── AuthException.php
│       └── ValidationException.php
├── config/
│   ├── app.php
│   ├── database.php
│   ├── helper.php
│   ├── mail.php
│   ├── session.php
│   └── routes.php
├── public/
│   ├── index.php
│   ├── assets/
│   │   ├── css/
│   │   ├── js/
│   │   └── images/
│   └── .htaccess
├── resources/
│   ├── views/
│   │   ├── auth/
│   │   │   ├── login.php
│   │   │   └── register.php
│   │   ├── dashboard/
│   │   │   └── index.php
│   │   ├── home/
│   │   │   └── index.php
│   │   ├── layouts/
│   │   │   └── app.php
│   │   └── errors/
│   │       ├── 404.php
│   │       └── 500.php
│   └── lang/
│       ├── ar/
│       └── en/
├── routes/
│   ├── web.php
│   └── api.php
├── storage/
│   ├── cache/
│   ├── logs/
│   ├── sessions/
│   ├── uploads/
├── vendor/
├── .env
├── .env.example
├── .gitignore
├── composer.json
├── README.md
└── LICENSE
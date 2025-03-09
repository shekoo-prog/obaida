# Project Structure

```php
obaida/
├── app/
│   ├── Commands/                    # Command line tools
│   │   ├── AppInstall.php
│   │   ├── CacheClear.php
│   │   ├── DbSeed.php
│   │   ├── KeyGenerate.php
│   │   ├── MakeCommand.php
│   │   ├── Migration.php
│   │   ├── MakeMigration.php
│   │   ├── MakeController.php
│   │   ├── MakeHelper.php
│   │   ├── MakeModel.php
│   │   ├── MakeMiddleware.php
│   │   ├── MakeTest.php
│   │   ├── MakeView.php
│   │   ├── MigrateFresh.php
│   │   ├── MigrateRollback.php
│   │   ├── RouteList.php
│   │   └── Serve.php
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   └── AdminController.php
│   │   ├── Auth/
│   │   │   ├── LoginController.php
│   │   │   └── RegisterController.php
│   │   ├── FileController.php
│   │   ├── HomeController.php
│   │   └── BaseController.php
│   ├── Core/
│   │   ├── Auth/
│   │   │   └── Auth.php
│   │   ├── Database/
│   │   │   ├── Database.php
│   │   │   ├── BaseModel.php
│   │   │   └── SensitiveData.php
│   │   ├── FileUpload/
│   │   │   └── FileUploader.php
│   │   ├── Logger/
│   │   │   ├── Logger.php
│   │   │   └── Loggable.php
│   │   ├── Routing/
│   │   │   └── Router.php
│   │   ├── Security/
│   │   │   ├── IpManager.php
│   │   │   ├── LoginActivity.php
│   │   │   └── Security.php
│   │   ├── Session/
│   │   │   ├── SessionManager.php
│   │   │   └── Session.php
│   │   ├── Validation/
│   │   │   └── Validator.php
│   │   └── View/
│   │       └── View.php
│   ├── Middleware/
│   │   ├── AuthorizationMiddleware.php
│   │   └── Middleware.php
│   └── Models/
│       ├── User.php
│       ├── Role.php
│       └── Permission.php
├── config/
│   ├── app.php
│   └── database.php
├── database/
│   ├── migrations/
│   │   ├── create_users_table.sql
│   │   ├── create_sessions_table.sql
│   │   ├── create_logs_table.sql
│   │   └── create_authorization_tables.sql
│   └── seeds/
│       ├── DatabaseSeeder.php
│       ├── UserSeeder.php
│       └── RoleSeeder.php
├── public/
│   ├── index.php
│   ├── assets/
│   │   ├── css/
│   │   ├── js/
│   │   └── img/
│   └── .htaccess
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.php
│       ├── admin/
│       ├── auth/
│       │   ├── login.php
│       │   └── register.php
│       └── errors/
├── routes/
│   ├── web.php
│   └── api.php
├── storage/
│   ├── cache/
│   ├── logs/
│   └── uploads/
├── tests/
│   └── TestCase.php
├── vendor/
├── .env
├── .env.example
├── .gitignore
├── command.php
├── composer.json
├── STRUCTURE.md
└── README.md
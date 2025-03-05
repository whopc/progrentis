# 🌐 Progrentis FilamentPhp 

Aplicacion para la gestion pago de la cuota de la plataforma progrentis para el centro educativo cefodipf.
## ✨ Features

### 🛠️ Developer Experience

- ⚡ Quick CRUD generation with customized [FilamentPHP](https://filamentphp.com/) stubs
    - Optimized UX out of the box
    - No need to modify generated resources
- 🔄 Auto reload on save for rapid development
- 📚 Easy API documentation using [Scramble](https://scramble.dedoc.co/)
- 📤 Built-in Export and Import examples in Filament resources

### 🔐 Authentication & Authorization

- 🛡️ Role-Based Access Control (RBAC) using [Filament Shield](https://filamentphp.com/plugins/bezhansalleh-shield)
- 🔑 Enhanced login page with custom design
- 🌐 Social login with Google via [Filament Socialite](https://filamentphp.com/plugins/dododedodonl-socialite)
- 👤 User profile management with [Filament Breezy](https://filamentphp.com/plugins/jeffgreco-breezy)
- 🔒 Instant 2-Factor Authentication capabilities
- 👥 Simple user-to-role assignment
- 🎭 User impersonation via [Filament Impersonate](https://filamentphp.com/plugins/joseph-szobody-impersonate)

### 📡 API & Integration

- 🚀 Full API support with [Filament API Service](https://filamentphp.com/plugins/rupadana-api-service)
    - Seamlessly integrated with Shield
    - Ready-to-use API endpoints
- 📨 Email integration using [Resend](https://resend.com/)
- 📝 Auto-generated API documentation

### 📁 Media & Content Management

- 🖼️ Integrated [Filament Media Library](https://filamentphp.com/plugins/filament-spatie-media-library)
    - Easy media handling process
    - [Spatie Media Library](https://spatie.be/docs/laravel-medialibrary) support

### ⚙️ Configuration & Settings

- 🎛️ Dynamic plugin management via [Filament Settings](https://filamentphp.com/plugins/filament-spatie-settings)
    - Enable/disable features on the fly
    - [Spatie Laravel Settings](https://github.com/spatie/laravel-settings) integration

## 🚀 Quick Start

1. Copiar el proyecto
    
    ```php
    git clone git@github.com:whopc/progrentis.git
    ```
    1. Entrar en la carpeta 

    ```php
    cd progrentis
    ```
    
2. Composer install
    
    ```php
    composer install
    ```
    
3. Npm Install
    
    ```php
    npm install
    ```
    
4. Copy .env
    
    ```php
    cp .env.example .env
    ```
    
5. Configure your database in .env
    
    ```php
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=progrentis
    DB_USERNAME=root
    DB_PASSWORD=
    ```
    
6. Configure your google sign in cliend id and secret (optional)
    
    ```php
    #google auth
    GOOGLE_CLIENT_ID=
    GOOGLE_CLIENT_SECRET=
    GOOGLE_REDIRECT_URI=http://localhost:8000/admin/oauth/callback/google
    ```
    
7. Configure your resend for email sending (optional)
    
    ```php
    #resend
    MAIL_MAILER=resend
    MAIL_HOST=127.0.0.1
    MAIL_PORT=2525
    MAIL_USERNAME=null
    MAIL_PASSWORD=null
    MAIL_ENCRYPTION=null
    RESEND_API_KEY=
    MAIL_FROM_ADDRESS="admin@domain.com"
    MAIL_FROM_NAME="${APP_NAME}"
    ```
    
8. Migrate your database
    
    ```php
    php artisan migrate --seed
    ```
    
9. Serve the Application
    
    ```script
    composer run dev
    ```
    
11. If run successfully you will get this login interface
    
    ![image.png](.github/images/login-screen.png)
    
12. When signed in it will show this (not much yet but it getting there :) )
    
    ![image.png](.github/images/after-login-without-rbac.png)
    
13. Next step is to setup the RBAC, first generate the role and permission
    
    ```php
    php artisan shield:generate --all
    ```
    
14. It will ask which panel do you want to generate permission/policies for choose the admin panel.
15. Setup the super admin using this command
    
    ```php
    php artisan shield:super-admin
    ```
    
    ![image.png](.github/images/provide-superadmin.png)
    
16. Choose your super admin user and login again.
    
    ![image.png](.github/images/after-login-rbac.png)


## Security
Set your app Debug to false in .env file
```php
APP_NAME="Kaido-Kit"
APP_ENV=local
APP_KEY=base64:gWUd7RPrCZm6iu7qFddY3039BQLroNHJ0nqKcBr8eeA=
APP_DEBUG=false
APP_TIMEZONE=UTC
APP_URL=https://localhost:8000
```



## 💬 Supporte

- 🐛 [Reporte fallas](aristofanethp@gmail.com)
- Tel. 809-803-4111


# progrentis

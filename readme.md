# Berp Sample

### Install

1. Open your terminal.

2. Clone this repo:

        git clone https://github.com/larryu/berp.git
    
3. Once finished, change directory to berp:

        cd berp
    
4. Install all laravel dependencies:

        composer install

5. Copy .env file:

        cp .env.example .env

6. Modify `DB_*` value in `.env` with your database config.
   it uses mailtrap.io to send notification, so please set accordingly
   modify ADMIN_MAIL_ADDRESS to your admin email address
   so it can receive notification when new product is created automacticaly

7. Generate application key:

        php artisan key:generate

8. Run migration table:

        php artisan migrate

9. Run npm comman:

        npm install

10. Serve the app:

        php artisan serve

11. Open `http://localhost:8000/` in your web browser.

12. For third part to import order, please post json data to the following url
    http://localhost:8000/api/orders



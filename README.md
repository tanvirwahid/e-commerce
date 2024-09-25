**Features:**
1) Basic registration. Users can register as normal user or admin.
2) Admin can create and update products.
3) Normal user can order products.
4) All user can view product list (publicly available)
5) Product lists are paginated and cached. Only first 10 pages are cached by default (latest products will be shown first). It can be configured using CACHE_PRODUCT_PAGE key in .env. If CACHE_PRODUCT_PAGE=5, then 1st 5 pages will be cached. If CACHE_PRODUCT_PAGE=ALL, then all pages will be cached.

**To run this project**
1) Clone the repo.
2) Make sure you have php 8.2 (or higher) and necessary extensions installed.
3) Go to project directory and run 'composer install'.
4) Run cp .env.example .env
5) Open .env file and enter db credentials.
6) Fill out these fields

ADMIN_NAME=

ADMIN_EMAIL=

ADMIN_PASSWORD=

These will be default name, email and password of default admin. If these are empty then default admin's name, email and password will be admin, admin@demo.com, aaaaaa

7) Fill out appropriate CACHE_STORE.
8) Save and close .env and run 'php artisan config:cache', 'php artisan key:generate', 'php artisan jwt:secret', 'php artisan migrate', 'php artisan db:seed' and 'php artisan serve'.

**API Endpoints**
1) POST api/v1/register (parameters: name, email, password, password_confirmation, is_admin (0 or 1, boolean)).
2) POST api/v1/login (parameters: email, password).
3) POST api/v1/refresh (Accessible by authenticated users)
4) POST api/v1/logout (Accessible by authenticated users)
5) GET api/v1/products
6) POST api/v1/products (Accessible by admins. Parameters: name, price(number), stock(integer))
7) PUT api/v1/products/{id} (Accessible by admins who has created product with id = {id}. Parameters: name, price(number), stock(integer))
8) GET api/v1/orders (Accessible by normal users).
9) POST api/v1/orders (Accessible by normal users. parameter: items (array), each item needs to have product_id and quantity field)

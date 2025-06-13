<h1 align="center"><a href="" target="_blank">Task Management</a></h1>

<p align="center">

</p>

## Task Management Setup
- ``` composer install ```
- ``` php artisan sail:install ```
  - choose pgsql
- ``` sudo service apache2 stop	```
- ``` alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)' ```
- ``` sail artisan migrate ```
- In case you want sample tasks
  - ``` sail artisan db:seed ```
- ``` sail artisan reverb:install ```
- ``` npm install --save-dev laravel-echo pusher-js ```

To use Laravel Echo with Laravel Reverb, install and execute the server
- ``` sail artisan reverb:install ```
- ``` sail artisan reverb:start ```

## Assumptions
- Initially I considered status and priority columns as string/enum for simplicity, latelly I noticed it should be foreign keys so that I could use eager loading. If I had more time, I would fix that.
- Statuses: Backlog, Todo, In Progress and Done
- Priorities: Low, Medium and High
- 

<h1 align="center"><a href="" target="_blank">Task Management</a></h1>

<p align="center">

</p>

## Presentation Video Demo
- 3 minutes video
  - https://drive.google.com/file/d/14i-r1YrZMRewExmK55EeAYZhhMJTDEzX/view

## Setup
- ``` composer install ```
- ``` php artisan sail:install ```
  - choose pgsql
- ``` sudo service apache2 stop	```
- ``` alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)' ```
- ``` sail artisan migrate ```
- In case you want sample tasks
  - ``` sail artisan db:seed ```

To use Laravel Echo with Laravel Reverb, install:
- ``` sail artisan reverb:install ```
- ``` npm install --save-dev laravel-echo pusher-js ```

To run Laravel Reverb
- ``` sail artisan reverb:start ```

To run tests
- ``` sail pest ```

## Assumptions
- Initially I considered status and priority columns as string/enum for simplicity, latelly I noticed it should be foreign keys so that I could use eager loading. If I had more time, I would fix that.
- Statuses: Backlog, Todo, In Progress and Done
- Priorities: Low, Medium and High
- The documentation with OpenAPI/Swagger was not implemented (lack of time)

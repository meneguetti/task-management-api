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

## Assumptions
- Initially I considered status and priority columns as string/enum for simplicity, latelly I noticed it should be foreign keys so that I could use eager loading. If I had more time, I would fix that.
- Statuses: Backlog, Todo, In Progress and Done
- Priorities: Low, Medium and High
- 

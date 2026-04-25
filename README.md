# Instructions on how to run the code 
## Note: this is a Laravel app , and we will use docker to run it .
## Setting up Laravel sail :

1. Make sure you have docker installed and running on your machine .
2. Run the following command to create a new Laravel application:
	- curl -s https://laravel.build/calculate-date-pattern | bash.
3. Wait until all the dependencies are installed and Laravel Sail is set up .
4. cd to calculate-date-pattern dir.
5. Pull the code from the this repo : 
	- https://github.com/The-Coder-Ali-Jalal/calculate-date-pattern
6. Run : cp .env.example .env
7. Run : ./vendor/bin/sail up -d
8. Run: ./vendor/bin/sail artisan key:generate
9.  Run: ./vendor/bin/sail npm install 
10. Run: ./vendor/bin/sail npm run build
11. Run : ./vendor/bin/sail artisan migrate
12. Run : ./vendor/bin/sail artisan schedule:work.
13. Run: ./vendor/bin/sail artisan queue:work.
14. Visit localhost on your browser you should see Laravel's welcome page.
15. Visit localhost/calculate on your browser you should see my app .

### And that's it . I hope it goes well for you , because I always have hard times setting up environments .
### I do every possible mistake  when doing so 😢.

# Design Document 
## In this document I am going to  explain my choices when developing this app , and why I chose them. 

1. I chose laravel because I am comfortable with using it .

2. I designed the backend to be stateless to allow the ability to scale  in the future (horizontal scaling) .

3. The database is a mysql DB , I chose it because I have experience with it . However , if I am developing for production I would choose a noSQL DB which is well-optimized for 
   write-heavy apps and will be better to handle millions of writing-requests .

4. I used redis caching , to store the calculated results, since we could only have at most  *217* pairs from the *seven* days of the week and the *31* days of the month , we will opt to store each pair of them in redis   as *key: year:date:day* and *value:every occurrence of that date:day pair in this year* , so in all my app history , I will calculate the matches for a pair  the fist time they are requested  , and after that we check the cache for each request , and only calculate what we first encounter .

5. I also used redis to make the writing to the db run as a background job so the user won't wait till the writing is done to get a response , and the job flow will be that it will be queued in redis , and the job will write the request  information also in redis  , and then we will run a command in the server every minute to bulk insert all the requests into the DB at once to save resources . 

6. For the front end , I used alpinejs and tailwindcss in the blade system of Laravel .though  I don't prefer monolithic systems  because they are hard to scale , and put extra load on the server (especially for millions of requests ) , but because the front end is very simple I did so .
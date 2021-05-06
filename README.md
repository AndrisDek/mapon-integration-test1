# mapon-integration-test1

## Cron Job (periodically running service)
To get calculations, create logs and insert data into database every 5 minutes.
Run this command as following in your crontab file.
> */5 * * * * mapon-integration-test1/index.php 

## Instalation

* php ^8.4
* composer install
* `cp .env.example .env`
* put API token to `INPOST_TOKEN` in file `.env`
* put organization id to `ORGANIZATION_ID` in file `.env`

## Run application
`php create_shipment.php`

## Run UnitTest
`./vendor/bin/phpunit`

## Logs
* The log file is located in `log.txt` and contains information about errors and responses from the API

## Assumptions
* For simplicity, I assumed the input was json copied from the sample data in the documentation.
* At the moment I have a problem with the sandbox account, I am not able to add a tracker id to generate a token allowing me to order a courier, as proof I am attaching a screenshot from the application. I am also waiting for a response from inpost support. Therefore I am not able to test whether this code will work and remove any errors. I generated a token for the parcel locker without any problems.
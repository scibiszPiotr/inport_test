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


## Assumptions
* For simplicity, I assumed the input was json copied from the sample data in the documentation.
* The log file is located in logs/logs.txt and contains information about errors and responses from the API
* At the moment I have a problem with the sandbox account, I am not able to add a tracker id to generate a token allowing me to order a courier, as proof I am attaching a screenshot from the application. I am also waiting for a response from inpost support. Therefore I am not able to test whether this code will work and remove any errors.
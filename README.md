## Instalation

* php ^8.4
* composer install
* `cp .env.example .env`
* put API token to `INPOST_TOKEN` in file `.env`
* put organization id to `ORGANIZATION_ID` in file `.env`

### Data to order shipment
* json in file `data/shipment.json`

## Run application
`php create_shipment.php`

## Run UnitTest
`./vendor/bin/phpunit`

## Logs
* The log file is located in `log.txt` and contains information about errors and responses from the API

## Assumptions
* For simplicity, I assumed the input was json copied from the sample data in the documentation.
* for production code I would write more tests, especially negative scenarios and think about edge exceptions
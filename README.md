# Messaging API task

## Set up with PHP built-in webserver
* Install PHP
* Install and start redis-server
* Clone this repo and cd into it
* Run `composer install` to install the Predis dependency
* Run `php -S localhost:8080` (or any port of your choice) to start the server

## Set up with Apache
* Install and start Apache with PHP
  * Ensure mod_rewrite is enabled
* Install and start redis-server
* Clone this repo into your web document root
* Run `composer install` to install the Predis dependency

## To use...
(optional) To initially populate the redis store, run `populate.sh`

To put a message:
```
curl ${host};${port}/messages -X POST -H 'Content-Type: application/json' -d '{"body":"message"}'
```
The return value will be the message ID that can be used for retrieving the message.

To get a message by ID:
```
curl ${host}:${port}/messages/${msg_id}
```

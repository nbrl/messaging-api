# Messaging API task

## Automatic set up with Vagrant and Ansible (using LXC)
* Requires vagrant version > 1.5 (tested with 1.6.2)
* Requires ansible (tested with 1.7)
* Requires lxc lxc-templates cgroup-lite redir python-{yaml,jinja2}
* Requires vagrant-lxc plugin (version 1.0.0.alpha.2)
```
vagrant plugin install vagrant-lxc --plugin-version 1.0.0.alpha.2
git clone https://github.com/nbrl/messaging-api.git
cd messaging-api
vagrant up --provider=lxc
```
* Redis will be populated with six entries (message IDs 0-6)
* PHP built-in web server will be running on 8080, forwarded to port 3020 on the host, so for below commands use ${host}=localhost, ${port}=3020

## Set up with PHP built-in webserver
* Install PHP
* Install and start redis-server
* Clone this repo and cd into it
* Run `composer install` or `php composer.phar install` to install the Predis dependency
* Run `php -S localhost:8080` (or any port of your choice) to start the server

## Set up with Apache
* Install and start Apache with PHP
  * Ensure mod_rewrite is enabled
* Install and start redis-server
* Clone this repo into your web document root
* Run `composer install` or `php composer.phar install` to install the Predis dependency
* Note: is a little picky with rewrite. Works on VPS, not on local installs. Advised use of PHP built-in.

## To use...
(optional) To initially populate the redis store, run `populate.sh`

### To put a message:
```
curl ${host}:${port}/messages -X POST -H 'Content-Type: application/json' -d '{"body":"message"}'
```
The return value will be the message ID that can be used for retrieving the message.


### To get a message by ID:
```
curl ${host}:${port}/messages/${msg_id}
```

### Example
```
$ curl http://localhost:8080/messages -X POST -H 'Content-Type: application.json' -d '{"body":"this is my message"}'
{"id":5}
```
```
$ curl http://localhost:8080/messages/5
{"body":"this is my message"}
```

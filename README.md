# online-shop
Online shop created in php using RESTful technology

## Installation

```bash
# first set up LAMP on your linux OS
$ sudo apt install apache2
$ sudo apt install mysql-server
$ sudo apt install php-pear php-fpm php-dev php-zip php-curl php-xmlrpc php-gd php-mysql php-mbstring php-xml libapache2-mod-php
$ sudo service apache2 restart

# installation of server certificate
$ sudo mkdir /etc/apache2/ssl
$ sudo a2enmod ssl
$ sudo a2ensite default-ssl.conf
$ sudo service apache2 restart
$ sudo service apache2 reload

# enabling apache server-lever redirecting
$ sudo a2enmod rewrite

# clone project into ~/NetBeansProjects directory
$ sudo mkdir ~/NetBeansProjects
$ git clone https://github.com/simenravnik/online-shop.git

# set up configuration
$ sudo cp ~/NetBeansProjects/online-shop/certs/conf/* /etc/apache2/sites-available/
$ sudo service apache2 restart
$ sudo service apache2 reload

# install php graphics library GD
$ sudo apt-get install php-gd
$ sudo service apache2 restart

# import init database
$ mysql -u root -p < ~/NetBeansProjects/online-shop/sql/db.sql
```

The site is available on:

```
http://localhost/netbeans/online-shop
```

## Access

Test data:
```
1. admin:         
      username:   simen@ep.si
      password:   admin

2. seller:
      username:   jure@ep.si
      password:   jure

3. customer:
      username:   vito@ep.si
      password:   vito
```

To login as admin or seller, you need to import server and client certificates.

## Certificates

> Note: recommended browser: Mozilla Firefox

1. Server certificate import

```
Firefox:

preferences > Privacy & Security > Certificates > View Certificates

Open tab:
Authorities > Import

Import:
~/NetBeansProjects/online-shop/cert/epca.crt
```

2. Client certificate import

```
Firefox:

preferences > Privacy & Security > Certificates > View Certificates

Open tab:
Your Certificates > Import

Import:
~/NetBeansProjects/online-shop/cert/Simen.p12

Import:
~/NetBeansProjects/online-shop/cert/Jure.p12
```

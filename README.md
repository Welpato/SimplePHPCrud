# Description
This is a simple command-line CRUD system for client registration using only
PHP and the ORM Doctrine for the database control. 
The objective of this application is just to work as an example on how easy is to
create databases and execute the whole CRUD process just using Doctrine.

## How to execute it
To execute this app you need to have ```composer``` installed in your machine
and execute the following commands:

```composer install```

After that for creating the database:

```vendor/bin/doctrine orm:schema-tool:create```

And then finally run the application via:

```php aplication.php```

If any change is done in the code you can also check if it is following the PSR2 standard
using the following command:

```composer run phpcs```

## Requirements
- PHP ^7.2.27
- Composer
- Doctrine 2.7
- JMS Serializer ^3.8

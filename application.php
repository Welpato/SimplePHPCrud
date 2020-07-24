<?php

declare(strict_types=1);

use SimplePhpCrud\BusinessCase\ClientBusinessCase;
use \SimplePhpCrud\EventSubscribers\ClientSubscriber;

require_once "vendor/autoload.php";
require_once "bootstrap.php";

$entityManager->getEventManager()
              ->addEventSubscriber(new ClientSubscriber());
$ClientBusinessCase = new ClientBusinessCase($entityManager);

$running = true;
while ($running) {
    print "Hello! Choose one of the following options:\n
    0 - Exit.
    1 - Create/Update a client.
    2 - Search a client.
    3 - Delete a client.\n";
    $handle = fopen("php://stdin", "r");
    $value = trim(fgets($handle));
    fclose($handle);
    switch ($value) {
        case 0:
            $running = false;
            break;
        case 1:
            $ClientBusinessCase->createUpdateClient();
            break;
        case 2:
            $ClientBusinessCase->findClient();
            break;
        case 3:
            $ClientBusinessCase->deleteClient();
            break;
        default:
            echo 'Invalid option!';
            break;
    }
}

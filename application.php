<?php

declare(strict_types=1);

use SimplePhpCrud\BusinessCase\ClienteBusinessCase;
use \SimplePhpCrud\EventSubscribers\ClienteSubscriber;

require_once "vendor/autoload.php";
require_once "bootstrap.php";

$entityManager->getEventManager()->addEventSubscriber(new ClienteSubscriber());
$clienteBusinessCase = new ClienteBusinessCase($entityManager);

$running = true;
while ($running){
    print "Olá! Escolha uma das seguintes opções:\n
    0 - Sair da aplicação.
    1 - Cadastrar/atualizar um cliente.
    2 - Pesquisar por informações de um cliente.
    3 - Deletar um cliente existente.\n";
    $handle = fopen("php://stdin", "r");
    $value = trim(fgets($handle));
    fclose($handle);
    switch ($value){
        case 0:
            $running = false;
            break;
        case 1:
            $clienteBusinessCase->createUpdateCliente();
            break;
        case 2:
            $clienteBusinessCase->findClient();
            break;
        case 3:
            $clienteBusinessCase->deleteCliente();
            break;
        default:
            echo 'Opção inválida!';
            break;
    }

}

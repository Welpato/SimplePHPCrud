<?php

declare(strict_types=1);

namespace SimplePhpCrud\EventSubscribers;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use JMS\Serializer\SerializerBuilder;
use SimplePhpCrud\Entity\Client;
use SimplePhpCrud\Entity\Log;
use SimplePhpCrud\Repository\LogRepository;

/**
 * Class ClientSubscriber
 */
class ClientSubscriber implements EventSubscriber
{
    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [Events::postRemove, Events::postUpdate, Events::postPersist];
    }

    /**
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $this->insertLog(Log::CHANGE_TYPE_INSERT, $args);
    }

    /**
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->insertLog(Log::CHANGE_TYPE_UPDATE, $args);
    }

    /**
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
     */
    public function postRemove(LifecycleEventArgs $args)
    {
        $this->insertLog(Log::CHANGE_TYPE_REMOVE, $args);
    }

    /**
     * @param string                                 $logType
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
     */
    private function insertLog(string $logType, LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof Client) {
            return;
        }

        $entityManager = $args->getEntityManager();
        $log = new Log();
        //Since there is no user control it will be always set userId 1
        $log->setUserId(1);
        $log->setChangedDb('Client');
        $log->setChangeType($logType);
        $serializer = SerializerBuilder::create()
                                       ->build();
        $logChange = $serializer->serialize($entity, 'json');
        $log->setChange($logChange);

        $logRepository = new LogRepository($entityManager);
        $logRepository->persist($log);
    }
}

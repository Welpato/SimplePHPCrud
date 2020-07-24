<?php

declare(strict_types=1);

namespace SimplePhpCrud\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Log
 *
 * @ORM\Entity
 * @ORM\Table(
 *     name="log",
 *     options={"comment"="This table carries the logs of the most important changes"}
 * )
 */
class Log
{
    public const CHANGE_TYPE_INSERT = 'insert';
    public const CHANGE_TYPE_UPDATE = 'update';
    public const CHANGE_TYPE_REMOVE = 'remove';


    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(
     *     type="integer",
     *     options={"comment"="Log id"}
     * )
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(
     *     type="integer",
     *     options={"comment"="User that did the change"}
     * )
     *
     * Since there is no user control in this simple app
     * This field is just used as an example.
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=40,
     *     options={"comment"="A table which the change happened"}
     * )
     */
    private $changedDb;


    /**
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=15,
     *     options={"comment"="Type of change done"}
     * )
     */
    private $changeType;

    /**
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=500,
     *     options={"comment"="JSON representation of the new state of the entity"}
     * )
     */
    private $change;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getChangedDb(): string
    {
        return $this->changedDb;
    }

    /**
     * @param string $changedDb
     */
    public function setChangedDb(string $changedDb): void
    {
        $this->changedDb = $changedDb;
    }

    /**
     * @return string
     */
    public function getChangeType(): string
    {
        return $this->changeType;
    }

    /**
     * @param string $changeType
     */
    public function setChangeType(string $changeType): void
    {
        $this->changeType = $changeType;
    }



    /**
     * @return string
     */
    public function getChange(): string
    {
        return $this->change;
    }

    /**
     * @param string $change
     */
    public function setChange(string $change): void
    {
        $this->change = $change;
    }
}

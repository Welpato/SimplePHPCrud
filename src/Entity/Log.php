<?php

declare(strict_types=1);

namespace SimplePhpCrud\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Log/
 *
 * @ORM\Entity
 * @ORM\Table(
 *     name="log",
 *     options={"comment"="Tabela que guarda o log das principais alterações"}
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
     *     options={"comment"="id do log"}
     * )
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(
     *     type="integer",
     *     options={"comment"="Uma FK que poderia ser utilizada para caso existisse um controle de usuarios"}
     * )
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=40,
     *     options={"comment"="Nome da tabela em que a alteração foi realizada"}
     * )
     */
    private $changedDb;


    /**
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=15,
     *     options={"comment"="Tipo da alteração realizada"}
     * )
     */
    private $changeType;

    /**
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=500,
     *     options={"comment"="Entidate da alteração realizada"}
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

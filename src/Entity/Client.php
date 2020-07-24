<?php

declare(strict_types=1);

namespace SimplePhpCrud\Entity;

use Doctrine\ORM\Mapping as ORM;
use SimplePhpCrud\Validation\ClientValidation;

/**
 * Class Client/
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(
 *     name="Client",
 *     options={"comment"="Holds the client information"}
 * )
 */
class Client
{
    /**
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=150,
     *     options={"comment"="Client full name"}
     * )
     */
    private $fullName;

    /**
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=150,
     *     options={"comment"="Client email"},
     *     unique=true
     * )
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(
     *     type="string",
     *     length=11,
     *     options={"comment"="Client CPF"},
     *     unique=true
     * )
     *
     * CPF is the national ID number in Brazil,
     * and it is a very good example of how to apply some validation logic.
     * Since this id number follow up a quite complex validation process
     * as you can see in the ClientValidation class
     */
    private $cpf;

    /**
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=11,
     *     nullable=true,
     *     options={"comment"="Client phone number"}
     * )
     *
     * This field validation is also following the local Brazilian phone number standard
     */
    private $phone;

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     */
    public function setFullName($fullName): void
    {
        $this->fullName = $fullName;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getCpf(): string
    {
        return $this->cpf;
    }

    /**
     * @param string $cpf
     */
    public function setCpf($cpf): void
    {
        $this->cpf = $cpf;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     * @throws \Exception
     */
    public function validate(): void
    {
        if (!ClientValidation::isValidFullName($this->fullName)) {
            throw new \Exception('Invalid name!');
        }

        if (!ClientValidation::isValidEmail($this->email)) {
            throw new \Exception('Invalid email!');
        }

        if (!ClientValidation::isValidCpf($this->cpf)) {
            throw new \Exception('Invalid CPF!');
        }

        if (!ClientValidation::isValidPhone($this->phone)) {
            throw new \Exception('Invalid phone number');
        }
    }
}

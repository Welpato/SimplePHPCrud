<?php

declare(strict_types=1);

namespace SimplePhpCrud\Entity;

use Doctrine\ORM\Mapping as ORM;
use SimplePhpCrud\Validation\ClienteValidation;

/**
 * Class Cliente/
 *
 * @ORM\Entity
 * @ORM\Table(
 *     name="cliente",
 *     options={"comment"="Guarda informações basicas do cliente"}
 * )
 */
class Cliente
{
    /**
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=150,
     *     options={"comment"="Nome completo do cliente"}
     * )
     */
    private $nomeCompleto;

    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(
     *     type="string",
     *     length=150,
     *     options={"comment"="Email do cliente"}
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
     *     options={"comment"="CPF do cliente"}
     * )
     */
    private $cpf;

    /**
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=11,
     *     nullable=true,
     *     options={"comment"="Telefone do cliente"}
     * )
     */
    private $telefone;

    /**
     * @return string
     */
    public function getNomeCompleto(): string
    {
        return $this->nomeCompleto;
    }

    /**
     * @param string $nomeCompleto
     */
    public function setNomeCompleto($nomeCompleto): void
    {
        $this->nomeCompleto = $nomeCompleto;
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
    public function getTelefone(): string
    {
        return $this->telefone;
    }

    /**
     * @param string $telefone
     */
    public function setTelefone($telefone): void
    {
        $this->telefone = $telefone;
    }

    /**
     * @PrePersist
     * @PreUpdate
     * @throws \Exception
     */
    public function validate(): void
    {
        if (!ClienteValidation::isValidNomeCompleto($this->nomeCompleto)) {
            throw new \Exception('Nome inválido!');
        }

        if (!ClienteValidation::isValidEmail($this->email)) {
            throw new \Exception('E-mail inválido!');
        }

        if (!ClienteValidation::isValidCpf($this->cpf)) {
            throw new \Exception('CPF inválido!');
        }

        if (!ClienteValidation::isValidTelefone($this->telefone)) {
            throw new \Exception('Telefone inválido!');
        }
    }
}
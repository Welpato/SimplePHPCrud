<?php

declare(strict_types=1);

namespace SimplePhpCrud\BusinessCase;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\QueryException;
use SimplePhpCrud\Entity\Cliente;
use SimplePhpCrud\Repository\ClienteRepository;
use SimplePhpCrud\Validation\ClienteValidation;

/**
 * Class ClienteBusinessCase
 */
class ClienteBusinessCase
{
    private const EXIT_STRING = 'SAIR';
    private const INSERT_BASE_MESSAGE = "Por favor informe o %s do cliente:\n";

    /**
     * @var array
     */
    private $fieldsToFill = [
        ['fieldName' => 'Cpf', 'label' => 'CPF', 'columnName' => 'cpf'],
        ['fieldName' => 'Email', 'label' => 'E-mail', 'columnName' => 'email'],
        ['fieldName' => 'NomeCompleto', 'label' => 'Nome Completo', 'columnName' => 'nomeCompleto'],
        ['fieldName' => 'Telefone', 'label' => 'Telefone', 'columnName' => 'telefone'],
    ];

    /**
     * @var \SimplePhpCrud\Repository\ClienteRepository
     */
    private $repository;

    /**
     * ClienteBusinessCase constructor.
     *
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = new ClienteRepository($entityManager);
    }

    /**
     * Insert/Update a new Cliente in the database
     */
    public function createUpdateCliente(): void
    {
        $clienteEntity = new Cliente();
        foreach ($this->fieldsToFill as $field) {
            $result = $this->getCreateInput(
                sprintf(self::INSERT_BASE_MESSAGE, $field['label']),
                [ClienteValidation::class, sprintf('isValid%s', $field['fieldName'])],
                [$clienteEntity, sprintf('set%s', $field['fieldName'])]
            );
            if (!$result) {
                print "Retornando ao menu principal.";

                return;
            }
        }
        $this->repository->persist($clienteEntity);
        print "Novo cliente cadastrado com sucesso!\n";
    }

    /**
     * Search and show a list of Clientes
     */
    public function findClient(): void
    {
        $message = "Pesquisar cliente por:\n";
        foreach ($this->fieldsToFill as $index => $field) {
            $message .= sprintf("%s - %s\n", $index, $field['label']);
        }

        $value = $this->getConsoleInput($message);
        if (null === $value) {
            return;
        }

        $criteria = $this->generateCriteria((int)$value);
        if (null === $criteria) {
            return;
        }

        try {
            $result = $this->repository->findBy($criteria);
            if (!empty($result)) {
                $this->printTable($result);
            } else {
                print "\nDados não encontrados!\n\n";
            }
        } catch (QueryException $e) {
            print $e->getMessage();
        }
    }

    /**
     * Delete process of an Cliente entity
     */
    public function deleteCliente(): void
    {
        $value = $this->getConsoleInput(
            sprintf(
                "Você deseja deletar um cliente usando:\n 0 - %s\n 1 - %s\n",
                $this->fieldsToFill[0]['label'],
                $this->fieldsToFill[1]['label']
            )
        );
        if (null === $value) {
            return;
        }
        $cliente = new Cliente();
        switch ($value) {
            case 0:
            case 1:
                $criteria = $this->generateCriteria((int)$value, $cliente);
                break;
            default:
                print "Campo inválido!\n";

                return;
                break;
        }

        if (null === $criteria) {
            return;
        }

        try {
            if ($this->repository->deleteBy($criteria, $cliente)) {
                print "Cliente deletado com sucesso!\n";
            } else {
                print "Erro ao deletar o cliente.\n";
            }
        } catch (QueryException $e) {
            print $e->getMessage();
        }
    }

    /**
     * @param string   $message
     * @param callable $validationFunction
     * @param callable $setFunction
     *
     * @return bool
     */
    private function getCreateInput(string $message, callable $validationFunction, callable $setFunction): bool
    {
        $value = $this->getConsoleInput($message);

        if (null === $value) {
            return false;
        }

        if (!$validationFunction($value)) {
            print "Campo inválido! Preencha novamente.\n";
            $this->getCreateInput($message, $validationFunction, $setFunction);
        } else {
            $setFunction($value);
        }

        return true;
    }

    /**
     * @param string $message
     *
     * @return string|null
     */
    private function getConsoleInput(string $message): ?string
    {
        print $message;
        print sprintf("(Ou digite %s para voltar ao menu principal)\n", self::EXIT_STRING);
        $handle = fopen("php://stdin", "r");
        $value = trim(fgets($handle));
        fclose($handle);

        if (strtoupper($value) === self::EXIT_STRING) {
            return null;
        }

        return $value;
    }

    /**
     * @param int                                $fieldIndex
     *
     * @param \SimplePhpCrud\Entity\Cliente|null $clienteEntity
     *
     * @return \Doctrine\Common\Collections\Criteria|null
     */
    private function generateCriteria(int $fieldIndex, ?Cliente &$clienteEntity = null): ?Criteria
    {
        $criteria = Criteria::create();
        if (null === $clienteEntity) {
            $clienteEntity = new Cliente();
        }
        $checkInput = $this->getCreateInput(
            sprintf(self::INSERT_BASE_MESSAGE, $this->fieldsToFill[$fieldIndex]['label']),
            [ClienteValidation::class, sprintf('isValid%s', $this->fieldsToFill[$fieldIndex]['fieldName'])],
            [$clienteEntity, sprintf('set%s', $this->fieldsToFill[$fieldIndex]['fieldName'])]
        );
        if (!$checkInput) {
            return null;
        }
        $getFieldFunction = [$clienteEntity, sprintf('get%s', $this->fieldsToFill[$fieldIndex]['fieldName'])];
        $criteria->where(
            Criteria::expr()
                    ->eq($this->fieldsToFill[$fieldIndex]['columnName'], $getFieldFunction())
        );

        return $criteria;
    }

    /**
     * @param array $table
     */
    private function printTable(array $table): void
    {
        print implode(' | ', array_keys($table[0])) . "\n";
        foreach ($table as $row) {
            print implode(' | ', $row) . "\n";
        }
        print "\n";
    }
}

<?php

declare(strict_types=1);

namespace SimplePhpCrud\BusinessCase;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\QueryException;
use SimplePhpCrud\Entity\Client;
use SimplePhpCrud\Repository\ClientRepository;
use SimplePhpCrud\Validation\ClientValidation;

/**
 * Class ClientBusinessCase
 */
class ClientBusinessCase
{
    private const EXIT_STRING = 'EXIT';
    private const INSERT_BASE_MESSAGE = "Please inform %s of the client:\n";

    /**
     * @var array
     */
    private $fieldsToFill = [
        ['fieldName' => 'Cpf', 'label' => 'CPF', 'columnName' => 'cpf'],
        ['fieldName' => 'Email', 'label' => 'E-mail', 'columnName' => 'email'],
        ['fieldName' => 'FullName', 'label' => 'Nome Completo', 'columnName' => 'fullName'],
        ['fieldName' => 'Phone', 'label' => 'phone', 'columnName' => 'phone'],
    ];

    /**
     * @var \SimplePhpCrud\Repository\ClientRepository
     */
    private $repository;

    /**
     * ClientBusinessCase constructor.
     *
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = new ClientRepository($entityManager);
    }

    /**
     * Insert/Update a new Client in the database
     */
    public function createUpdateClient(): void
    {
        $ClientEntity = new Client();
        foreach ($this->fieldsToFill as $field) {
            $result = $this->getCreateInput(
                sprintf(self::INSERT_BASE_MESSAGE, $field['label']),
                [ClientValidation::class, sprintf('isValid%s', $field['fieldName'])],
                [$ClientEntity, sprintf('set%s', $field['fieldName'])]
            );
            if (!$result) {
                print "Returning to the main menu.";

                return;
            }
        }
        $this->repository->persist($ClientEntity);
        print "New client registered with success!\n";
    }

    /**
     * Search and show a list of Clients
     */
    public function findClient(): void
    {
        $message = "Search client by:\n";
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
                print "\nData not find!\n\n";
            }
        } catch (QueryException $e) {
            print $e->getMessage();
        }
    }

    /**
     * Delete process of an Client entity
     */
    public function deleteClient(): void
    {
        $value = $this->getConsoleInput(
            sprintf(
                "Deleting client using:\n 0 - %s\n 1 - %s\n",
                $this->fieldsToFill[0]['label'],
                $this->fieldsToFill[1]['label']
            )
        );
        if (null === $value) {
            return;
        }
        $Client = new Client();
        switch ($value) {
            case 0:
            case 1:
                $criteria = $this->generateCriteria((int)$value, $Client);
                break;
            default:
                print "Invalid field!\n";

                return;
                break;
        }

        if (null === $criteria) {
            return;
        }

        try {
            if ($this->repository->deleteBy($criteria, $Client)) {
                print "Client deleted with success!\n";
            } else {
                print "Error while deleting client.\n";
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
            print "Invalid field! Please fill it again.\n";
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
        print sprintf("(Or type %s to go back to the main menu)\n", self::EXIT_STRING);
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
     * @param \SimplePhpCrud\Entity\Client|null $ClientEntity
     *
     * @return \Doctrine\Common\Collections\Criteria|null
     */
    private function generateCriteria(int $fieldIndex, ?Client &$ClientEntity = null): ?Criteria
    {
        $criteria = Criteria::create();
        if (null === $ClientEntity) {
            $ClientEntity = new Client();
        }
        $checkInput = $this->getCreateInput(
            sprintf(self::INSERT_BASE_MESSAGE, $this->fieldsToFill[$fieldIndex]['label']),
            [ClientValidation::class, sprintf('isValid%s', $this->fieldsToFill[$fieldIndex]['fieldName'])],
            [$ClientEntity, sprintf('set%s', $this->fieldsToFill[$fieldIndex]['fieldName'])]
        );
        if (!$checkInput) {
            return null;
        }
        $getFieldFunction = [$ClientEntity, sprintf('get%s', $this->fieldsToFill[$fieldIndex]['fieldName'])];
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

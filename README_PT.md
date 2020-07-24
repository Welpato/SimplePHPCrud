# Descrição
Este é um simples CRUD via linha de comando para cadastro de Clients 
usando apenas PHP e a ORM Doctrine para a manipulação de entidates e bancos de dados.
O objectivo desta aplicação é apenas servir como um exemplo de como é simples
criar um banco de dados e executar todas as operações básicas utilizando Doctrine.

## Como executar a aplicação
Para executar a aplicação é necesserio apenas  realizar a instalação dos requisitos via composer com o comando:

```composer install```

Em seguida é necessário criar o banco dados através do comando:

```vendor/bin/doctrine orm:schema-tool:create```

E a partir disso para inicie a aplicação através do comando:

```php application.php```

Caso alguma alteração seja realizada você pode tambêm checar se seu código está seguindo 
os padrões PSR2 através do comando:

```composer run phpcs```

## Requerimentos
- PHP ^7.2.27
- Composer
- Doctrine 2.7
- JMS Serializer ^3.8

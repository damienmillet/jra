<?php

namespace Core;

use Core\Config;

/**
 * Class BddManager
 */
class BddManager
{
    /**
     * @var \PDO The PDO instance for database connection.
     */
    private $pdo;


    /**
     * Constructor for the BddManager class.
     *
     * @return void
     */
    public function __construct()
    {
        try {
            $config = Config::getInstance();

            $host     = $config->get('DB_HOST', 'localhost');
            $port     = $config->get('DB_PORT', '3306');
            $dbName   = $config->get('DB_NAME', 'jra');
            $username = $config->get('DB_USER', 'username');
            $password = $config->get('DB_PASS', 'password');

            // Création de la connexion PDO
            $dsn = "mysql:host={$host}:{$port};dbname={$dbName}";
            $this->setPDO(new \PDO($dsn, $username, $password));
            // Configuration des attributs pour gérer les erreurs
            $this->getPdo()->setAttribute(
                \PDO::ATTR_ERRMODE,
                \PDO::ERRMODE_EXCEPTION
            );
            // Résultats sous forme de tableau associatif par defaut
            $this->getPdo()->setAttribute(
                \PDO::ATTR_DEFAULT_FETCH_MODE,
                \PDO::FETCH_ASSOC
            );
        } catch (\PDOException $e) {
            // Gestion des erreurs de connexion
            die('Erreur de connexion à la base de données : ' . $e->getMessage());
        }
    }


    /**
     * Getter of the PDO instance.
     *
     * @return \PDO The PDO instance.
     */
    public function getPdo()
    {
        return $this->pdo;
    }


    /**
     * Setter for the PDO instance.
     *
     * @param \PDO $pdo The PDO instance.
     *
     * @return BddManager The current instance.
     */
    public function setPdo(\PDO $pdo): BddManager
    {
        $this->pdo = $pdo;
        return $this;
    }


    /**
     * Executes a query and returns the results.
     *
     * @param string $sql       The SQL query to execute.
     * @param array  $params    The parameters to bind to the query.
     * @param string $className The name of the class to instantiate for each row.
     *
     * @return array The query results.
     */
    public function query($sql, $params = [], $className = null)
    {
        try {
            // Préparation de la requête
            $stmt = $this->getPdo()->prepare($sql);
            // Exécution de la requête avec les paramètres
            $stmt->execute($params);
            // Retourner les résultats sous forme d'objets
            return $stmt->fetchAll(\PDO::FETCH_CLASS, $className);
        } catch (\PDOException $e) {
            // Gestion des erreurs d'exécution de la requête
            die('Erreur lors de l\'exécution de la requête : ' . $e->getMessage());
        }
    }


    /**
     * Find all.
     *
     * @return object[] Array of objects.
     */
    public function findAll(): array
    {
        $class     = $this->getEntityClass();
        $pdo       = $this->getPdo();
        $tableName = $this->getTableName($class);
        $stmt      = $pdo->prepare("SELECT * FROM $tableName");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, $class);
    }


    /**
     * Find an entity by ID.
     *
     * @param integer $id The ID of the entity.
     *
     * @return object|null The entity object or null if not found.
     */
    public function findOneById(int $id): ?object
    {
        $class     = $this->getEntityClass();
        $pdo       = $this->getPdo();
        $tableName = $this->getTableName($class);
        $stmt      = $pdo->prepare("SELECT * FROM $tableName WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $response = $stmt->fetchObject($class);
        if (empty($response)) {
            return null;
        }

        return $response;
    }


    /**
     * Check if a key exists in the table.
     *
     * @param string $key   The key to check.
     * @param string $value The value to check.
     *
     * @return boolean True if the key exists, false otherwise.
     */
    public function exist(string $key, string $value): bool
    {
        $class     = $this->getEntityClass();
        $pdo       = $this->getPdo();
        $tableName = $this->getTableName($class);
        $stmt      = $pdo->prepare("SELECT * FROM $tableName WHERE $key = :value");
        $stmt->execute([':value' => $value]);
        $response = $stmt->fetchObject($class);
        if (empty($response)) {
            return false;
        }

        return true;
    }


    /**
     * Find One by key.
     *
     * @param string $key   The key searched.
     * @param mixed  $value The value for the key.
     *
     * @return object|null The user object or null if not found.
     */
    public function findOneBy(string $key, mixed $value): ?object
    {
        $class     = $this->getEntityClass();
        $pdo       = $this->getPdo();
        $tableName = $this->getTableName($class);
        $stmt      = $pdo->prepare("SELECT * FROM $tableName WHERE $key = :key");
        $stmt->execute([':key' => $value]);
        return $stmt->fetchObject($class) ?: null;
    }


    /**
     * Delete One from database.
     *
     * @param string $id The parameters for the query.
     *
     * @return integer The deleted user object.
     */
    public function deleteOneById(string $id): int
    {
        try {
            $class     = $this->getEntityClass();
            $pdo       = $this->getPdo();
            $tableName = $this->getTableName($class);
            $stmt      = $pdo->prepare("DELETE FROM $tableName WHERE id = :id");
            $stmt->execute([':id' => (int) $id]);
            $response = $stmt->rowCount() > 0;
        } catch (\PDOException $e) {
            $response = false;
        }

        return $response;
    }


    /**
     * Get the table name for a given class.
     *
     * @param string The class name.
     *
     * @return string The table name.
     */
    private function getTableName(string $class): string
    {
        // Utiliser la réflexion pour obtenir le nom de la classe sans le namespace
        $reflection = new \ReflectionClass($class);
        $shortName  = $reflection->getShortName();

        // Convertir le nom de la classe en nom de table (par exemple, User -> users)
        return strtolower($shortName) . 's';
    }


    /**
     * Get the entity class name from the manager class name.
     *
     * @return string The entity class name.
     */
    private function getEntityClass(): string
    {
        // Utiliser la réflexion pour obtenir le nom de la classe du gestionnaire
        $reflection = new \ReflectionClass($this);
        $shortName  = $reflection->getShortName();

        // Remplacer "Manager" par "" pour obtenir le nom de la classe de l'entité
        $entityClass = str_replace('Manager', '', $shortName);

        // Ajouter le namespace des entités
        return 'Entities\\' . $entityClass;
    }
    /**
     * Get the SQL condition based on the operator.
     *
     * @param string $operator The comparison operator.
     *
     * @return string The SQL condition.
     */
    protected function getCondition(string $operator): string
    {
        switch ($operator) {
            case 'lt':
                return '<';

            case 'gt':
                return '>';

            case 'lte':
                return '<=';

            case 'gte':
                return '>=';

            case 'eq':
            default:
                return '=';
        }
    }
}

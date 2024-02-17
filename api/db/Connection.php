<?php
/**
 * @author Martin Hansson, maha6445.
 * 
 * Wrapper for the mysqli class with defined functions for SQL statements. 
 */

declare(strict_types = 1);

require_once 'Database.php';
require_once 'User.php';

/**
 * Creates a connection using mysqli to the database.
 * Database is a database with host and database.
 * User is a database user with username and password.
 */
class Connection {
    private mysqli $connection;

    public function __construct(Database $database, User $user) {
        $this->connection = new mysqli(
            $database->getHostname(),
            $user->getUsername(), 
            $user->getPassword(), 
            $database->getDatabase()
        );
    }

    /**
     * Executes SELECT query on the specified table.
     * 
     * @param table the table to execute SQL query on.
     * @return mysqli_result if the query succeeded.
     * @return false if the query failed.
     */
    public function selectAll(string $table): mysqli_result | bool {
        // Construct SQL query.
        $result = $this->connection->query("SELECT * FROM {$table}");
        
        // If there is no result, return false.
        if (!$result) return false;
        // Else return the result.
        else return $result;
    }

    /**
     * Executes SELECT query on the specified table filtering on id.
     * 
     * @param table the table to execute SQL query on.
     * @param id the id to filter.
     * @return mysqli_result if the query succeeded.
     * @return false if the query failed.
     */
    public function selectById(string $table, int $id): mysqli_result | bool {
        // Construct SQL query.
        $sql = "SELECT * FROM posts WHERE id=?";
        try {
            // Construct statement and bind parameters.
            $statement = $this->connection->prepare($sql);
            $statement->bind_param('i', $id);

            // Execute query.
            $statement->execute();

            // Return the results.
            return $statement->get_result();

        } catch (mysqli_sql_exception $exception) {
            // Rollback if query failed.
            $this->connection->rollback();
            return false;
        }
    }

    /**
     * Executes INSERT INTO query on the specified table using the specified values.
     * Rollback to previous state if the query fails.
     * 
     * @param table the table to execute SQL query on.
     * @param values the values to insert.
     * @return true if the operation succeeded.
     * @return false if the operation failed.
     */
    public function insertInto(string $table, array $values): bool {
        // Get columns and values from input array.
        $columns = array_keys($values);
        $data = array_values($values);

        // Generate SQL query.
        $sql = $this->generateInsertStatement($table, $columns);

        try {
            // Construct statement and bind parameters.
            $statement = $this->connection->prepare($sql);
            $statement->bind_param(str_repeat('s', count($values)), ...$data);

            // Try to execute query.
            return $statement->execute();

        } catch (mysqli_sql_exception $exception) {
            // Rollback if query failed.
            $this->connection->rollback();
            return false;
        }
    }
 
    /**
     * Helper function that prepares an INSERT INTO query depending on number of columns.
     * 
     * @param table the table to execute SQL query on.
     * @param columns the columns in the table.
     * @return string the constructed query.
     */
    private function generateInsertStatement(string $table, array $columns): string {
        // Prepare SQL statement
        $sql = "INSERT INTO {$table} (";
        $i = 0;
        // Add columns
        for ($i = 0; $i < count($columns); $i++) {
            if ($i == count($columns) - 1) $sql .= $columns[$i];
            else $sql .= $columns[$i] . ', ';
        }
        // Add ? marker for each column.
        $sql .= ') VALUES (';
        for ($i = 0; $i < count($columns); $i++) {
            if ($i == count($columns) - 1) $sql .= '?';
            else $sql .= '?, ';
        }
        $sql .= ')';

        return $sql;
    }
}
<?php
/**
 * @author Martin Hansson, maha6445.
 * 
 * Class representing a database.
 */

declare(strict_types = 1);

/**
 * A database.
 * Used to contain information about the host and database names.
 */
class Database {
    private string $hostname;
    private string $database;

    public function __construct($hostname, $database) {
        $this->hostname = $hostname;
        $this->database = $database;
    }

    public function getHostname(): string {
        return $this->hostname;
    }

    public function getDatabase(): string {
        return $this->database;
    }
}
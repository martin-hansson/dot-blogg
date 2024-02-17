<?php
/**
 * @author Martin Hansson, maha6445.
 * 
 * Class to represent a database user.
 */

declare(strict_types = 1);

/**
 * A database user.
 * Used to contain information about the username and password.
 */
class User {
    private string $username;
    private string $password;

    public function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getPassword(): string {
        return $this->password;
    }
}
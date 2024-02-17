<?php
/**
 * @author Martin Hansson, maha6445.
 * 
 * Wrapper class for the Blog api.
 */

declare(strict_types = 1);

require_once dirname(__DIR__) . '/api/db/User.php';
require_once dirname(__DIR__) . '/api/db/Database.php';
require_once dirname(__DIR__) . '/api/db/Connection.php';
require_once dirname(__DIR__) . '/api/model/Post.php';

/** 
 * Define the username, password, hostname, database 
 * and table for the api connection.
 */ 
define('USERNAME', '');
define('PASSWORD', '');
define('HOSTNAME', '');
define('DATABASE', '');
define('TABLE', '');

/**
 * API for the blog.
 * Uses Connection to communicate with db using SQL functions.
 * User is a database user with username and password.
 * Database is a database with host and database.
 */
class Blog {
    private Connection $connection;

    public function __construct() {
        $user = new User(USERNAME, PASSWORD);
        $database = new Database(HOSTNAME, DATABASE);
        $this->connection = new Connection($database, $user);
    }

    /**
     * Get all posts in the database.
     * Constructs a Post for each row and returns them.
     * 
     * @return array of all the posts in the database.
     */
    public function getPosts(): array {
        $result = $this->connection->selectAll('posts');

        $posts = [];
        if ($result) {
            while ($row = $result->fetch_array()) {
                // Create a new Post from the data.
                $post = new Post(
                    $row['id'],
                    $row['title'],
                    $row['author'],
                    $row['date'],
                    $row['content'],
                    $row['media']
                );

                // Add to array of all posts.
                array_push($posts, $post);
            }
        }

        return $posts;
    }

    /**
     * Get a specific post with an id.
     * Constructs a Post from the result and returns it.
     * 
     * @return Post of the result.
     * @return false if there was no post with id.
     */
    public function getPost(int $id): Post | bool {
        $result = $this->connection->selectById('posts', $id);

        if ($result) {
            while ($row = $result->fetch_array()) {
                $post = new Post(
                    $row['id'],
                    $row['title'],
                    $row['author'],
                    $row['date'],
                    $row['content'],
                    $row['media']
                );

                return $post;
            }
        }

        return false;
    }

    /**
     * Add a new post to the database.
     * 
     * @return true if the operation succeeded.
     * @return false if the operation failed.
     */
    public function createPost(string $title, string $author, string $content, string $image): bool {
        $result = $this->connection->insertInto('posts', [
            'title' => $title,
            'author' => $author,
            'content' => $content,
            'image' => $image
        ]);

        return $result;
    }
}
<?php
ini_set ('display_errors', 1);

// Import api wrapper.
require_once './api/Blog.php';
$blog = new Blog();

// If its a GET request construct the feed.
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Get the html from the template file.
    $templateHTML = file_get_contents('./template/template.html');
    $templateParts = explode('<!--$content-->', $templateHTML);

    // Get the html from the view file.
    $indexHTML = file_get_contents('./view/feed.html');
    $indexParts = explode('<!--$posts-->', $indexHTML);

    // Get all posts from the database, reverse so latest is first.
    $posts = $blog->getPosts();
    $posts = array_reverse($posts);

    // Add view and template html together.
    $indexParts[1] = implode($posts);
    $templateParts[1] = implode($indexParts);
    $templateHTML = implode($templateParts);

    // Send constructed page to client.
    echo $templateHTML;
}

// If its a post request, add new post to database and reload page.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if required fields are set.
    if (isset($_POST['title']) 
        AND isset($_POST['author']) 
        AND isset($_POST['post'])) {
            // Strip data from special chars and tags.
            cleanFormData($_POST);

            // Get the required fields
            $title = $_POST['title'];
            $author = $_POST['author'];
            $post = $_POST['post'];

            // Move uploaded file if exists.
            if (isset($_FILES['image']) AND file_exists($_FILES['image']['tmp_name'])) {
                $filename = pathinfo($_FILES['image']['tmp_name'])['basename'];
                $extension = pathinfo($_FILES['image']['name'])['extension'];

                $image = './media/' . $filename . '.' . $extension;
                move_uploaded_file($_FILES['image']['tmp_name'], $image);
            } else {
                $image = '';
            }

            // Add new post to database with input values.
            $post = $blog->createPost($title, $author, $post, $image);

            // Reload page.
            header('Location: http://localhost/wprog2/9/');
    }
}

/**
 * Helper function that clears HTML special chars and tags
 * from input values.
 * 
 * @param values to clean.
 */
function cleanFormData(array &$values): void {
    foreach ($values as $key => $value) {
        $value = htmlspecialchars($value);
        $value = strip_tags($value);
    }
}
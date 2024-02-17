<?php
/**
 * @author Martin Hansson, maha6445.
 * 
 * Generates the HTML page with a form used to create new posts.
 */

// Get the html from the template and view and construct page.
$templateHTML = file_get_contents('./template/template.html');
$templateParts = explode('<!--$content-->', $templateHTML);
$indexParts[1] = file_get_contents('./view/post-form.html');;
$templateParts[1] = implode($indexParts);
$templateHTML = implode($templateParts);

echo $templateHTML;
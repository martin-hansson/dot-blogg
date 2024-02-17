<?php
/**
 * @author Martin Hansson, maha6445.
 * 
 * Class that represents the <article> element.
 */

declare(strict_types = 1);

require_once 'Element.php';

class Article extends Element {
    public function __construct($content = [], ...$attributes) {
        parent::__construct('article', $content, $attributes);
    }
}
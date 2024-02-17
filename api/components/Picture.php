<?php
/**
 * @author Martin Hansson, maha6445.
 * 
 * Class that represents the <picture> element.
 */

declare(strict_types = 1);

require_once 'Element.php';

class Picture extends Element {
    public function __construct($content, ...$attributes) {
        parent::__construct('picture', $content, $attributes);
    }
}
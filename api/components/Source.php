<?php
/**
 * @author Martin Hansson, maha6445.
 * 
 * Class that represents the <source> element.
 */

declare(strict_types = 1);

require_once 'VoidElement.php';

class Source extends VoidElement {
    public function __construct($attributes) {
        parent::__construct('source', $attributes);
    }
}
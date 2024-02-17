<?php
/**
 * @author Martin Hansson, maha6445.
 * 
 * Class that represents the <img> element.
 */

require_once 'VoidElement.php';

class Image extends VoidElement {
    public function __construct($attributes) {
        parent::__construct('img', $attributes);
    }
}
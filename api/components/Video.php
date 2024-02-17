<?php
/**
 * @author Martin Hansson, maha6445.
 * 
 * Class that represents the <video> element.
 */

declare(strict_types = 1);

require_once 'Element.php';

class Video extends Element {
    public function __construct($content, ...$attributes) {
        parent::__construct('video', $content, $attributes);
    }
}
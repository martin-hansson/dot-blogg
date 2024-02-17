<?php
/**
 * @author Martin Hansson, maha6445.
 * 
 * Class that represents the <div> element.
 */

declare(strict_types = 1);

require_once 'Element.php';

class Div extends Element {
    public function __construct($content = [], ...$attributes) {
        parent::__construct('div', $content, $attributes);
    }
}
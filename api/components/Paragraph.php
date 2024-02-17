<?php
/**
 * @author Martin Hansson, maha6445.
 * 
 * Class that represents the <p> element.
 */

declare(strict_types = 1);

require_once 'Element.php';

class Paragraph extends Element {
    public function __construct($content = [], ...$attributes) {
        if (is_string($content)) parent::__construct('p', [$content], $attributes);
        else parent::__construct('p', $content, $attributes);
    }
}
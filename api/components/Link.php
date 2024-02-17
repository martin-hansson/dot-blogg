<?php
/**
 * @author Martin Hansson, maha6445.
 * 
 * Class that represents the <a> element.
 */

declare(strict_types = 1);

require_once 'Element.php';

class Link extends Element {
    public function __construct($content, ...$attributes) {
        if (is_string($content)) parent::__construct('a', [$content], $attributes);
        else parent::__construct('a', $content, $attributes);
    }
}
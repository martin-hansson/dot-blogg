<?php
/**
 * @author Martin Hansson, maha6445.
 * 
 * Class that represents the <button> element.
 */

declare(strict_types = 1);

require_once 'Element.php';

class Button extends Element {
    public function __construct($content = [], ...$attributes) {
        if (is_string($content)) parent::__construct('button', [$content], $attributes);
        else parent::__construct('button', $content, $attributes);
    }
}
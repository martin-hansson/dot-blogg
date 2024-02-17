<?php
/**
 * @author Martin Hansson, maha6445.
 * 
 * Class that represents a self closing HTML element.
 */

require_once 'Tag.php';

abstract class VoidElement extends Tag {
    public function __construct($tag, ...$attributes) {
        parent::__construct($tag, $attributes);
    }

    /**
     * Returns a formatted HTML tag.
     */
    public function render(): string {
        // Add self closing tag.
        $element = PHP_EOL;
        $element .= '<' . $this->tag;
        foreach ($this->attributes as $attribute) {
            foreach ($attribute as $name => $value) {
                if (empty($value)) $element .= ' ' . $name;
                else $element .= ' ' . $name . '="' . $value . '"';
            }
        }
        $element .= ' />';

        return $element;
    }
}
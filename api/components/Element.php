<?php
/**
 * @author Martin Hansson, maha6445.
 * 
 * Class that represents an HTML element.
 */

declare(strict_types = 1);

require_once 'Tag.php';

/**
 * Abstract class of an HTML element.
 * Can add children elements and render render itself
 * as a formatted HTML tag.
 */
abstract class Element extends Tag {
    private array $content;

    public function __construct($tag, $content, ...$attributes) {
        parent::__construct($tag, $attributes[0]);
        $this->content = $content;
    }

    /**
     * Adds a new child element.
     * 
     * @param element to be added.
     */
    public function add($element): void {
        array_push($this->content, $element);
    }

    /**
     * Returns a formatted HTML tag.
     * 
     * @return string of the element formatted as HTML.
     */
    public function render(): string {
        // Add opening tag.
        $element = PHP_EOL;
        $element .= '<' . $this->tag;
        foreach ($this->attributes as $attribute) {
            foreach ($attribute as $name => $value) {
                if (empty($value)) $element .= ' ' . $name;
                else $element .= ' ' . $name . '="' . $value . '"';
            }
        }
        $element .= ' >';
        
        // Add content.
        foreach ($this->content as $child => $childElement) {
            if (is_string($childElement)) $element .= $childElement;
            else if (is_array($childElement)) {
                echo $child;
            }
            else {
                $element .= $childElement->render();
            }
        }

        // Add closing tag.
        $element .= '</' . $this->tag . '>';

        return $element;
    }
}


<?php
/**
 * @author Martin Hansson, maha6445.
 * 
 * Class that represents an HTML tag.
 */

abstract class Tag {
    protected string $tag;
    protected array $attributes;
    
    protected function __construct($tag, ...$attributes) {
        $this->tag = $tag;
        $this->attributes = $attributes[0];
    } 
}
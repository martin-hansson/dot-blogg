<?php
/**
 * @author Martin Hansson, maha6445.
 * 
 * Class that represents <h1> to <h6> element.
 */

declare(strict_types = 1);

require_once 'Element.php';

class Heading extends Element {
    private int $level;

    /**
     * @param level the level of the heading.
     */
    public function __construct($level, $content, ...$attributes) {
        if (is_string($content)) parent::__construct("h{$level}", [$content], $attributes);
        else parent::__construct("h{$level}", $content, $attributes);
        $this->level = $level;
    }
}
<?php
/**
 * @author iAtomPlaza
 * @link https://twitter.com/iAtomPlaza
 */

namespace atom\afterlife\utils;


class FileManager {

    private $data;

    public function executeSelect(string $filename, ?callable $onSelect = null) :void {
        if (is_file($filename)) $this->data = yaml_parse_file($filename);
        if ($onSelect !== null) $onSelect($this->data);
    }
}
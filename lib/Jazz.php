<?php

class Jazz
{
    static function render($node)
    {
        $out = "";

        if (is_array($node)) {
            if (is_string($node[0]) and substr($node[0], 0, 1) == "#") {
                $out .= static::renderHtmlTag($node);
            } else {
                foreach ($node as $n) {
                    $out .= static::render($n);
                }
            }
        } else {
            $out .= $node;
        }

        return $out;
    }

    protected static function renderHtmlTag($node)
    {
        # Strip the leading "#"
        $tagName    = substr(array_shift($node), 1);
        $attributes = array();

        if (sizeof($node) == 1) {
            if (is_array($node[0])) {
                is_string(key($node[0])) ? $attributes = $node[0] : $content = $node[0];
            } else {
                $content = $node[0];
            }
        } else if (sizeof($node) == 2) {
            list($attributes, $content) = $node;

        } else if (sizeof($node) > 2) {
            throw new \UnexpectedValueException(
                "Tags must not consist of more than three elements"
            );
        }

        $out = "<$tagName";

        foreach ($attributes as $attr => $value) {
            $out .= " $attr=\"$value\"";
        }

        if (!isset($content)) {
            $out .= " />";
            return $out;
        }

        $out .= ">";
        $out .= $content ? static::render($content) : "";
        $out .= "</$tagName>";

        return $out;
    }
}

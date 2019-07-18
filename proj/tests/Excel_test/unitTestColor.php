<?php

namespace Colors;

class Display
{
    const CLEAR = "\e[0m";
    const ERROR = "\e[41;97m";
    const WARNING = "\e[43;30m";
    const OK      = "\e[42;30m";
    public static function error($text, $newline = true) {
        $text = self::ERROR . $text . self::CLEAR;
        $text .= $newline ? "\n" : "";
        return $text;
    }
    public static function warning($text, $newline = true) {
        $text = self::WARNING . $text . self::CLEAR;
        $text .= $newline ? "\n" : "";
        return $text;
    }
    public static function OK($text, $newline = true) {
        $text = self::OK . $text . self::CLEAR;
        $text .= $newline ? "\n" : "";
        return $text;
    }
}
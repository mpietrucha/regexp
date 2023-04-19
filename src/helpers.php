<?php

if (! function_exists('regexp')) {
    function regexp(string $name): Regexp {
        return Regexp::create($name);
    }
}

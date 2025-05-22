<?php

function getFirstWords($text, $limit = 100, $suffix = '...') {
    // Split the text by whitespace
    $words = preg_split('/\s+/', $text);

    // If word count exceeds limit, return trimmed + suffix
    if (count($words) > $limit) {
        return implode(' ', array_slice($words, 0, $limit)) . $suffix;
    }

    // Otherwise, return original text
    return $text;
}

?>
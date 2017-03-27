<?php

namespace common\helpers;

class TagHelper
{
    public static function splitTags($text)
    {
        // Remove any apostrophes or dashes which aren't part of words
        $text = substr(preg_replace('%((?<=[^\p{L}\p{N}])[\'\-]|[\'\-](?=[^\p{L}\p{N}]))%u', '', ' ' . $text . ' '), 1, -1);
        
        // Remove punctuation and symbols (actually anything that isn't a letter or number), allow apostrophes and dashes (and % * if we aren't indexing)
        $text = preg_replace('%(?![\'\-\%\*])[^\p{L}\p{N}]+%u', ' ', $text);

        // Replace multiple whitespace or dashes
        $text = preg_replace('%(\s){2,}%u', '\1', $text);

        // Fill an array with all the words
        $tags = array_unique(explode(' ', trim($text)));

        foreach ($tags as $key => $tag) {
            if ($tag === '') {
                unset($tags[$key]);
            }
        }

        return $tags;
    }

}

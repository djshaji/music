<?php

function album_format_title ($album) {
    $album_js = str_replace (' ', '_', $album) ;
    $album_js = str_replace (':', '_', $album_js) ;
    $album_js = str_replace ('-', '_', $album_js) ;
    $album_js = str_replace ('(', '_', $album_js) ;
    $album_js = str_replace (')', '_', $album_js) ;

    // Uncaught SyntaxError: Numeric separators are not allowed at the end of numeric literals
    $album_js = $album_js . '_full' ;
    return $album_js ;
}

?>
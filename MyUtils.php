<?php

class MyUtils {

    public static function redirect($url, $permanent = false) {
        // https://ourcodeworld.com/articles/read/252/how-to-redirect-to-a-page-with-plain-php
        if (headers_sent() === false) {
            header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
        }
        exit();
    }

}

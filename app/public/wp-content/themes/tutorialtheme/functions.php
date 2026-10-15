<?php

function testing(){
        add_theme_support("title-tag");
        // telling wp to use dynamic values
        add_theme_support("post-thumbnails");
        // telling wp to use featured imgs
}

add_action("after_setup_theme", "testing");
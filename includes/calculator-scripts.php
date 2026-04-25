<?php

// Add scripts

function sl_add_script(){

    wp_enqueue_style('calculator-main-style', plugins_url().'/roof-calculator/css/style.css?v.2.1');
    // wp_enqueue_script('pdf-script', 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js');
    // wp_enqueue_script('table-script','https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.30/jspdf.plugin.autotable.min.js');
    wp_enqueue_script('calculator-main-script', plugins_url().'/roof-calculator/js/main.js?v.2.2',array('jquery'));
}

add_action('wp_enqueue_scripts','sl_add_script');
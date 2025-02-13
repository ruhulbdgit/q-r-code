<?php

/**
 * Plugin Name: QR Code Plugin
 * Plugin URI: #
 * Description: Basic QR Code
 * Version: 0.0.1
 * Author: Ruhul Siddiki
 * Author URI: #
 */

class Basic_QR_Code
{
    public function __construct()
    {
        add_action('init', array($this, 'initialize'));
    }

    function initialize()
    {
        add_filter('the_content', [$this, 'display_qr_code']);
    }

    function display_qr_code($content)
    {
        $current_post_url = get_permalink();
        $qr_code_image = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . $current_post_url;
        $newcontent = $content . "<p><img src='{$qr_code_image}'></p>";
        return $newcontent;
        // return $content."<p>URL: {$current_post_url}</p>";
    }
}

new Basic_QR_Code();

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
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    function initialize()
    {
        add_filter('the_content', [$this, 'display_qr_code']);
    }

    function display_qr_code($content)
    {
        $current_post_url = get_permalink();

        // Color can Change  from admin panel
        $foreground_color = get_option('qr_foreground_color', '000000'); // ডিফল্ট কালো
        $background_color = get_option('qr_background_color', 'ffffff'); // ডিফল্ট সাদা
        $qr_size = get_option('qr_code_size', 150); // ডিফল্ট 150px

        // qr code gererator
        $qr_code_image = "https://api.qrserver.com/v1/create-qr-code/?size={$qr_size}x{$qr_size}&data={$current_post_url}&color={$foreground_color}&bgcolor={$background_color}";

        $newcontent = $content . "<p><img src='{$qr_code_image}'></p>";
        return $newcontent;
    }

    function add_admin_menu()
    {
        add_options_page(
            'QR Code Settings',
            'QR Code Settings',
            'manage_options',
            'qr_code_settings',
            array($this, 'settings_page')
        );
    }

    function register_settings()
    {
        register_setting('qr_settings_group', 'qr_foreground_color');
        register_setting('qr_settings_group', 'qr_background_color');
        register_setting('qr_settings_group', 'qr_code_size');
    }

    function settings_page()
    {
?>
        <div class="wrap">
            <h1>QR Code Customization</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('qr_settings_group');
                do_settings_sections('qr_settings_group');
                ?>
                <table class="form-table">
                    <tr>
                        <th>QR Code Color:</th>
                        <td><input type="text" name="qr_foreground_color" value="<?php echo esc_attr(get_option('qr_foreground_color', '000000')); ?>" placeholder="000000"></td>
                    </tr>
                    <tr>
                        <th>Background Color:</th>
                        <td><input type="text" name="qr_background_color" value="<?php echo esc_attr(get_option('qr_background_color', 'ffffff')); ?>" placeholder="ffffff"></td>
                    </tr>
                    <tr>
                        <th>QR Code Size:</th>
                        <td><input type="number" name="qr_code_size" value="<?php echo esc_attr(get_option('qr_code_size', 150)); ?>" min="50" max="500"></td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
<?php
    }
}

new Basic_QR_Code();


// class Basic_QR_Code
// {
//     public function __construct()
//     {
//         add_action('init', array($this, 'initialize'));
//     }

//     function initialize()
//     {
//         add_filter('the_content', [$this, 'display_qr_code']);
//     }

//     function display_qr_code($content)
//     {
//         $current_post_url = get_permalink();
//         $qr_code_image = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . $current_post_url;
//         $newcontent = $content . "<p><img src='{$qr_code_image}'></p>";
//         return $newcontent;
//         // return $content."<p>URL: {$current_post_url}</p>";
//     }
// }

// new Basic_QR_Code();

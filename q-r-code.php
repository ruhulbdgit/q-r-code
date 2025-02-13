<?php
/*
Plugin Name: Custom QR Code Generator
Plugin URI: #
Description: A feature-rich QR code generator with color, size, and custom text options Add Icon,Name,Link,Wifi-connect,Website browse etc .
Version: 1.0
Author: Ruhul siddiki
Author URI: #
License: GPL2
*/

// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

class Custom_QR_Code_Generator
{
    public function __construct()
    {
        // Add settings menu in the admin panel
        add_action('admin_menu', array($this, 'add_admin_menu'));

        // Register settings
        add_action('admin_init', array($this, 'register_settings'));

        // Enqueue color picker script
        add_action('admin_enqueue_scripts', array($this, 'enqueue_color_picker'));

        // Add QR code to content
        add_filter('the_content', array($this, 'display_qr_code'));

        // Register shortcode
        add_shortcode('qr_code', array($this, 'qr_code_shortcode'));
    }

    // Function to add settings menu in the WP dashboard
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

    // Function to register settings
    function register_settings()
    {
        register_setting('qr_settings_group', 'qr_foreground_color');
        register_setting('qr_settings_group', 'qr_background_color');
        register_setting('qr_settings_group', 'qr_code_size');
        register_setting('qr_settings_group', 'qr_custom_text');
    }

    // Function to display QR code
    function display_qr_code($content)
    {
        $data = get_option('qr_custom_text', '') ?: get_permalink();
        $size = get_option('qr_code_size', 150);
        $fg_color = str_replace('#', '', get_option('qr_foreground_color', '000000'));
        $bg_color = str_replace('#', '', get_option('qr_background_color', 'ffffff'));

        $qr_url = "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data={$data}&color={$fg_color}&bgcolor={$bg_color}";
        $qr_code_html = "<p><img src='{$qr_url}'><br><a href='{$qr_url}' download='QR-Code.png'>Download QR Code</a></p>";

        return $content . $qr_code_html;
    }

    // Function to create the settings page
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
                        <td><input type="text" id="qr_foreground_color" name="qr_foreground_color" value="<?php echo esc_attr(get_option('qr_foreground_color', '#000000')); ?>"></td>
                    </tr>
                    <tr>
                        <th>Background Color:</th>
                        <td><input type="text" id="qr_background_color" name="qr_background_color" value="<?php echo esc_attr(get_option('qr_background_color', '#ffffff')); ?>"></td>
                    </tr>
                    <tr>
                        <th>QR Code Size:</th>
                        <td><input type="number" name="qr_code_size" value="<?php echo esc_attr(get_option('qr_code_size', 150)); ?>" min="50" max="500"></td>
                    </tr>
                    <tr>
                        <th>Custom QR Code Data:</th>
                        <td><input type="text" name="qr_custom_text" value="<?php echo esc_attr(get_option('qr_custom_text', '')); ?>"></td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                jQuery("#qr_foreground_color").wpColorPicker();
                jQuery("#qr_background_color").wpColorPicker();
            });
        </script>
<?php
    }

    // Function to enqueue color picker script
    function enqueue_color_picker($hook)
    {
        if ($hook !== 'settings_page_qr_code_settings') return;
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('qr-color-picker', plugins_url('qr-color-picker.js', __FILE__), array('wp-color-picker'), false, true);
    }

    // Shortcode function
    function qr_code_shortcode()
    {
        $data = get_option('qr_custom_text', '') ?: get_permalink();
        $size = get_option('qr_code_size', 150);
        $fg_color = str_replace('#', '', get_option('qr_foreground_color', '000000'));
        $bg_color = str_replace('#', '', get_option('qr_background_color', 'ffffff'));

        $qr_url = "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data={$data}&color={$fg_color}&bgcolor={$bg_color}";
        return "<img src='{$qr_url}' />";
    }
}

// Initialize the plugin
new Custom_QR_Code_Generator();

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

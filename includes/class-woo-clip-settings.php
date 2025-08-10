<?php 
class Woo_CLIP_Settings {
    public function __construct() {
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function add_settings_page() {
        add_options_page(
            'CLIP Image Search',
            'CLIP Image Search',
            'manage_options',
            'woo-clip-image-search',
            [$this, 'settings_page_html']
        );
    }

    public function register_settings() {
        register_setting('woo_clip_image_search', 'woo_clip_api_key');
    }
    
    public function settings_page_html() {
        ?>
        <div class="wrap">
            <h1>WooCommerce CLIP Image Search Settings</h1>
            <form method="post" action="options.php">
                <?php settings_fields('woo_clip_image_search'); ?>
                <?php do_settings_sections('woo_clip_image_search'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">OpenAI API Key</th>
                        <td>
                            <input type="text" name="woo_clip_api_key" value="<?php echo esc_attr(get_option('woo_clip_api_key')); ?>" size="50" />
                            <br>
                            <br>
                            <!-- <a href="https://platform.openai.com/docs/guides/embeddings/what-are-embeddings" target="_blank">https://platform.openai.com/docs/guides/embeddings/what-are-embeddings</a> -->
                            <a href="https://platform.openai.com/settings/organization/api-keys" target="_blank">https://platform.openai.com/settings/organization/api-keys</a>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}
<?php
class Woo_CLIP_Search {
    public function __construct() {
        add_shortcode('clip_image_search', [$this, 'search_form']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    public function enqueue_scripts() {
        wp_enqueue_style('clip-style', plugin_dir_url(__FILE__) . 'assets/css/style.css');
        wp_enqueue_script( 'clip-script', plugin_dir_path(__FILE__). 'assets/js/script.js', ['jquery'], false, true );
        wp_localize_script('clip-script', 'clip_ajax', [
            'ajax_url' => admin_url('admin-ajax.php')
        ]);
    }

    public function search_form() {
        ob_start();
        ?>
        <form id="clip-search-form" enctype="multipart/form-data">
            <input type="file" name="search_image" accept="image/*" required>
            <button type="submit">Search by Image</button>
        </form>
        <div id="clip-results"></div>
        <?php
        return ob_get_clean();
    }
}
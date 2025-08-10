<?php
class Woo_CLIP_Search {
    public function __construct() {
        add_shortcode('clip_image_search', [$this, 'search_form']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('wp_ajax_clip_search', [$this, 'handle_search']);
        add_action('wp_ajax_nopriv_clip_search', [$this, 'handle_search']);
    }

    public function enqueue_assets() {
        wp_enqueue_style('clip-style', plugin_dir_url(__FILE__) . '../assets/style.css');
        wp_enqueue_script('clip-script', plugin_dir_url(__FILE__) . '../assets/script.js', ['jquery'], false, true);
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

    public function handle_search() {
        if (!isset($_FILES['search_image'])) {
            wp_send_json_error('No image uploaded.');
        }

        $uploaded = wp_handle_upload($_FILES['search_image'], ['test_form' => false]);
        if (isset($uploaded['error'])) {
            wp_send_json_error($uploaded['error']);
        }

        $image_url = $uploaded['url'];
        $api_key = get_option('woo_clip_api_key');

        if (!$api_key) {
            wp_send_json_error('API key not set.');
        }

        // Step 1: Get CLIP embedding for uploaded image
        $embedding = $this->get_clip_embedding($image_url, $api_key);

        // Step 2: Compare with stored product embeddings (mocked for now)
        $results = $this->find_similar_products($embedding);

        wp_send_json_success($results);
    }

    private function get_clip_embedding($image_url, $api_key) {
        $response = wp_remote_post('https://api.openai.com/v1/embeddings', [
            'headers' => [
                'Authorization' => 'Bearer ' . $api_key,
                'Content-Type'  => 'application/json'
            ],
            'body' => json_encode([
                'model' => 'clip', // Example model
                'input' => $image_url
            ])
        ]);

        if (is_wp_error($response)) {
            return [];
        }

        $data = json_decode(wp_remote_retrieve_body($response), true);
        return $data['data'][0]['embedding'] ?? [];
    }

    private function find_similar_products($search_embedding) {
        // TODO: Implement cosine similarity check with stored product embeddings
        
        // For now, just return first few products
        $args = [
            'post_type' => 'product',
            'posts_per_page' => 5
        ];
        $query = new WP_Query($args);
        $results = [];

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                global $product;
                $results[] = [
                    'title' => get_the_title(),
                    'url'   => get_permalink(),
                    'image' => get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'),
                    'price' => $product->get_price_html()
                ];
            }
        }
        wp_reset_postdata();
        return $results;
    }
}

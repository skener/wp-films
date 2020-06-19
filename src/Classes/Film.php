<?php


namespace Films;


class Film
{

    public function __construct()
    {
        add_action('init', [$this, 'film_post_type']);
        add_action('add_meta_boxes', [$this, 'add_film_metaboxes']);
        add_action('save_post_film', [$this, 'save_film_meta_post']);
        add_action('wp_footer', [$this, 'initScripts']);
    }

    /**
     * Init custom js scripts
     */
    public function initScripts()
    {
        wp_register_script('film-script', $src = plugins_url('wp-films/src/js/js.js'), null, microtime(), true);
        wp_enqueue_script('film-script');
    }


    public function activate()
    {
        $this->film_post_type();

        flush_rewrite_rules();
    }

    public function deactivate()
    {
        flush_rewrite_rules();
    }


    public function film_post_type()
    {
        flush_rewrite_rules();

        register_taxonomy(
            'genre',
            'film',
            [
                'labels' => [
                    'name' => __('Genre'),
                    'singular_name' => __('Genre'),
                ],
                'hierarchical' => true,
                'show_admin_column' => true,
                'public' => true,
                'show_ui' => true,
                'show_in_nav_menus' => true,
                'show_tagcloud' => true,
                'show_in_rest' => true,
            ]
        );

        register_taxonomy(
            'country',
            'film',
            [
                'labels' => [
                    'name' => __('Country'),
                    'singular_name' => __('Country'),
                ],
                'hierarchical' => true,
                'show_admin_column' => true,
                'public' => true,
                'show_ui' => true,
                'show_in_nav_menus' => true,
                'show_tagcloud' => true,
                'show_in_rest' => true,
            ]
        );

        register_taxonomy(
            'actors',
            'film',
            [
                'labels' => [
                    'name' => __('Actor'),
                    'singular_name' => __('Actor'),
                ],
                'hierarchical' => true, //true if it is category like, false if it is  tag like
                'show_admin_column' => true,
                'public' => true,
                'show_ui' => true,
                'show_in_nav_menus' => true,
                'show_tagcloud' => true,
                'show_in_rest' => true
            ]
        );

        register_taxonomy(
            'year',
            'film',
            [
                'labels' => [
                    'name' => __('Year'),
                    'singular_name' => __('Year'),
                ],
                'hierarchical' => true, //true if it is category like, false if it is  tag like
                'show_admin_column' => true,
                'public' => true,
                'show_ui' => true,
                'show_in_nav_menus' => true,
                'show_tagcloud' => true,
                'show_in_rest' => true
            ]
        );

        register_post_type(
            'film',
            [
                'labels' => [
                    'name' => 'Film',
                    'add_new_item' => ' Add New Film',
                    'menu_name' => 'Films',
                    'singular_name' => 'Film'
                ],
                'public' => true,
                'publicly_queryable' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'query_var' => true,
                'rewrite' => array('slug' => 'film'),
                'capability_type' => 'post',
                'has_archive' => true,
                'hierarchical' => false,
                'menu_position' => null,
                'supports' => ['title', 'editor', 'author', 'custom-fields', 'page-attributes', 'revisions'],
                'taxonomies' => ['genre', 'country', 'actors', 'year'],
                'show_in_rest' => true
            ]
        );
    }

    public function film_form_markup_func()
    {
        global $post;
        wp_nonce_field(basename(__FILE__), 'film-extra-form-data-nonce');
        $title = get_post_meta($post->ID, 'film_title', true);
        $price = get_post_meta($post->ID, 'film_price', true);
        $date = get_post_meta($post->ID, 'film_date', true);

        require_once __DIR__ . './../templates/form.html';
    }


    public function add_film_metaboxes()
    {
        add_meta_box(
            'film_id',
            'Film-Extra-Form-Data',
            [$this, 'film_form_markup_func'],
            'film',
            'normal',
            'high',
            null
        );
    }


    public function save_film_meta_post($post_id)
    {
        // Check if our nonce is set.
        if (!isset($_POST['film-extra-form-data-nonce'])) {
            return $post_id;
        }

        $nonce = $_POST['film-extra-form-data-nonce'];

        // Verify that the nonce is valid.
        if (!wp_verify_nonce($nonce, basename(__FILE__))) {
            return $post_id;
        }

        if (isset($_POST['film_title'])):
            $title = sanitize_text_field($_POST['film_title']);
        endif;
        if (isset($_POST['film_price'])):
            $price = sanitize_text_field($_POST['film_price']);
        endif;
        if (isset($_POST['film_date'])):
            $date = sanitize_text_field($_POST['film_date']);
        endif;

        // Update the meta field.
        update_post_meta($post_id, 'film_title', $title);
        update_post_meta($post_id, 'film_price', $price);
        update_post_meta($post_id, 'film_date', $date);
    }

}
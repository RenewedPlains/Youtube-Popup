<?php
/**
* Plugin Name:       Youtube Popup
* Plugin URI:        https://plural.software
* Description:       Handle the basics with this plugin.
* Version:           1.0
* License:           GPL v2 or later
* Text Domain:       youtube-popup
*/

global $post;

function youtube_custom_post_type() {
    $labels = array(
        'name' => _x( 'Youtube Popup', 'Youtube Popup', 'twentytwenty' ),
        'singular_name' => _x( 'Youtube Popup', 'Youtube Popup', 'twentytwenty' ),
        'menu_name' => __( 'Youtube Popup', 'twentytwenty' ),
        'all_items' => __( 'Alle Popups', 'twentytwenty' ),
        'view_item' => __( 'Youtube Popups anzeigen', 'twentytwenty' ),
        'add_new_item' => __( 'Neues Youtube Popup', 'twentytwenty' ),
        'add_new' => __( 'Neues Youtube Popup', 'twentytwenty' ),
        'edit_item' => __( 'Youtube Popup bearbeiten', 'twentytwenty' ),
        'update_item' => __( 'Youtube Popup aktualisieren', 'twentytwenty' ),
        'search_items' => __( 'Suche Youtube Popup', 'twentytwenty' ),
        'not_found' => __( 'Nicht gefunden', 'twentytwenty' ),
        'not_found_in_trash' => __( 'Nicht gefunden im Papierkorb', 'twentytwenty' )
    );
    $args = array(
        'label' => __( 'youtube-popup', 'twentytwenty' ),
        'description' => __( 'Youtube Popups', 'twentytwenty' ),
        'labels' => $labels,
        'supports' => array( 'title', 'author', 'comments', 'revisions', 'custom-fields', ),
        'taxonomies' => array( 'category' ),
        'hierarchical' => false,
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => false,
        'menu_position' => 5,
        'can_export' => true,
        'has_archive' => false,
        'exclude_from_search' => true,
        'publicly_queryable' => false,
        'capability_type' => 'post',
        'show_in_rest' => true,
    );
    register_post_type( 'youtube-popup', $args );
    wp_enqueue_style( 'youtube-popup-style', plugin_dir_url( __FILE__ ) . 'youtube-popup.css' );
}
add_action( 'init', 'youtube_custom_post_type', 0 );

function youtube_popup_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'id' => '0'
        ), $atts, 'youtube-popup'
    );

    $args = array( 'post_type' => 'youtube-popup', 'posts_per_page' => 1, 'p' => $atts['id'] );
    $the_query = new WP_Query( $args );
    if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post();

        $content = get_post_meta(get_the_ID(), 'youtubelink', true);;
        $get_post_id = get_the_ID();
        $youtubeframe_holder = '<div id="youtube-popup-' . $get_post_id . '" class="youtube-popup-layer"><figure class="wp-block-embed is-type-video is-provider-youtube wp-block-embed-youtube wp-embed-aspect-16-9 wp-has-aspect-ratio"><div class="wp-block-embed__wrapper">
<iframe loading="lazy" title="Youtube embedded Video" src="' . $content . '?feature=oembed" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" style="max-width: 100%; max-height: 343px;" width="750" height="422" frameborder="0"></iframe>
</div></figure></div>';
    endwhile;
    endif;

  	return $youtubeframe_holder;
}
add_shortcode( 'youtube-popup', 'youtube_popup_shortcode' );


function youtube_popup_metaboxes( ) {
    global $wp_meta_boxes;
    add_meta_box('postfunctiondiv', __('Function'), 'youtube_popup_metaboxes_html', 'youtube-popup', 'normal', 'high');
}
add_action( 'add_meta_boxes_youtube-popup', 'youtube_popup_metaboxes' );

function youtube_popup_metaboxes_html()
{
    global $post;
    $custom = get_post_custom($post->ID);
    $youtubelink = isset($custom["youtubelink"][0])?$custom["youtubelink"][0]:'';
    ?>
    <label>Youtube Link:</label><input name="youtubelink" value="<?php echo $youtubelink; ?>">
    <?php
}

function youtube_popup_save_post()
{
    if(empty($_POST)) return; //why is prefix_teammembers_save_post triggered by add new?
    global $post;
    update_post_meta($post->ID, "youtubelink", $_POST["youtubelink"]);
}

add_action( 'save_post_youtube-popup', 'youtube_popup_save_post' );

function check_content_for_youtube_video()
{
    global $post;
    if (has_shortcode(get_the_content(), 'youtube-popup')) {
        wp_enqueue_script('youtube-popup', plugin_dir_url( __FILE__ ) . 'youtube-popup.js', array('jquery'), '', false);
    }
}
add_action( 'init', 'check_content_for_youtube_video', 0 );

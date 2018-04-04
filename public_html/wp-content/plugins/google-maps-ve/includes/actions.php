<?php

class Google_Maps_Ve_Actions
{
    
    /**
     * @var Google_Maps_Ve
     */
    protected $_google_maps;
    
    public function __construct($google_maps)
    {
        $this->_google_maps = $google_maps;
    }
    
    public function shortcode_gmap($post_id)
    {
        $map = get_post($post_id);
        if (!$map) {
            return '';
        }
        else if ($map->post_type !== Google_Maps_Ve_App::POST_TYPE_MAPS) {
            return '';
        }
        
    }
    
    public function save_post($post_id)
    {
        if (wp_is_post_revision($post_id)) {
            return $post_id;
        }
        if (!isset($_POST['ve_map_post_nonce'])) {
            return $post_id;
        }
        
        $nonce = $_POST['ve_map_post_nonce'];

        if (!wp_verify_nonce($nonce, 've_map_post')) {
            return $post_id;
        }

        if (defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE) {
            return $post_id;
        }
        
        $post = get_post($post_id);
        
        if ($post->post_type !== Google_Maps_Ve_App::POST_TYPE_MAPS) {
            return $post_id;
        }
        
        $markerMetaFields = array(
            'marker_address', 'marker_description', 'marker_lat', 
            'marker_long', 'marker_animation',
        );
        
        if (!empty($_POST['marker_lat']) && !empty($_POST['marker_long']) && !empty($_POST['marker_title'])) {
            $_POST['marker_lat'] = str_replace(',', '.', $_POST['marker_lat']);
            $_POST['marker_long'] = str_replace(',', '.', $_POST['marker_long']);

            if ($markerId = wp_insert_post(array(
                'post_title' => sanitize_text_field($_POST['marker_title']),
                'post_status' => 'publish',
                'post_type' => Google_Maps_Ve_App::POST_TYPE_MARKER,
                'post_parent' => $post_id,
                'post_content' => sanitize_text_field($_POST['marker_description']),
            ))) {
                foreach ($markerMetaFields as $field) {
                    $markerData = sanitize_text_field($_POST[$field]);
                    update_post_meta($markerId, $field, $markerData);
                }
            }
            
        }
        
        $fields = array(
            've_map_type', 've_map_width', 've_map_width_type', 've_map_height', 
            've_map_height_type', 've_map_zoom', 've_map_client_based_location', 
            've_map_lat', 've_map_long', 
            've_map_layer_transit', 've_map_layer_traffic', 've_map_layer_bicycling', 
            've_map_control_overview', 've_map_control_street_view', 've_map_control_scale',
            've_map_control_map_type', 've_map_control_zoom', 've_map_control_pan', 
        );
        $_POST['ve_map_lat'] = str_replace(',', '.', $_POST['ve_map_lat']);
        $_POST['ve_map_long'] = str_replace(',', '.', $_POST['ve_map_long']);
        
        foreach ($fields as $field) {
            $data = sanitize_text_field($_POST[$field]);
            update_post_meta($post_id, $field, $data);
        }
    }
    
    public function save_post_marker($post_id)
    {
        if (wp_is_post_revision($post_id)) {
            return $post_id;
        }
        if (!isset($_POST['ve_map_marker_post_nonce'])) {
            return $post_id;
        }
        
        $nonce = $_POST['ve_map_marker_post_nonce'];

        if (!wp_verify_nonce($nonce, 've_map_marker_post')) {
            return $post_id;
        }

        if (defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE) {
            return $post_id;
        }
        
        $post = get_post($post_id);
        
        if ($post->post_type !== Google_Maps_Ve_App::POST_TYPE_MARKER) {
            return $post_id;
        }

        $markerMetaFields = array(
            'marker_address', 'marker_animation', 
        );
        
        foreach ($markerMetaFields as $field) {
            $markerData = sanitize_text_field($_POST[$field]);
            update_post_meta($post_id, $field, $markerData);
        }
        
        if (!empty($_POST['marker_lat'])) {
            $lat = str_replace(',', '.', $_POST['marker_lat']);
            update_post_meta($post_id, 'marker_lat', sanitize_text_field($lat));
        }
        if (!empty($_POST['marker_long'])) {
            $long = str_replace(',', '.', $_POST['marker_long']);
            update_post_meta($post_id, 'marker_long', sanitize_text_field($long));
        }

        remove_action('save_post', array($this->_google_maps->getAction(), 'save_post_marker'));

        wp_update_post(array(
            'ID' => $post_id,
            'post_content' => $_POST['marker_description'],
        ));
        add_action('save_post', array($this->_google_maps->getAction(), 'save_post_marker'));
        
    }
    
    public function save_post_polygon($post_id)
    {
        if (wp_is_post_revision($post_id)) {
            return $post_id;
        }
        if (!isset($_POST['ve_map_polygon_post_nonce'])) {
            return $post_id;
        }
        
        $nonce = $_POST['ve_map_polygon_post_nonce'];

        if (!wp_verify_nonce($nonce, 've_map_polygon_post')) {
            return $post_id;
        }

        if (defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE) {
            return $post_id;
        }
        
        $post = get_post($post_id);
        
        if ($post->post_type !== Google_Maps_Ve_App::POST_TYPE_POLYGON) {
            return $post_id;
        }

        $markerMetaFields = array(
            'marker_polystring', 'marker_line_color', 'marker_fill_color', 'marker_line_opacity',
            'marker_fill_opacity', 'marker_line_opacity_hover', 'marker_fill_opacity_hover'
        );
        
        foreach ($markerMetaFields as $field) {
            $markerData = sanitize_text_field($_POST[$field]);
            update_post_meta($post_id, $field, $markerData);
        }

        remove_action('save_post', array($this->_google_maps->getAction(), 'save_post_polygon'));

        wp_update_post(array(
            'ID' => $post_id,
            'post_content' => $_POST['marker_description'],
            'post_parent' => sanitize_text_field($_POST['ve_marker_map']),
        ));
        add_action('save_post', array($this->_google_maps->getAction(), 'save_post_polygon'));
        
    }
    
    public function save_post_polyline($post_id)
    {
        if (wp_is_post_revision($post_id)) {
            return $post_id;
        }
        if (!isset($_POST['ve_map_polyline_post_nonce'])) {
            return $post_id;
        }
        
        $nonce = $_POST['ve_map_polyline_post_nonce'];

        if (!wp_verify_nonce($nonce, 've_map_polyline_post')) {
            return $post_id;
        }

        if (defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE) {
            return $post_id;
        }
        
        $post = get_post($post_id);
        
        if ($post->post_type !== Google_Maps_Ve_App::POST_TYPE_POLYLINE) {
            return $post_id;
        }

        $markerMetaFields = array(
            'marker_polystring', 'marker_line_color', 'marker_line_opacity', 'marker_line_opacity_hover', 
            'marker_fill_opacity_hover', 'marker_line_thick', 
        );
        
        foreach ($markerMetaFields as $field) {
            $markerData = sanitize_text_field($_POST[$field]);
            update_post_meta($post_id, $field, $markerData);
        }

        remove_action('save_post', array($this->_google_maps->getAction(), 'save_post_polyline'));

        wp_update_post(array(
            'ID' => $post_id,
            'post_content' => $_POST['marker_description'],
            'post_parent' => sanitize_text_field($_POST['ve_marker_map']),
        ));
        add_action('save_post', array($this->_google_maps->getAction(), 'save_post_polyline'));
        
    }
    
    public function delete_marker()
    {
        if (!empty($_GET['redirect_to'])) {
            wp_redirect($_GET['redirect_to']);
            exit;
        }
    }
    
    public function delete_map($postId)
    {
        $gmap = array(
            Google_Maps_Ve_App::POST_TYPE_MAPS,
            Google_Maps_Ve_App::POST_TYPE_MARKER,
            Google_Maps_Ve_App::POST_TYPE_POLYGON,
            Google_Maps_Ve_App::POST_TYPE_POLYLINE,
        );
        $children = get_children(array('post_per_page' => -1, 'post_parent', 'post_parent' => $postId));
        if (!empty($children)) {
            remove_action('trashed_post', array($this->_google_maps->getAction(), 'delete_marker'));
            remove_action('trashed_post', array($this->_google_maps->getAction(), 'delete_map'));
            foreach($children as $post){
                if (in_array($post->post_type, $gmap)) {
                    wp_delete_post($post->ID, true);
                }
            }
            add_action('trashed_post', array($this->_google_maps->getAction(), 'delete_marker'));
            add_action('trashed_post', array($this->_google_maps->getAction(), 'delete_map'));
        }
    }

}
<?php

class Google_Maps_Ve_App
{
    
    const POST_TYPE_MAPS = 'gmaps_map_ve';
    const POST_TYPE_MARKER = 'gmaps_marker_ve';
    const POST_TYPE_POLYGON = 'gmaps_polygon_ve';
    const POST_TYPE_POLYLINE = 'gmaps_polyline_ve';
    const TAXONOMY_MARKER = 'marker_category';
    
    /**
     * @var Google_Maps_Ve
     */
    protected $_google_maps;
    
    public function __construct($google_maps)
    {
        $this->_google_maps = $google_maps;
        add_action('init', array($this, 'register_post_types'));
        add_action('init', array($this, 'register_taxonomies'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this->_google_maps->getAction(), 'save_post'));
        add_action('save_post', array($this->_google_maps->getAction(), 'save_post_marker'));
        add_action('save_post', array($this->_google_maps->getAction(), 'save_post_polygon'));
        add_action('save_post', array($this->_google_maps->getAction(), 'save_post_polyline'));
        add_shortcode('ve_gmaps', array($this->_google_maps->getAction(), 'shortcode_gmap'));
        add_filter('manage_' . self::POST_TYPE_MARKER . '_posts_columns', array($this, 'marker_list_columns'));
        add_action('manage_' . self::POST_TYPE_MARKER . '_posts_custom_column', array($this, 'add_marker_list_column'), 10, 2 );
        add_action('admin_init', array($this, 'manage_permissions'));
        add_action('trashed_post', array($this->_google_maps->getAction(), 'delete_marker'));
        add_action('trashed_post', array($this->_google_maps->getAction(), 'delete_map'));
        add_shortcode('ve_gmap', array($this, 'map_shortcode'));
    }
    
    public function register_post_types()
    {
        $labels = array(
            'name' => _x('Google Maps', 'post type general name', GOOGLE_MAPS_VE_TD),
            'singular_name' => _x('Google Maps', 'post type singular name', GOOGLE_MAPS_VE_TD),
            'add_new' => _x('Add new map', 'Google Map', GOOGLE_MAPS_VE_TD),
            'add_new_item' => __('Add new Google Map', GOOGLE_MAPS_VE_TD),
            'edit_item' => __('Edit Google Map', GOOGLE_MAPS_VE_TD),
            'new_item' => __('Add Google Map', GOOGLE_MAPS_VE_TD),
            'all_items' => __('List of maps', GOOGLE_MAPS_VE_TD),
            'view_item' => __('View maps', GOOGLE_MAPS_VE_TD),
            'search_items' => __('Search maps', GOOGLE_MAPS_VE_TD),
            'not_found' =>  __('Maps not found', GOOGLE_MAPS_VE_TD),
            'not_found_in_trash' => __('Maps not found in trash', GOOGLE_MAPS_VE_TD), 
            'parent_item_colon' => '',
            'menu_name' => 'Google Map'
        );
        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true, 
            'show_in_menu' => true, 
            'query_var' => true,
            'rewrite' => true,
            'menu_icon' => 'dashicons-location-alt',
            'capability_type' => 'post',
            'has_archive' => true, 
            'hierarchical' => false,
            'menu_position' => 5,
            'supports' => array( 'title', 'thumbnail' ),
            'taxonomies' => array('marker_category'),
        ); 
        register_post_type(self::POST_TYPE_MAPS, $args);
        
        $labels = array(
            'name' => _x('Google Maps marker', 'post type general name', GOOGLE_MAPS_VE_TD),
            'singular_name' => _x('Google Maps marker', 'post type singular name', GOOGLE_MAPS_VE_TD),
            'add_new' => _x('Add new', 'Google Map marker', GOOGLE_MAPS_VE_TD),
            'add_new_item' => __('Add new Google Map marker', GOOGLE_MAPS_VE_TD),
            'edit_item' => __('Edit Google Map marker', GOOGLE_MAPS_VE_TD),
            'new_item' => __('Add Google Map marker', GOOGLE_MAPS_VE_TD),
            'all_items' => __('List of all markers', GOOGLE_MAPS_VE_TD),
            'view_item' => __('View markers', GOOGLE_MAPS_VE_TD),
            'search_items' => __('Search markers', GOOGLE_MAPS_VE_TD),
            'not_found' =>  __('Markers not found', GOOGLE_MAPS_VE_TD),
            'not_found_in_trash' => __('Markers not found in trash', GOOGLE_MAPS_VE_TD), 
            'parent_item_colon' => '',
            'menu_name' => 'List of all markers',
        );
        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true, 
            'show_in_menu' => true, 
            'query_var' => true,
            'rewrite' => true,
            'capability_type' => 'post',
            'capabilities' => array(
                'create_posts' => false,
                'edit_post' => 'edit_post',
                'delete_post' => 'delete_post',
            ),
            'map_meta_cap' => true,
            'has_archive' => true, 
            'hierarchical' => false,
            'menu_position' => 5,
            'taxonomies' => array('marker_category'),
            'supports' => array('title', 'thumbnail'),
            'show_in_menu' => 'edit.php?post_type=' . self::POST_TYPE_MAPS,
        ); 
        register_post_type(self::POST_TYPE_MARKER, $args);
        
        $labels = array(
            'name' => _x('Google Maps polygon', 'post type general name', GOOGLE_MAPS_VE_TD),
            'singular_name' => _x('Google Maps polygon', 'post type singular name', GOOGLE_MAPS_VE_TD),
            'add_new' => _x('Add new', 'Google Map polygon', GOOGLE_MAPS_VE_TD),
            'add_new_item' => __('Add new Google Map polygon', GOOGLE_MAPS_VE_TD),
            'edit_item' => __('Edit Google Map polygon', GOOGLE_MAPS_VE_TD),
            'new_item' => __('Add Google Map polygon', GOOGLE_MAPS_VE_TD),
            'all_items' => __('List of all polygons', GOOGLE_MAPS_VE_TD),
            'view_item' => __('View polygons', GOOGLE_MAPS_VE_TD),
            'search_items' => __('Search polygons', GOOGLE_MAPS_VE_TD),
            'not_found' =>  __('Polygons not found', GOOGLE_MAPS_VE_TD),
            'not_found_in_trash' => __('Polygons not found in trash', GOOGLE_MAPS_VE_TD), 
            'parent_item_colon' => '',
            'menu_name' => 'List of all polygons',
        );
        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true, 
            'show_in_menu' => true, 
            'query_var' => true,
            'rewrite' => true,
            'capability_type' => 'post',
            'map_meta_cap' => true,
            'has_archive' => true, 
            'hierarchical' => false,
            'menu_position' => 5,
            'taxonomies' => array('marker_category'),
            'supports' => array('title', 'thumbnail'),
            'show_in_menu' => 'edit.php?post_type=' . self::POST_TYPE_MAPS,
        ); 
        register_post_type(self::POST_TYPE_POLYGON, $args);
        
        $labels = array(
            'name' => _x('Google Maps polyline', 'post type general name', GOOGLE_MAPS_VE_TD),
            'singular_name' => _x('Google Maps polyline', 'post type singular name', GOOGLE_MAPS_VE_TD),
            'add_new' => _x('Add new', 'Google Map polyline', GOOGLE_MAPS_VE_TD),
            'add_new_item' => __('Add new Google Map polyline', GOOGLE_MAPS_VE_TD),
            'edit_item' => __('Edit Google Map polyline', GOOGLE_MAPS_VE_TD),
            'new_item' => __('Add Google Map polyline', GOOGLE_MAPS_VE_TD),
            'all_items' => __('List of all polylines', GOOGLE_MAPS_VE_TD),
            'view_item' => __('View polylines', GOOGLE_MAPS_VE_TD),
            'search_items' => __('Search polylines', GOOGLE_MAPS_VE_TD),
            'not_found' =>  __('Polylines not found', GOOGLE_MAPS_VE_TD),
            'not_found_in_trash' => __('Polylines not found in trash', GOOGLE_MAPS_VE_TD), 
            'parent_item_colon' => '',
            'menu_name' => 'List of all polylines',
        );
        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true, 
            'show_in_menu' => true, 
            'query_var' => true,
            'rewrite' => true,
            'capability_type' => 'post',
            'map_meta_cap' => true,
            'has_archive' => true, 
            'hierarchical' => false,
            'menu_position' => 5,
            'taxonomies' => array('marker_category'),
            'supports' => array('title', 'thumbnail'),
            'show_in_menu' => 'edit.php?post_type=' . self::POST_TYPE_MAPS,
        ); 
        register_post_type(self::POST_TYPE_POLYLINE, $args);

        flush_rewrite_rules();
        
        if (class_exists('Polylang')) {
            add_filter('pll_get_post_types', array($this, 'pll_get_post_types'));
        }
    }
    
    public function register_taxonomies()
    {
        $labels = array(
            'name'                       => _x( 'Marker parameters', 'taxonomy general name', GOOGLE_MAPS_VE_TD),
            'singular_name'              => _x( 'Marker parameter', 'taxonomy singular name', GOOGLE_MAPS_VE_TD),
            'search_items'               => __( 'Search marker parameters', GOOGLE_MAPS_VE_TD),
            'all_items'                  => __( 'All marker parameters', GOOGLE_MAPS_VE_TD),
            'edit_item'                  => __( 'Edit parameter', GOOGLE_MAPS_VE_TD),
            'update_item'                => __( 'Update parameter', GOOGLE_MAPS_VE_TD),
            'add_new_item'               => __( 'Add New parameter', GOOGLE_MAPS_VE_TD),
            'menu_name'                  => __( 'Marker parameters', GOOGLE_MAPS_VE_TD),
        );
        $args = array(
            'labels' => $labels,
            'rewrite' => array('slug' => self::TAXONOMY_MARKER),
            'hierarchical' => true,
        );
        register_taxonomy('marker_category', self::POST_TYPE_MAPS, $args);
    }
    
    public function pll_get_post_types($types) {
        return array_merge($types, array(self::POST_TYPE_MAPS => self::POST_TYPE_MAPS));
    }
    
    public function getMapMarkers($mapId)
    {
        $markers = get_posts(array(
            'posts_per_page' => -1,
            'post_parent' => (int) $mapId,
            'post_type' => Google_Maps_Ve_App::POST_TYPE_MARKER,
        ));
        foreach ($markers as $k => $marker) {
            $marker->icon = GOOGLE_MAPS_VE_DEFAULT_MARKER;
            $marker->address = get_post_meta($marker->ID, 'marker_address', true);
            $marker->marker_lat = get_post_meta($marker->ID, 'marker_lat', true);
            $marker->marker_long = get_post_meta($marker->ID, 'marker_long', true);
            $marker->animation = get_post_meta($marker->ID, 'marker_animation', true);
        }
        return $markers;
    }
    
    public function getMap($mapId)
    {
        if (!$map = get_post($mapId)) {
            return false;
        }
        $map->map_type = get_post_meta($mapId, 've_map_type', true);
        if (empty($map->map_type)) {
            $map->map_type = Google_Maps_Ve_Defs::MAP_TYPE_ROADMAP;
        }
        $map->map_width = get_post_meta($mapId, 've_map_width', true);
        $map->map_width_type = get_post_meta($mapId, 've_map_width_type', true);
        
        if (empty($map->map_width)) {
            $map->map_width = 100;
        }
        if (empty($map->map_width_type)) {
            $map->map_width_type = Google_Maps_Ve_Defs::MAP_SIZE_TYPE_PERC;
        }
        
        $map->map_height = get_post_meta($mapId, 've_map_height', true);
        if (empty($map->map_height)) {
            $map->map_height = 400;
        }
        $map->map_height_type = get_post_meta($mapId, 've_map_height_type', true);
        if (empty($map->map_height_type)) {
            $map->map_height_type = Google_Maps_Ve_Defs::MAP_SIZE_TYPE_PX;
        }
        $map->map_zoom = (int) get_post_meta($mapId, 've_map_zoom', true);
        if (empty($map->map_zoom)) {
            $map->map_zoom = 10;
        }
        $map->client_based_location = get_post_meta($mapId, 've_map_client_based_location', true);
        $map->map_lat = get_post_meta($mapId, 've_map_lat', true);
        if (!is_numeric($map->map_lat) || empty($map->map_lat)) {
            $map->map_lat = -34.397;
        }
        $map->map_long = get_post_meta($mapId, 've_map_long', true);
        if (!is_numeric($map->map_long) || empty($map->map_long)) {
            $map->map_long = 150.644;
        }
        $map->map_show_get_visitor_position = get_post_meta($mapId, 've_show_get_visitor_position', true); 
        
        $map->map_layer_transit = get_post_meta($mapId, 've_map_layer_transit', true);
        $map->map_layer_traffic = get_post_meta($mapId, 've_map_layer_traffic', true);
        $map->map_layer_bicycling = get_post_meta($mapId, 've_map_layer_bicycling', true);
        
        $map->map_control_pan = get_post_meta($mapId, 've_map_control_pan', true);
        $map->map_control_zoom = get_post_meta($mapId, 've_map_control_zoom', true);
        $map->map_control_map_type = get_post_meta($mapId, 've_map_control_map_type', true);
        $map->map_control_scale = get_post_meta($mapId, 've_map_control_scale', true);
        $map->map_control_street_view = get_post_meta($mapId, 've_map_control_street_view', true);
        $map->map_control_overview = get_post_meta($mapId, 've_map_control_overview', true);
        
        return $map;
    }
    
    public function getMarker($markerId)
    {
        if (!$marker = get_post($markerId)) {
            return false;
        }
        $marker->icon = GOOGLE_MAPS_VE_DEFAULT_MARKER;
        $marker->address = get_post_meta($marker->ID, 'marker_address', true);
        $marker->marker_lat = get_post_meta($marker->ID, 'marker_lat', true);
        $marker->marker_long = get_post_meta($marker->ID, 'marker_long', true);
        $marker->animation = get_post_meta($marker->ID, 'marker_animation', true);
        
        return $marker;
    }
    
    public function getPolygon($markerId)
    {
        if (!$marker = get_post($markerId)) {
            return false;
        }
        $marker->polystring = get_post_meta($marker->ID, 'marker_polystring', true);
        
        $marker->marker_line_color = get_post_meta($marker->ID, 'marker_line_color', true);
        $marker->marker_fill_color = get_post_meta($marker->ID, 'marker_fill_color', true);
        $marker->marker_line_opacity = get_post_meta($marker->ID, 'marker_line_opacity', true);
        $marker->marker_fill_opacity = get_post_meta($marker->ID, 'marker_fill_opacity', true);
        $marker->marker_line_opacity_hover = get_post_meta($marker->ID, 'marker_line_opacity_hover', true);
        $marker->marker_fill_opacity_hover = get_post_meta($marker->ID, 'marker_fill_opacity_hover', true);
        
        if (empty($marker->marker_line_color)) {
            $marker->marker_line_color = '#333333';
        }
        if (empty($marker->marker_fill_color)) {
            $marker->marker_fill_color = '#426dc5';
        }
        if (strlen($marker->marker_line_opacity) == 0) {
            $marker->marker_line_opacity = '0.5';
        }
        if (strlen($marker->marker_fill_opacity) == 0) {
            $marker->marker_fill_opacity = '0.3';
        }
        if (strlen($marker->marker_line_opacity_hover) == 0) {
            $marker->marker_line_opacity_hover = '0.8';
        }
        if (strlen($marker->marker_fill_opacity_hover) == 0) {
            $marker->marker_fill_opacity_hover = '0.6';
        }
        return $marker;
    }
    
    public function getMapPolygons($mapId)
    {
        $markers = get_posts(array(
            'posts_per_page' => -1,
            'post_parent' => $mapId,
            'post_type' => Google_Maps_Ve_App::POST_TYPE_POLYGON,
        ));
        foreach ($markers as $k => $marker) {
            $marker->polystring = get_post_meta($marker->ID, 'marker_polystring', true);
            
            $marker->marker_line_color = get_post_meta($marker->ID, 'marker_line_color', true);
            $marker->marker_fill_color = get_post_meta($marker->ID, 'marker_fill_color', true);
            $marker->marker_line_opacity = get_post_meta($marker->ID, 'marker_line_opacity', true);
            $marker->marker_fill_opacity = get_post_meta($marker->ID, 'marker_fill_opacity', true);
            $marker->marker_line_opacity_hover = get_post_meta($marker->ID, 'marker_line_opacity_hover', true);
            $marker->marker_fill_opacity_hover = get_post_meta($marker->ID, 'marker_fill_opacity_hover', true);
            
            if (empty($marker->marker_line_color)) {
                $marker->marker_line_color = '#333333';
            }
            if (empty($marker->marker_fill_color)) {
                $marker->marker_fill_color = '#426dc5';
            }
            if (strlen($marker->marker_line_opacity) == 0) {
                $marker->marker_line_opacity = '0.5';
            }
            if (strlen($marker->marker_fill_opacity) == 0) {
                $marker->marker_fill_opacity = '0.3';
            }
            if (strlen($marker->marker_line_opacity_hover) == 0) {
                $marker->marker_line_opacity_hover = '0.8';
            }
            if (strlen($marker->marker_fill_opacity_hover) == 0) {
                $marker->marker_fill_opacity_hover = '0.6';
            }
        }
        return $markers;
    }
    
    public function getPolyline($markerId)
    {
        if (!$marker = get_post($markerId)) {
            return false;
        }
        $marker->polystring = get_post_meta($marker->ID, 'marker_polystring', true);
        
        $marker->marker_line_color = get_post_meta($marker->ID, 'marker_line_color', true);
        $marker->marker_line_thick = get_post_meta($marker->ID, 'marker_line_thick', true);
        $marker->marker_line_opacity = get_post_meta($marker->ID, 'marker_line_opacity', true);
        $marker->marker_line_opacity_hover = get_post_meta($marker->ID, 'marker_line_opacity_hover', true);
        
        if (empty($marker->marker_line_color)) {
            $marker->marker_line_color = '#333333';
        }
        if (empty($marker->marker_line_thick)) {
            $marker->marker_line_thick = 3;
        }
        if (strlen($marker->marker_line_opacity) == 0) {
            $marker->marker_line_opacity = '0.5';
        }
        if (strlen($marker->marker_line_opacity_hover) == 0) {
            $marker->marker_line_opacity_hover = '0.8';
        }
        return $marker;
    }
    
    public function getMapPolylines($mapId)
    {
        $markers = get_posts(array(
            'posts_per_page' => -1,
            'post_parent' => $mapId,
            'post_type' => Google_Maps_Ve_App::POST_TYPE_POLYLINE,
        ));
        foreach ($markers as $k => $marker) {
            $marker->polystring = get_post_meta($marker->ID, 'marker_polystring', true);
            
            $marker->marker_line_color = get_post_meta($marker->ID, 'marker_line_color', true);
            $marker->marker_line_thick = get_post_meta($marker->ID, 'marker_line_thick', true);
            $marker->marker_line_opacity = get_post_meta($marker->ID, 'marker_line_opacity', true);
            $marker->marker_line_opacity_hover = get_post_meta($marker->ID, 'marker_line_opacity_hover', true);
            
            if (empty($marker->marker_line_color)) {
                $marker->marker_line_color = '#333333';
            }
            if (empty($marker->marker_line_thick)) {
                $marker->marker_line_thick = 3;
            }
            if (strlen($marker->marker_line_opacity) == 0) {
                $marker->marker_line_opacity = '0.5';
            }
            if (strlen($marker->marker_line_opacity_hover) == 0) {
                $marker->marker_line_opacity_hover = '0.8';
            }
        }
        return $markers;
    }
    
    public function add_meta_boxes()
    {
        add_meta_box(
            'map_type',
            __('Map settings', GOOGLE_MAPS_VE_TD),
            array($this->_google_maps->getView(), 'render_meta_box_map_general'),
            self::POST_TYPE_MAPS
        );
        remove_meta_box('marker_categorydiv', self::POST_TYPE_MAPS, 'side');
        
        add_meta_box(
            'map_add_polygon',
            __('Polygon settings', GOOGLE_MAPS_VE_TD),
            array($this->_google_maps->getView(), 'render_meta_box_add_polygon'),
            self::POST_TYPE_POLYGON
        );
        
        add_meta_box(
            'polygon_map',
            __('Map', GOOGLE_MAPS_VE_TD),
            array($this->_google_maps->getView(), 'render_meta_box_polygon_map_container'),
            self::POST_TYPE_POLYGON
        );
        
        add_meta_box(
            'map_add_polyline',
            __('Polyline settings', GOOGLE_MAPS_VE_TD),
            array($this->_google_maps->getView(), 'render_meta_box_add_polyline'),
            self::POST_TYPE_POLYLINE
        );
        
        add_meta_box(
            'polyline_map',
            __('Map', GOOGLE_MAPS_VE_TD),
            array($this->_google_maps->getView(), 'render_meta_box_polyline_map_container'),
            self::POST_TYPE_POLYLINE
        );
        
        global $post;

        
        if ($post->filter !== 'raw') {
            add_meta_box(
                'map_container',
                __('Your map', GOOGLE_MAPS_VE_TD),
                array($this->_google_maps->getView(), 'render_meta_box_map_container'),
                self::POST_TYPE_MAPS
            );
            
            add_meta_box(
                'map_add_marker',
                __('Add new marker', GOOGLE_MAPS_VE_TD),
                array($this->_google_maps->getView(), 'render_meta_box_map_add_marker'),
                self::POST_TYPE_MAPS
            );
            
            add_meta_box(
                'map_manage_markers',
                __('List of added markers', GOOGLE_MAPS_VE_TD),
                array($this->_google_maps->getView(), 'render_meta_box_map_markers_list'),
                self::POST_TYPE_MAPS
            );
            
            add_meta_box(
                'map_manage_polygon',
                __('List of added polygons', GOOGLE_MAPS_VE_TD),
                array($this->_google_maps->getView(), 'render_meta_box_map_polygons_list'),
                self::POST_TYPE_MAPS
            );
            
            add_meta_box(
                'map_manage_polylines',
                __('List of added polylines', GOOGLE_MAPS_VE_TD),
                array($this->_google_maps->getView(), 'render_meta_box_map_polylines_list'),
                self::POST_TYPE_MAPS
            );
            
            add_meta_box(
                'marker_settings',
                __('Marker settings', GOOGLE_MAPS_VE_TD),
                array($this->_google_maps->getView(), 'render_meta_box_marker_settings'),
                self::POST_TYPE_MARKER
            );
            
            add_meta_box(
                'marker_map',
                __('Map', GOOGLE_MAPS_VE_TD),
                array($this->_google_maps->getView(), 'render_meta_box_marker_map_container'),
                self::POST_TYPE_MARKER
            );
        }
    }
    
    public function marker_list_columns($columns)
    {
        return array(
            'cb' => '<input type="checkbox" />',
            'title' => __('Title'),
            'map' => __('Map', GOOGLE_MAPS_VE_TD),
            'date' => __('Date'),
        );
    }
    
    public function add_marker_list_column($column, $post_id)
    {
        switch ( $column ) {
    
            case 'map' :
                $post = get_post($post_id);
                if (!empty($post->post_parent)) {
                    echo '<a href="' . get_edit_post_link($post->post_parent) . '">' . esc_html(get_the_title($post->post_parent)) . '</a>';
                }
                break;
    
        }
    }
    
    public function manage_permissions()
    {
        
    }
    
    public function map_shortcode($params)
    {
        $mapId = $params['map'];
        if (!$map = $this->getMap($mapId)) {
            return '';
        }
        $markers = $this->getMapMarkers($mapId);
        $polygons = $this->getMapPolygons($mapId);
        $polylines = $this->getMapPolylines($mapId);
        ob_start();
        ?>
        <div class="ve-gmap-wrapper" id="ve-gmap-container-<?php echo $mapId; ?>" style="width: <?php echo $map->map_width; ?><?php echo $map->map_width_type; ?>; height: <?php echo $map->map_height; ?><?php echo $map->map_height_type; ?>;"></div>
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
        <script>
        function gmap_initialize<?php echo $mapId; ?>() {
          window.ve_gmaps[<?php echo $mapId; ?>] = new google.maps.Map(document.getElementById('ve-gmap-container-<?php echo $mapId; ?>'), {
              zoom: <?php echo (int) $map->map_zoom; ?>,
              center: new google.maps.LatLng(<?php echo $map->map_lat; ?>, <?php echo $map->map_long; ?>),
              mapTypeId: google.maps.MapTypeId.<?php echo $map->map_type; ?>,
              panControl: <?php echo Google_Maps_Ve_Defs::getJavascriptBoolean($map->map_control_pan); ?>,
              zoomControl: <?php echo Google_Maps_Ve_Defs::getJavascriptBoolean($map->map_control_zoom); ?>,
              mapTypeControl: <?php echo Google_Maps_Ve_Defs::getJavascriptBoolean($map->map_control_map_type); ?>,
              scaleControl: <?php echo Google_Maps_Ve_Defs::getJavascriptBoolean($map->map_control_scale); ?>,
              streetViewControl: <?php echo Google_Maps_Ve_Defs::getJavascriptBoolean($map->map_control_street_view); ?>,
              overviewMapControl: <?php echo Google_Maps_Ve_Defs::getJavascriptBoolean($map->map_control_overview); ?>
          });

          google.maps.event.addDomListener(window, "resize", function() {
              google.maps.event.trigger(window.ve_gmaps[<?php echo $mapId; ?>], "resize");
          });

          <?php if ($map->map_layer_bicycling == Google_Maps_Ve_Defs::MAP_OPTION_YES): ?>
          new google.maps.BicyclingLayer().setMap(window.ve_gmaps[<?php echo $mapId; ?>]);
          <?php endif; ?>
          <?php if ($map->map_layer_transit == Google_Maps_Ve_Defs::MAP_OPTION_YES): ?>
          new google.maps.TransitLayer().setMap(window.ve_gmaps[<?php echo $mapId; ?>]);
          <?php endif; ?>
          <?php if ($map->map_layer_traffic == Google_Maps_Ve_Defs::MAP_OPTION_YES): ?>
          new google.maps.TrafficLayer().setMap(window.ve_gmaps[<?php echo $mapId; ?>]);
          <?php endif; ?>
          <?php if ($map->client_based_location == Google_Maps_Ve_Defs::MAP_OPTION_YES): ?>
          jQuery(window).load(function() {
              ve_map_update_positions_by_visitor(<?php echo $mapId; ?>);
          });
          <?php endif; ?>
          window.ve_gmap_markers[<?php echo $mapId; ?>] = {};
          <?php foreach ($markers as $marker): ?>
              <?php if (empty($marker->marker_lat) || empty($marker->marker_long)) { continue; } ?>
              window.ve_gmap_markers[<?php echo $mapId; ?>][<?php echo $marker->ID; ?>] = new google.maps.Marker({
                  position: new google.maps.LatLng(<?php echo esc_html($marker->marker_lat); ?>, <?php echo esc_html($marker->marker_long); ?>), 
                  map: window.ve_gmaps[<?php echo $mapId; ?>],
                  icon: '<?php echo $marker->icon; ?>'
                  //draggable:true
              });
              
              <?php if ($marker->animation !== Google_Maps_Ve_Defs::MAP_ANIMATION_NONE): ?>
              window.ve_gmap_markers[<?php echo $mapId; ?>][<?php echo $marker->ID; ?>].setAnimation(google.maps.Animation.<?php echo $marker->animation; ?>);
              <?php endif; ?>
          <?php endforeach; ?>

          window.ve_gmap_polygons[<?php echo $mapId; ?>] = {};
          <?php foreach ($polygons as $marker): ?>
              <?php if (empty($marker->polystring)) { continue; } ?>
              <?php 
              $polyString = json_decode($marker->polystring);
              ?>
              
              var polyPaths = [];
              <?php foreach ($polyString as $poly): ?>
                  polyPaths.push(new google.maps.LatLng(<?php echo $poly[0]; ?>, <?php echo $poly[1]; ?>));
              <?php endforeach; ?>
              var bounds = new google.maps.LatLngBounds();
              for (i = 0; i < polyPaths.length; i++) {
            	  bounds.extend(polyPaths[i]);
            	}

              window.ve_gmap_polygons[<?php echo $mapId; ?>][<?php echo $marker->ID; ?>] = new google.maps.Polygon({
                  paths: polyPaths,
                  strokeWeight: 3,
                  map: window.ve_gmaps[<?php echo $mapId; ?>],
                  fillColor: '<?php echo $marker->marker_fill_color; ?>',
                  strokeColor: '<?php echo $marker->marker_line_color; ?>',
                  strokeOpacity: '<?php echo $marker->marker_line_opacity; ?>',
                  fillOpacity: '<?php echo $marker->marker_fill_opacity; ?>'
              });

              google.maps.event.addListener(window.ve_gmap_polygons[<?php echo $mapId; ?>][<?php echo $marker->ID; ?>], 'mouseover', function() {
              	window.ve_gmap_polygons[<?php echo $mapId; ?>][<?php echo $marker->ID; ?>].setOptions({
                      strokeOpacity: '<?php echo $marker->marker_line_opacity_hover; ?>',
                      fillOpacity: '<?php echo $marker->marker_fill_opacity_hover; ?>',
                  });
              });
              google.maps.event.addListener(window.ve_gmap_polygons[<?php echo $mapId; ?>][<?php echo $marker->ID; ?>], 'mouseout', function() {
              	window.ve_gmap_polygons[<?php echo $mapId; ?>][<?php echo $marker->ID; ?>].setOptions({
                      strokeOpacity: '<?php echo $marker->marker_line_opacity; ?>',
                      fillOpacity: '<?php echo $marker->marker_fill_opacity; ?>'
                  });
              });
              
          <?php endforeach; ?>

          window.ve_gmap_polylines[<?php echo $mapId; ?>] = {};
          <?php foreach ($polylines as $marker): ?>
              <?php if (empty($marker->polystring)) { continue; } ?>

              <?php $polyString = json_decode($marker->polystring); ?>

              var polyPaths = [];
              <?php foreach ($polyString as $poly): ?>
                  polyPaths.push(new google.maps.LatLng(<?php echo $poly[0]; ?>, <?php echo $poly[1]; ?>));
              <?php endforeach; ?>
              var bounds = new google.maps.LatLngBounds();
              for (i = 0; i < polyPaths.length; i++) {
            	  bounds.extend(polyPaths[i]);
            	}

              window.ve_gmap_polylines[<?php echo $mapId; ?>][<?php echo $marker->ID; ?>] = new google.maps.Polyline({
                  strokeColor: '<?php echo $marker->marker_line_color; ?>',
                  strokeOpacity: <?php echo $marker->marker_line_opacity; ?>,
                  strokeWeight: <?php echo $marker->marker_line_thick; ?>
              });

              for (i = 0; i < polyPaths.length; i++) {
              	window.ve_gmap_polylines[<?php echo $mapId; ?>][<?php echo $marker->ID; ?>].getPath().push(polyPaths[i]);
              }
              window.ve_gmap_polylines[<?php echo $mapId; ?>][<?php echo $marker->ID; ?>].setMap(window.ve_gmaps[<?php echo $mapId; ?>]);

              google.maps.event.addListener(window.ve_gmap_polylines[<?php echo $mapId; ?>][<?php echo $marker->ID; ?>], 'mouseover', function() {
              	window.ve_gmap_polylines[<?php echo $mapId; ?>][<?php echo $marker->ID; ?>].setOptions({
                      strokeOpacity: '<?php echo $marker->marker_line_opacity_hover; ?>'
                  });
              });
              google.maps.event.addListener(window.ve_gmap_polylines[<?php echo $mapId; ?>][<?php echo $marker->ID; ?>], 'mouseout', function() {
              	window.ve_gmap_polylines[<?php echo $mapId; ?>][<?php echo $marker->ID; ?>].setOptions({
                      strokeOpacity: '<?php echo $marker->marker_line_opacity; ?>'
                  });
              });
              
          <?php endforeach; ?>
        }
        google.maps.event.addDomListener(window, 'load', gmap_initialize<?php echo $mapId; ?>);
        
        </script>
        <?php
        
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }
    
}
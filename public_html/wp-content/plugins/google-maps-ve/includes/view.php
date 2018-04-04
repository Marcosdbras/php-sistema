<?php

class Google_Maps_Ve_View
{
    
    /**
     * @var Google_Maps_Ve
     */
    protected $_google_maps;
    
    public function __construct($google_maps)
    {
        $this->_google_maps = $google_maps;
    }
    
    public function render_meta_box_map_container($post)
    {
        $map = $this->_google_maps->getApp()->getMap($post->ID);
        $mapZoom = $map->map_zoom;
        $mapLat = $map->map_lat;
        $mapLong = $map->map_long;
        $mapType = $map->map_type;
        $mapClientBasedLocation = $map->client_based_location;
        ?>
        <div id="ve-gmap-container" style="width: 100%; height: 400px; margin-bottom: 10px;"></div>
        <span id="ve-map-update-coords" class="button button-primary"><?php _e('Fill map coordinates by current position', GOOGLE_MAPS_VE_TD); ?></span>
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places"></script>
        <script>
        var map;
        var marker;
        var bikeLayer = new google.maps.BicyclingLayer();
        var transitLayer = new google.maps.TransitLayer();
        var trafficLayer = new google.maps.TrafficLayer();
        
        function initialize() {
          var mapOptions = {
            zoom: <?php echo (int) $mapZoom; ?>,
            center: new google.maps.LatLng(<?php echo $mapLat; ?>, <?php echo $mapLong; ?>),
            mapTypeId: google.maps.MapTypeId.<?php echo $mapType; ?>,
            		panControl: <?php echo Google_Maps_Ve_Defs::getJavascriptBoolean($map->map_control_pan); ?>,
	                zoomControl: <?php echo Google_Maps_Ve_Defs::getJavascriptBoolean($map->map_control_zoom); ?>,
	                mapTypeControl: <?php echo Google_Maps_Ve_Defs::getJavascriptBoolean($map->map_control_map_type); ?>,
	                scaleControl: <?php echo Google_Maps_Ve_Defs::getJavascriptBoolean($map->map_control_scale); ?>,
	                streetViewControl: <?php echo Google_Maps_Ve_Defs::getJavascriptBoolean($map->map_control_street_view); ?>,
	                overviewMapControl: <?php echo Google_Maps_Ve_Defs::getJavascriptBoolean($map->map_control_overview); ?>
          };
          map = new google.maps.Map(document.getElementById('ve-gmap-container'),
              mapOptions);
          window.ve_gmaps[<?php echo $post->ID; ?>] = map;
          google.maps.event.addListener(map, 'rightclick', function(event) {
              vePlaceMarker(event.latLng.lat(), event.latLng.lng());
          });

          
          <?php if ($map->map_layer_bicycling == Google_Maps_Ve_Defs::MAP_OPTION_YES): ?>
          bikeLayer.setMap(map);
          <?php endif; ?>
          <?php if ($map->map_layer_transit == Google_Maps_Ve_Defs::MAP_OPTION_YES): ?>
          transitLayer.setMap(map);
          <?php endif; ?>
          <?php if ($map->map_layer_traffic == Google_Maps_Ve_Defs::MAP_OPTION_YES): ?>
          trafficLayer.setMap(map);
          <?php endif; ?>
          ve_load_markers(map);
          ve_load_polygons(map);
          ve_load_polylines(map);

          autocomplete = new google.maps.places.Autocomplete(
              /** @type {HTMLInputElement} */(document.getElementById('ve-marker-address')),
              { types: ['geocode'] });
        }
        google.maps.event.addDomListener(window, 'load', initialize);
        
        function vePlaceMarker(lat, lng) {
            if (marker) {
                marker.setMap(null);
                marker = null;
            }
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(lat, lng), 
                map: map,
                draggable:true,
            });
            jQuery('#ve-marker-lat').val(lat);
            jQuery('#ve-marker-long').val(lng);
            
            google.maps.event.addListener(marker,'dragend',function(event) {
                jQuery('#ve-marker-lat').val(event.latLng.lat());
                jQuery('#ve-marker-long').val(event.latLng.lng());
            });
        }
        </script>
        <script type="text/javascript">
        jQuery(window).load(function() {
            jQuery('#ve-map-type').change(function() {
                map.setMapTypeId(google.maps.MapTypeId[jQuery(this).val()]);
            });
            jQuery('#ve-map-update-coords').click(function() {
                var c = map.getCenter();
                jQuery('#ve-map-long').val(c.lng());
                jQuery('#ve-map-lat').val(c.lat());
            });
        });
        <?php if ($mapClientBasedLocation == Google_Maps_Ve_Defs::MAP_OPTION_YES): ?>
            jQuery(window).load(function() {
                //ve_map_update_positions_by_visitor(<?php echo $post->ID; ?>);
            });
        <?php endif; ?>
        </script>
        <?php
    }
    
    public function render_meta_box_map_add_marker($post)
    {
        ?>
        
        <p class="info"><?php _e('Right click on map to add new marker.', GOOGLE_MAPS_VE_TD); ?></p>
        <p class="info"><?php _e('You can set infowindow image after saving marker. Markers are editable by clicking "Edit" link from markers table.', GOOGLE_MAPS_VE_TD); ?></p>
        <p class="info" style="color: red;"><?php _e('You can not save marker without latitude and longitude!', GOOGLE_MAPS_VE_TD); ?></p>
        
        <div class="categorydiv" id="ve-gmap-marker-tabs" style="margin-top: 20px; margin-bottom: 20px;">
            <ul id="marker_settings-tabs" class="category-tabs ve-tabs">
                <li class="tabs"><a href="#marker_general-tab"><?php _e('General settings', GOOGLE_MAPS_VE_TD); ?></a></li>
                <li><a href="#marker_infowindow-tab"><?php _e('Infowindow settings', GOOGLE_MAPS_VE_TD); ?></a></li>
                <li><a href="#marker_icon-tab"><?php _e('Marker icon', GOOGLE_MAPS_VE_TD); ?></a></li>
                <li><a href="#marker_parameters-tab"><?php _e('Marker parameters', GOOGLE_MAPS_VE_TD); ?></a></li>
            </ul>
            <div id="marker_general-tab" class="tabs-panel">
                <table class="add-marker-table ve-gmap-table">
                    <tr>
                        <th><label for="ve-marker-title"><?php _e('Title', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td><input tabindex="1" type="text" id="ve-marker-title" name="marker_title" value="" /></td>
                        <td width="90px"></td>
                        <th><label for="ve-marker-address"><?php _e('Address', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td><input tabindex="4" type="text" id="ve-marker-address" name="marker_address" value="" /> <span class="button button-primary" id="get-coordinates"><?php _e('Get coordinates from address', GOOGLE_MAPS_VE_TD); ?></span></td>
                    </tr>
                    <tr>
                        <th rowspan="2"><label for="ve-marker-description"><?php _e('Description', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td rowspan="2"><textarea tabindex="2" name="marker_description" id="ve-marker-description"></textarea></td>
                        <td></td>
                        <th><label for="ve-marker-lat"><?php _e('Latitude', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td><input tabindex="5" type="text" id="ve-marker-lat" name="marker_lat" value="" /> <span class="button button-primary" id="show-marker-on-map"><?php _e('Show marker on map', GOOGLE_MAPS_VE_TD); ?></span></td>
                    </tr>
                    <tr>
                        <td></td>
                        <th><label for="ve-marker-long"><?php _e('Longitude', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td><input tabindex="6" type="text" name="marker_long" id="ve-marker-long" value="" /></td>
                    </tr>
                    <tr>
                        <th><?php _e('Animation', GOOGLE_MAPS_VE_TD); ?></th>
                        <td>
                            <select name="marker_animation">
                                <?php foreach (Google_Maps_Ve_Defs::getAnimationTypes() as $mapK => $mapV): ?>
                                <option value="<?php echo $mapK; ?>"><?php echo esc_html($mapV); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="marker_infowindow-tab" class="tabs-panel" style="display: none;">
                <div class="ve-gmap-pro-feature-info"><a href="http://www.googlemapswordpress.com/buy-pro-version/" title="" target="_blank"><?php _e('Get PRO version for this feature', GOOGLE_MAPS_VE_TD); ?></a></div>
                <table class="add-marker-table ve-gmap-table">
                    <tr>
                        <th><?php _e('InfoWindow', GOOGLE_MAPS_VE_TD); ?></th>
                        <td>
                            <select>
                                <?php foreach (Google_Maps_Ve_Defs::getInfoWindowTypes() as $mapK => $mapV): ?>
                                <option value="<?php echo $mapK; ?>"><?php echo esc_html($mapV); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label><?php _e('Window width', GOOGLE_MAPS_VE_TD); ?></label>
                            <div style="font-size: 9px; font-weight: normal;"><?php _e('Leave empty for default', GOOGLE_MAPS_VE_TD); ?></div>
                        </th>
                        <td><input type="text" disabled style="width: 100px;" /> px</td>
                        <td width="90px"></td>
                        <th>
                            <label><?php _e('Window CSS class', GOOGLE_MAPS_VE_TD); ?></label>
                        </th>
                        <td><input type="text" disabled style="width: 100px;" /></td>
                    </tr>
                    <tr>
                        <th>
                            <label><?php _e('Link text', GOOGLE_MAPS_VE_TD); ?></label>
                            <div style="font-size: 9px; font-weight: normal;"><?php _e('Example "See more"', GOOGLE_MAPS_VE_TD); ?></div>
                        </th>
                        <td><input type="text" disabled style="width: 150px;" /></td>
                        <td width="90px"></td>
                        <th>
                            <label><?php _e('Link URL', GOOGLE_MAPS_VE_TD); ?></label>
                        </th>
                        <td><input type="text" disabled style="width: 100%; min-width: 300px;" /></td>
                    </tr>
                    <tr>
                        <th>
                            <label><?php _e('Image width', GOOGLE_MAPS_VE_TD); ?></label>
                            <div style="font-size: 9px; font-weight: normal;"><?php _e('Leave empty for default', GOOGLE_MAPS_VE_TD); ?></div>
                        </th>
                        <td><input type="text" disabled style="width: 100px;" /> px</td>
                        <td width="90px"></td>
                        <th>
                            <label><?php _e('Image height', GOOGLE_MAPS_VE_TD); ?></label>
                            <div style="font-size: 9px; font-weight: normal;"><?php _e('Leave empty for default', GOOGLE_MAPS_VE_TD); ?></div>
                        </th>
                        <td><input type="text" disabled style="width: 100px;" /> px</td>
                    </tr>
                </table>
            </div>
            <div id="marker_icon-tab" class="tabs-panel" style="display: none;">
                <div class="ve-gmap-pro-feature-info"><a href="http://www.googlemapswordpress.com/buy-pro-version/" title="" target="_blank"><?php _e('Get PRO version for this feature', GOOGLE_MAPS_VE_TD); ?></a></div>
                <table class="ve-gmap-table" style="width: 100%;">
                    <tr>
                        <th width="120px"><label><?php _e('Marker icon url', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td><input type="text" style="width: 100%;" disabled value="" /></td>
                        <td width="150px"><input type="button" target-element="ve-marker-icon" class="button upload-media-library-button" value="<?php _e('Choose or Upload an Image', GOOGLE_MAPS_VE_TD); ?>" /></td>
                    </tr>
                </table>
            </div>
            <div id="marker_parameters-tab" class="tabs-panel" style="display: none;">
                <div class="ve-gmap-pro-feature-info"><a href="http://www.googlemapswordpress.com/buy-pro-version/" title="" target="_blank"><?php _e('Get PRO version for this feature', GOOGLE_MAPS_VE_TD); ?></a></div>
                <ul class="marker-parameters-tab-ul">
                <?php 
                wp_terms_checklist($post->ID, array(
                    'taxonomy' => Google_Maps_Ve_App::TAXONOMY_MARKER,
                    'checked_ontop' => false,
                    'selected_cats' => array(),
                ));
                ?>
                </ul>
            </div>
        </div>
        <input type="submit" class="button button-primary button-large" value="<?php _e('Save and add marker to map', GOOGLE_MAPS_VE_TD); ?>" />
        <script type="text/javascript">
        jQuery(window).load(function() {
            jQuery('#marker_parameters-tab input').attr('disabled', 'disabled');
            jQuery('#ve-gmap-marker-tabs').tabs({
                activate: function( event, ui ) {
                    jQuery('#marker_settings-tabs li').removeClass('tabs');
                    ui.newTab.addClass('tabs');
                },
                tabsactivate: function (event, ui) {
                    jQuery('#marker_settings-tabs li').removeClass('tabs');
                    ui.newTab.addClass('tabs');
                }
            });
            jQuery('#show-marker-on-map').click(function() {
                if (!jQuery('#ve-marker-lat').val() || !jQuery('#ve-marker-long').val()) {
                    alert('<?php _e('Coordinates missing!', GOOGLE_MAPS_VE_TD); ?>');
                    return;
                }
                
                vePlaceMarker(jQuery('#ve-marker-lat').val(), jQuery('#ve-marker-long').val());
                map.setCenter(new google.maps.LatLng(jQuery('#ve-marker-lat').val(), jQuery('#ve-marker-long').val()));
            });
            var geocoder = new google.maps.Geocoder();
            jQuery('#get-coordinates').click(function() {
                var address = jQuery('#ve-marker-address').val();
                if (!address) {
                    alert('<?php _e('Address is empty', GOOGLE_MAPS_VE_TD); ?>');
                    return;
                }

                geocoder.geocode({
                    address: address
                }, function(locResult) {
                    jQuery('#ve-marker-lat').val(locResult[0].geometry.location.lat());
                    jQuery('#ve-marker-long').val(locResult[0].geometry.location.lng());
                });
            });
        });
        </script>
        <?php
    }

    public function render_meta_box_map_general($post)
    {
        wp_nonce_field('ve_map_post', 've_map_post_nonce');
        $map = $this->_google_maps->getApp()->getMap($post->ID);
        
        $mapType = $map->map_type;
        $mapWidth = $map->map_width;
        $mapWidthType = $map->map_width_type;
        $mapHeight = $map->map_height;
        $mapHeightType = $map->map_height_type;
        $mapZoom = $map->map_zoom;
        $mapClientBasedLocation = $map->client_based_location;
        $mapLat = $map->map_lat;
        $mapLong = $map->map_long;
        $mapShowGetVisitorPosition = $map->map_show_get_visitor_position;
        ?>
        <div>
            <table>
                <tr>
                    <td><?php _e('Map shortcode', GOOGLE_MAPS_VE_TD); ?></td>
                    <td><input type="text" readonly="readonly" value='[ve_gmap map="<?php echo $post->ID; ?>"]' /></td>
                </tr>
            </table>
        </div>
        <div class="categorydiv" id="ve-gmap-map-tabs" style="margin-top: 20px; margin-bottom: 20px;">
            <ul id="map_settings-tabs" class="category-tabs ve-tabs">
                <li class="tabs"><a href="#map_general-tab"><?php _e('General settings', GOOGLE_MAPS_VE_TD); ?></a></li>
                <li><a href="#map_layers-tab"><?php _e('Layers', GOOGLE_MAPS_VE_TD); ?></a></li>
                <li><a href="#map_controls-tab"><?php _e('Controls', GOOGLE_MAPS_VE_TD); ?></a></li>
            </ul>
            <div id="map_general-tab" class="tabs-panel">
                <table class="ve-gmap-table">
                    <tr>
                        <th style="padding: 5px;"><label for="ve-map-type"><?php _e('Map type', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td style="padding: 5px;">
                            <select name="ve_map_type" id="ve-map-type">
                                <?php foreach (Google_Maps_Ve_Defs::getMapTypes() as $mapK => $mapV): ?>
                                <option value="<?php echo $mapK; ?>"<?php if ($mapType == $mapK): ?> selected<?php endif; ?>><?php echo esc_html($mapV); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td width="90px"></td>
                        <th><label for="ve-map-lat"><?php _e('Map center latitude', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td><input type="text" id="ve-map-lat" name="ve_map_lat" value="<?php echo esc_html($mapLat); ?>" /></td>
                    </tr>
                    <tr>
                        <th style="padding: 5px;"><label for="ve-map-width"><?php _e('Map width', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td style="padding: 5px;">
                            <input type="text" name="ve_map_width" value="<?php echo esc_html($mapWidth); ?>" id="ve-map-width" style="width: 100px;" />
                            <select name="ve_map_width_type">
                                <?php foreach (Google_Maps_Ve_Defs::getSizeTypes() as $sizeK => $sizeV): ?>
                                <option value="<?php echo $sizeK; ?>"<?php if ($mapWidthType == $sizeK): ?> selected<?php endif; ?>><?php echo esc_html($sizeV); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td width="90px"></td>
                        <th><label for="ve-map-long"><?php _e('Map center longitude', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td><input type="text" id="ve-map-long" name="ve_map_long" value="<?php echo esc_html($mapLong); ?>" /></td>
                    </tr>
                    <tr>
                        <th style="padding: 5px;"><label for="ve-map-height"><?php _e('Map height', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td style="padding: 5px;">
                            <input type="text" name="ve_map_height" value="<?php echo esc_html($mapHeight); ?>" id="ve-map-height" style="width: 100px;" />
                            <select name="ve_map_height_type">
                                <?php foreach (Google_Maps_Ve_Defs::getSizeTypes() as $sizeK => $sizeV): ?>
                                <option value="<?php echo $sizeK; ?>"<?php if ($mapHeightType == $sizeK): ?> selected<?php endif; ?>><?php echo esc_html($sizeV); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td></td>
                        <th style="padding: 5px;"><label for="ve-map-client-based-location"><?php _e('Center map by visitor location', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td style="padding: 5px;">
                            <select name="ve_map_client_based_location">
                                <?php foreach (Google_Maps_Ve_Defs::getYesNoTypes() as $sizeK => $sizeV): ?>
                                <option value="<?php echo $sizeK; ?>"<?php if ($mapClientBasedLocation == $sizeK): ?> selected<?php endif; ?>><?php echo esc_html($sizeV); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th style="padding: 5px;"><?php _e('Map zoom', GOOGLE_MAPS_VE_TD); ?></th>
                        <td style="padding: 5px;">
                            <div id="ve-map-zoom-slider"></div>
                            <input type="hidden" id="ve_map_zoom" name="ve_map_zoom" value="<?php echo esc_html($mapZoom); ?>" readonly />
                        </td>
                        <td></td>
                    </tr>
                </table>
            </div>
            <div id="map_layers-tab" class="tabs-panel" style="display: none;">
                <table class="ve-gmap-table">
                    <tr>
                        <th style="padding: 5px;"><label for="ve-map-layer-traffic"><?php _e('Traffic layer', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td style="padding: 5px;">
                            <select name="ve_map_layer_traffic" id="ve-map-layer-traffic">
                                <?php foreach (Google_Maps_Ve_Defs::getYesNoTypes() as $mapK => $mapV): ?>
                                <option value="<?php echo $mapK; ?>"<?php if ($map->map_layer_traffic == $mapK): ?> selected<?php endif; ?>><?php echo esc_html($mapV); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td width="90px"></td>
                    </tr>
                    <tr>
                        <th style="padding: 5px;"><label for="ve-map-layer-transit"><?php _e('Transit layer', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td style="padding: 5px;">
                            <select name="ve_map_layer_transit" id="ve-map-layer-transit">
                                <?php foreach (Google_Maps_Ve_Defs::getYesNoTypes() as $mapK => $mapV): ?>
                                <option value="<?php echo $mapK; ?>"<?php if ($map->map_layer_transit == $mapK): ?> selected<?php endif; ?>><?php echo esc_html($mapV); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td width="90px"></td>
                    </tr>
                    <tr>
                        <th style="padding: 5px;"><label for="ve-map-layer-bicycling"><?php _e('Bicycling layer', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td style="padding: 5px;">
                            <select name="ve_map_layer_bicycling" id="ve-map-layer-bicycling">
                                <?php foreach (Google_Maps_Ve_Defs::getYesNoTypes() as $mapK => $mapV): ?>
                                <option value="<?php echo $mapK; ?>"<?php if ($map->map_layer_bicycling == $mapK): ?> selected<?php endif; ?>><?php echo esc_html($mapV); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td width="90px"></td>
                    </tr>
                </table>
            </div>
            <div id="map_controls-tab" class="tabs-panel" style="display: none;">
                <table class="ve-gmap-table">
                    <tr>
                        <th style="padding: 5px;"><label for="ve-map-control-pan"><?php _e('Pan', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td style="padding: 5px;">
                            <select name="ve_map_control_pan" id="ve-map-control-pan">
                                <?php foreach (Google_Maps_Ve_Defs::getYesNoTypes() as $mapK => $mapV): ?>
                                <option value="<?php echo $mapK; ?>"<?php if ($map->map_control_pan == $mapK): ?> selected<?php endif; ?>><?php echo esc_html($mapV); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td width="90px"></td>
                        <th style="padding: 5px;"><label for="ve-map-control-zoom"><?php _e('Zoom', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td style="padding: 5px;">
                            <select name="ve_map_control_zoom" id="ve-map-control-zoom">
                                <?php foreach (Google_Maps_Ve_Defs::getYesNoTypes() as $mapK => $mapV): ?>
                                <option value="<?php echo $mapK; ?>"<?php if ($map->map_control_zoom == $mapK): ?> selected<?php endif; ?>><?php echo esc_html($mapV); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th style="padding: 5px;"><label for="ve-map-control-map-type"><?php _e('Map type', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td style="padding: 5px;">
                            <select name="ve_map_control_map_type" id="ve-map-control-map-type">
                                <?php foreach (Google_Maps_Ve_Defs::getYesNoTypes() as $mapK => $mapV): ?>
                                <option value="<?php echo $mapK; ?>"<?php if ($map->map_control_map_type == $mapK): ?> selected<?php endif; ?>><?php echo esc_html($mapV); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td width="90px"></td>
                        <th style="padding: 5px;"><label for="ve-map-control-scale"><?php _e('Scale', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td style="padding: 5px;">
                            <select name="ve_map_control_scale" id="ve-map-control-scale">
                                <?php foreach (Google_Maps_Ve_Defs::getYesNoTypes() as $mapK => $mapV): ?>
                                <option value="<?php echo $mapK; ?>"<?php if ($map->map_control_scale == $mapK): ?> selected<?php endif; ?>><?php echo esc_html($mapV); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th style="padding: 5px;"><label for="ve-map-control-street-view"><?php _e('Street view', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td style="padding: 5px;">
                            <select name="ve_map_control_street_view" id="ve-map-control-street-view">
                                <?php foreach (Google_Maps_Ve_Defs::getYesNoTypes() as $mapK => $mapV): ?>
                                <option value="<?php echo $mapK; ?>"<?php if ($map->map_control_street_view == $mapK): ?> selected<?php endif; ?>><?php echo esc_html($mapV); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td width="90px"></td>
                        <th style="padding: 5px;"><label for="ve-map-control-overview"><?php _e('Overview', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td style="padding: 5px;">
                            <select name="ve_map_control_overview" id="ve-map-control-overview">
                                <?php foreach (Google_Maps_Ve_Defs::getYesNoTypes() as $mapK => $mapV): ?>
                                <option value="<?php echo $mapK; ?>"<?php if ($map->map_control_overview == $mapK): ?> selected<?php endif; ?>><?php echo esc_html($mapV); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <script>
            jQuery(function() {
            	jQuery('#ve-gmap-map-tabs').tabs({
                    activate: function( event, ui ) {
                        jQuery('#map_settings-tabs li').removeClass('tabs');
                        ui.newTab.addClass('tabs');
                    },
                    tabsactivate: function (event, ui) {
                        jQuery('#map_settings-tabs li').removeClass('tabs');
                        ui.newTab.addClass('tabs');
                    }
                });
                jQuery('#ve-map-layer-traffic').change(function() {
                    if (jQuery(this).val() == '<?php echo Google_Maps_Ve_Defs::MAP_OPTION_YES; ?>') {
                        trafficLayer.setMap(map);
                    }
                    else {
                    	trafficLayer.setMap(null);
                    }
                });
                jQuery('#ve-map-layer-transit').change(function() {
                    if (jQuery(this).val() == '<?php echo Google_Maps_Ve_Defs::MAP_OPTION_YES; ?>') {
                    	transitLayer.setMap(map);
                    }
                    else {
                    	transitLayer.setMap(null);
                    }
                });
                jQuery('#ve-map-layer-bicycling').change(function() {
                    if (jQuery(this).val() == '<?php echo Google_Maps_Ve_Defs::MAP_OPTION_YES; ?>') {
                    	bikeLayer.setMap(map);
                    }
                    else {
                    	bikeLayer.setMap(null);
                    }
                });
            	jQuery( "#ve-map-zoom-slider" ).slider({
                    range: "max",
                    min: 0,
                    max: 19,
                    value: <?php echo (int) $mapZoom; ?>,
                    slide: function( event, ui ) {
                        jQuery( "#ve_map_zoom" ).val( ui.value );
                        map.setZoom(ui.value);
                    }
                });
                jQuery( "#ve_map_zoom" ).val( jQuery( "#ve-map-zoom-slider" ).slider( "value" ) );
            });
        </script>
        <style>
        .ui-slider { position: relative; border: 1px solid #ddd; text-align: left; background: #eee; }
        .ui-slider .ui-slider-handle { position: absolute; z-index: 2; width: 1.2em; height: 1.2em; cursor: default; }
        .ui-slider .ui-slider-range { position: absolute; z-index: 1; font-size: .7em; display: block; border: 0; background: #ccc; background-position: 0 0; }
        
        .ui-slider-horizontal { height: .8em; }
        .ui-slider-horizontal .ui-slider-handle { top: -.3em; margin-left: -.6em; background: #fff; border: 1px solid #ccc; }
        .ui-slider-horizontal .ui-slider-range { top: 0; height: 100%; }
        .ui-slider-horizontal .ui-slider-range-min { left: 0; }
        .ui-slider-horizontal .ui-slider-range-max { right: 0; }
        
        .ui-slider-vertical { width: .8em; height: 100px; }
        .ui-slider-vertical .ui-slider-handle { left: -.3em; margin-left: 0; margin-bottom: -.6em; }
        .ui-slider-vertical .ui-slider-range { left: 0; width: 100%; }
        .ui-slider-vertical .ui-slider-range-min { bottom: 0; }
        .ui-slider-vertical .ui-slider-range-max { top: 0; }
        </style>
        <?php
    }
    
    public function render_meta_box_map_markers_list($post)
    {
        $markers = $this->_google_maps->getApp()->getMapMarkers($post->ID);
        ?>
        <table class="ve-gmap-markers-table ve-gmap-table">
            <thead>
                <tr>
                    <th><?php _e('Icon', GOOGLE_MAPS_VE_TD); ?></th>
                    <th><?php _e('Info', GOOGLE_MAPS_VE_TD); ?></th>
                    <th><?php _e('Address', GOOGLE_MAPS_VE_TD); ?></th>
                    <th colspan="3"></th>
                </tr>
            </thead>
            <tbody>
            <?php 
            foreach ($markers as $marker):
            ?>
            <tr>
                <td><img src="<?php echo esc_html($marker->icon); ?>" alt="" /></td>
                <td>
                    <strong><?php echo esc_html($marker->post_title); ?></strong><br />
                    <?php echo apply_filters( 'the_content', $marker->post_content ); ?>
                </td>
                <td><?php echo esc_html($marker->address); ?></td>
                <td><a href="javascript:ve_center_map(<?php echo $marker->post_parent; ?>, <?php echo $marker->marker_lat; ?>, <?php echo $marker->marker_long; ?>, 've-gmap-container');"><?php _e('See on map', GOOGLE_MAPS_VE_TD); ?></a></td>
                <td><a href="<?php echo get_edit_post_link($marker->ID); ?>"><?php _e('Edit', GOOGLE_MAPS_VE_TD); ?></a></td>
                <td class="submitbox"><a href="<?php echo get_delete_post_link($marker->ID); ?>&redirect_to=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" class="submitdelete deletion" onclick="return confirm('<?php _e('Are you sure?', GOOGLE_MAPS_VE_TD); ?>'); "><?php _e('Delete', GOOGLE_MAPS_VE_TD); ?></a></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <script type="text/javascript">
        function ve_load_markers(map) {
            window.ve_gmap_markers[<?php echo $post->ID; ?>] = {};
            
            <?php foreach ($markers as $marker): ?>
                <?php if (empty($marker->marker_lat) || empty($marker->marker_long)) { continue; } ?>
                window.ve_gmap_markers[<?php echo $post->ID; ?>][<?php echo $marker->ID; ?>] = new google.maps.Marker({
                    position: new google.maps.LatLng(<?php echo esc_html($marker->marker_lat); ?>, <?php echo esc_html($marker->marker_long); ?>), 
                    map: map,
                    icon: '<?php echo $marker->icon; ?>'
                    //draggable:true
                });

                google.maps.event.addListener(window.ve_gmap_markers[<?php echo $post->ID; ?>][<?php echo $marker->ID; ?>],'dragend',function(event) {
                    //jQuery('#ve-marker-lat').val(event.latLng.lat());
                    //jQuery('#ve-marker-long').val(event.latLng.lng());
                });
                <?php if ($marker->animation !== Google_Maps_Ve_Defs::MAP_ANIMATION_NONE): ?>
                window.ve_gmap_markers[<?php echo $post->ID; ?>][<?php echo $marker->ID; ?>].setAnimation(google.maps.Animation.<?php echo $marker->animation; ?>);
                <?php endif; ?>
            <?php endforeach; ?>
        };
        </script>
        <?php
    }
    
    public function render_meta_box_map_polygons_list($post)
    {
        $markers = $this->_google_maps->getApp()->getMapPolygons($post->ID);
        ?>
        <a href="/wp-admin/post-new.php?post_type=<?php echo Google_Maps_Ve_App::POST_TYPE_POLYGON; ?>&map_id=<?php echo $post->ID; ?>" class="button button-primary"><?php _e('Add new polygon', GOOGLE_MAPS_VE_TD); ?></a>
        <table class="ve-gmap-markers-table ve-gmap-table" style="margin-top: 20px;">
            <thead>
                <tr>
                    <th><?php _e('Info', GOOGLE_MAPS_VE_TD); ?></th>
                    <th colspan="3"></th>
                </tr>
            </thead>
            <tbody>
            <?php 
            foreach ($markers as $marker):
            $polys = null;
            if (!empty($marker->polystring)) {
                $polys = json_decode($marker->polystring);
            }
            ?>
            <tr>
                <td>
                    <strong><?php echo esc_html($marker->post_title); ?></strong><br />
                    <?php echo apply_filters( 'the_content', $marker->post_content ); ?>
                </td>
                <td><?php if (!empty($polys)): ?><a href="javascript:ve_center_map(<?php echo $marker->post_parent; ?>, <?php echo $polys[0][0]; ?>, <?php echo $polys[0][1]; ?>, 've-gmap-container');"><?php _e('See on map', GOOGLE_MAPS_VE_TD); ?></a><?php endif; ?></td>
                <td><a href="<?php echo get_edit_post_link($marker->ID); ?>"><?php _e('Edit', GOOGLE_MAPS_VE_TD); ?></a></td>
                <td class="submitbox"><a href="<?php echo get_delete_post_link($marker->ID); ?>&redirect_to=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" class="submitdelete deletion" onclick="return confirm('<?php _e('Are you sure?', GOOGLE_MAPS_VE_TD); ?>'); "><?php _e('Delete', GOOGLE_MAPS_VE_TD); ?></a></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <script type="text/javascript">
        function ve_load_polygons(map) {
            window.ve_gmap_polygons[<?php echo $post->ID; ?>] = {};
            <?php foreach ($markers as $marker): ?>
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

                window.ve_gmap_polygons[<?php echo $post->ID; ?>][<?php echo $marker->ID; ?>] = new google.maps.Polygon({
                    paths: polyPaths,
                    strokeWeight: 3,
                    map: map,
                    fillColor: '<?php echo $marker->marker_fill_color; ?>',
                    strokeColor: '<?php echo $marker->marker_line_color; ?>',
                    strokeOpacity: '<?php echo $marker->marker_line_opacity; ?>',
                    fillOpacity: '<?php echo $marker->marker_fill_opacity; ?>'
                });

                google.maps.event.addListener(window.ve_gmap_polygons[<?php echo $post->ID; ?>][<?php echo $marker->ID; ?>], 'mouseover', function() {
                	window.ve_gmap_polygons[<?php echo $post->ID; ?>][<?php echo $marker->ID; ?>].setOptions({
                        strokeOpacity: '<?php echo $marker->marker_line_opacity_hover; ?>',
                        fillOpacity: '<?php echo $marker->marker_fill_opacity_hover; ?>',
                    });
                });
                google.maps.event.addListener(window.ve_gmap_polygons[<?php echo $post->ID; ?>][<?php echo $marker->ID; ?>], 'mouseout', function() {
                	window.ve_gmap_polygons[<?php echo $post->ID; ?>][<?php echo $marker->ID; ?>].setOptions({
                        strokeOpacity: '<?php echo $marker->marker_line_opacity; ?>',
                        fillOpacity: '<?php echo $marker->marker_fill_opacity; ?>'
                    });
                });
                
            <?php endforeach; ?>
        };
        </script>
        <?php
    }
    
    public function render_meta_box_marker_settings()
    {
        wp_nonce_field('ve_map_marker_post', 've_map_marker_post_nonce');
        global $post;
        $postId = $post->ID;
        $terms = wp_get_post_terms($postId, Google_Maps_Ve_App::TAXONOMY_MARKER, array("fields" => "ids"));

        $infoWindow = get_post_meta($postId, 'marker_infowindow', true);
        $animation = get_post_meta($postId, 'marker_animation', true);
        ?>
        
        <p class="info"><?php _e('You can set infowindow image from "Featured image" box on right sidebar', GOOGLE_MAPS_VE_TD); ?></p>
        
        <div class="categorydiv" id="ve-gmap-marker-tabs" style="margin-top: 20px; margin-bottom: 20px;">
            <ul id="marker_settings-tabs" class="category-tabs ve-tabs">
                <li class="tabs"><a href="#marker_general-tab"><?php _e('General settings', GOOGLE_MAPS_VE_TD); ?></a></li>
                <li><a href="#marker_infowindow-tab"><?php _e('Infowindow settings', GOOGLE_MAPS_VE_TD); ?></a></li>
                <li><a href="#marker_icon-tab"><?php _e('Marker icon', GOOGLE_MAPS_VE_TD); ?></a></li>
                <li><a href="#marker_parameters-tab"><?php _e('Marker parameters', GOOGLE_MAPS_VE_TD); ?></a></li>
            </ul>
            <div id="marker_general-tab" class="tabs-panel">
                <table class="add-marker-table ve-gmap-table">
                    <tr>
                        <th rowspan="2"><label for="ve-marker-description"><?php _e('Description', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td rowspan="2"><textarea name="marker_description" id="ve-marker-description"><?php echo $post->post_content; ?></textarea></td>
                        <td width="90px"></td>
                        <th><label for="ve-marker-address"><?php _e('Address', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td><input type="text" id="ve-marker-address" name="marker_address" value="<?php echo esc_html(get_post_meta($postId, 'marker_address', true)); ?>" /> <span class="button button-primary" id="get-coordinates"><?php _e('Get coordinates from address', GOOGLE_MAPS_VE_TD); ?></span></td>
                    </tr>
                    <tr>
                        <td width="90px"></td>
                        <th><label for="ve-marker-lat"><?php _e('Latitude', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td><input type="text" required="true" id="ve-marker-lat" name="marker_lat" value="<?php echo esc_html(get_post_meta($postId, 'marker_lat', true)); ?>" /> <span class="button button-primary" id="show-marker-on-map"><?php _e('Show marker on map', GOOGLE_MAPS_VE_TD); ?></span></td>
                    </tr>
                    <tr>
                        <th><?php _e('Animation', GOOGLE_MAPS_VE_TD); ?></th>
                        <td>
                            <select name="marker_animation">
                                <?php foreach (Google_Maps_Ve_Defs::getAnimationTypes() as $mapK => $mapV): ?>
                                <option value="<?php echo $mapK; ?>"<?php if ($animation == $mapK): ?> selected<?php endif; ?>><?php echo esc_html($mapV); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td></td>
                        <th><label for="ve-marker-long"><?php _e('Longitude', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td><input type="text" required="true" name="marker_long" id="ve-marker-long" value="<?php echo esc_html(get_post_meta($postId, 'marker_long', true)); ?>" /></td>
                    </tr>
                </table>
            </div>
            <div id="marker_infowindow-tab" class="tabs-panel" style="display: none;">
                <div class="ve-gmap-pro-feature-info"><a href="http://www.googlemapswordpress.com/buy-pro-version/" title="" target="_blank"><?php _e('Get PRO version for this feature', GOOGLE_MAPS_VE_TD); ?></a></div>
                <table class="add-marker-table ve-gmap-table">
                    <tr>
                        <th><?php _e('InfoWindow', GOOGLE_MAPS_VE_TD); ?></th>
                        <td>
                            <select>
                                <?php foreach (Google_Maps_Ve_Defs::getInfoWindowTypes() as $mapK => $mapV): ?>
                                <option value="<?php echo $mapK; ?>"><?php echo esc_html($mapV); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label><?php _e('Window width', GOOGLE_MAPS_VE_TD); ?></label>
                            <div style="font-size: 9px; font-weight: normal;"><?php _e('Leave empty for default', GOOGLE_MAPS_VE_TD); ?></div>
                        </th>
                        <td><input type="text" disabled style="width: 100px;" /> px</td>
                        <td width="90px"></td>
                        <th>
                            <label><?php _e('Window CSS class', GOOGLE_MAPS_VE_TD); ?></label>
                        </th>
                        <td><input type="text" disabled style="width: 100px;" /></td>
                    </tr>
                    <tr>
                        <th>
                            <label><?php _e('Link text', GOOGLE_MAPS_VE_TD); ?></label>
                            <div style="font-size: 9px; font-weight: normal;"><?php _e('Example "See more"', GOOGLE_MAPS_VE_TD); ?></div>
                        </th>
                        <td><input type="text" disabled style="width: 150px;" /></td>
                        <td width="90px"></td>
                        <th>
                            <label><?php _e('Link URL', GOOGLE_MAPS_VE_TD); ?></label>
                        </th>
                        <td><input type="text" disabled style="width: 100%; min-width: 300px;" /></td>
                    </tr>
                    <tr>
                        <th>
                            <label><?php _e('Image width', GOOGLE_MAPS_VE_TD); ?></label>
                            <div style="font-size: 9px; font-weight: normal;"><?php _e('Leave empty for default', GOOGLE_MAPS_VE_TD); ?></div>
                        </th>
                        <td><input type="text" disabled style="width: 100px;" /> px</td>
                        <td width="90px"></td>
                        <th>
                            <label><?php _e('Image height', GOOGLE_MAPS_VE_TD); ?></label>
                            <div style="font-size: 9px; font-weight: normal;"><?php _e('Leave empty for default', GOOGLE_MAPS_VE_TD); ?></div>
                        </th>
                        <td><input type="text" disabled style="width: 100px;" /> px</td>
                    </tr>
                </table>
            </div>
            <div id="marker_icon-tab" class="tabs-panel" style="display: none;">
                <div class="ve-gmap-pro-feature-info"><a href="http://www.googlemapswordpress.com/buy-pro-version/" title="" target="_blank"><?php _e('Get PRO version for this feature', GOOGLE_MAPS_VE_TD); ?></a></div>
                <table class="ve-gmap-table" style="width: 100%;">
                    <tr>
                        <th width="120px"><label><?php _e('Marker icon url', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td><input type="text" style="width: 100%;" disabled value="" /></td>
                        <td width="150px"><input type="button" target-element="ve-marker-icon" class="button upload-media-library-button" value="<?php _e('Choose or Upload an Image', GOOGLE_MAPS_VE_TD); ?>" /></td>
                    </tr>
                </table>
            </div>
            <div id="marker_parameters-tab" class="tabs-panel" style="display: none;">
                <div class="ve-gmap-pro-feature-info"><a href="http://www.googlemapswordpress.com/buy-pro-version/" title="" target="_blank"><?php _e('Get PRO version for this feature', GOOGLE_MAPS_VE_TD); ?></a></div>
                <ul class="marker-parameters-tab-ul">
                <?php 
                wp_terms_checklist($post->ID, array(
                    'taxonomy' => Google_Maps_Ve_App::TAXONOMY_MARKER,
                    'checked_ontop' => false,
                    'selected_cats' => array(),
                ));
                ?>
                </ul>
            </div>
        </div>
        <script type="text/javascript">
        jQuery(window).load(function() {
            jQuery('#marker_parameters-tab input').attr('disabled', 'disabled');
            jQuery('#ve-gmap-marker-tabs').tabs({
                activate: function( event, ui ) {
                    jQuery('#marker_settings-tabs li').removeClass('tabs');
                    ui.newTab.addClass('tabs');
                },
                tabsactivate: function (event, ui) {
                    jQuery('#marker_settings-tabs li').removeClass('tabs');
                    ui.newTab.addClass('tabs');
                }
            });
            jQuery('.wrap').find('h2:first').before('<a href="<?php echo get_edit_post_link($post->post_parent); ?>" class="button button-primary"><?php _e('Back to map', GOOGLE_MAPS_VE_TD); ?></a>');
            jQuery('#show-marker-on-map').click(function() {
                if (!jQuery('#ve-marker-lat').val() || !jQuery('#ve-marker-long').val()) {
                    alert('<?php _e('Coordinates missing!', GOOGLE_MAPS_VE_TD); ?>');
                    return;
                }
                map.setCenter(new google.maps.LatLng(jQuery('#ve-marker-lat').val(), jQuery('#ve-marker-long').val()));
            });
            var geocoder = new google.maps.Geocoder();
            jQuery('#get-coordinates').click(function() {
                var address = jQuery('#ve-marker-address').val();
                if (!address) {
                    alert('<?php _e('Address is empty', GOOGLE_MAPS_VE_TD); ?>');
                    return;
                }

                geocoder.geocode({
                    address: address
                }, function(locResult) {
                    jQuery('#ve-marker-lat').val(locResult[0].geometry.location.lat());
                    jQuery('#ve-marker-long').val(locResult[0].geometry.location.lng());
                });
            });
        });
        </script>
        <?php
    }
    
    public function render_meta_box_marker_map_container($post)
    {
        $markerId = $post->ID;
        $mapId = $post->post_parent;
        
        $map = $this->_google_maps->getApp()->getMap($mapId);
        $marker = $this->_google_maps->getApp()->getMarker($markerId);

        $mapLat = $marker->marker_lat;
        $mapLong = $marker->marker_long;
        $mapZoom = get_post_meta($mapId, 've_map_zoom', true);
        $mapType = get_post_meta($mapId, 've_map_type', true);
        
        if (empty($mapType)) {
            $mapType = Google_Maps_Ve_Defs::MAP_TYPE_ROADMAP;
        }
        if (!is_numeric($mapLat)) {
            $mapLat = -34.397;
        }
        if (!is_numeric($mapLong)) {
            $mapLong = 150.644;
        }
        ?>
        <div id="ve-gmap-container" style="width: 100%; height: 400px; margin-bottom: 10px;"></div>
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places"></script>
        <script>
        var map;
        var marker;
        function initialize() {
          var mapOptions = {
            zoom: <?php echo (int) $mapZoom; ?>,
            center: new google.maps.LatLng(<?php echo $mapLat; ?>, <?php echo $mapLong; ?>),
            mapTypeId: google.maps.MapTypeId.<?php echo $mapType; ?>,
    		panControl: <?php echo Google_Maps_Ve_Defs::getJavascriptBoolean($map->map_control_pan); ?>,
          zoomControl: <?php echo Google_Maps_Ve_Defs::getJavascriptBoolean($map->map_control_zoom); ?>,
          mapTypeControl: <?php echo Google_Maps_Ve_Defs::getJavascriptBoolean($map->map_control_map_type); ?>,
          scaleControl: <?php echo Google_Maps_Ve_Defs::getJavascriptBoolean($map->map_control_scale); ?>,
          streetViewControl: <?php echo Google_Maps_Ve_Defs::getJavascriptBoolean($map->map_control_street_view); ?>,
          overviewMapControl: <?php echo Google_Maps_Ve_Defs::getJavascriptBoolean($map->map_control_overview); ?>
          };
          map = new google.maps.Map(document.getElementById('ve-gmap-container'),
              mapOptions);
          window.ve_gmaps[<?php echo $mapId; ?>] = map;

          var bikeLayer = new google.maps.BicyclingLayer();
          var transitLayer = new google.maps.TransitLayer();
          var trafficLayer = new google.maps.TrafficLayer();
          <?php if ($map->map_layer_bicycling == Google_Maps_Ve_Defs::MAP_OPTION_YES): ?>
          bikeLayer.setMap(map);
          <?php endif; ?>
          <?php if ($map->map_layer_transit == Google_Maps_Ve_Defs::MAP_OPTION_YES): ?>
          transitLayer.setMap(map);
          <?php endif; ?>
          <?php if ($map->map_layer_traffic == Google_Maps_Ve_Defs::MAP_OPTION_YES): ?>
          trafficLayer.setMap(map);
          <?php endif; ?>

          <?php if (!empty($marker->marker_lat) && !empty($marker->marker_long)):?>
              marker = new google.maps.Marker({
                  position: new google.maps.LatLng(<?php echo esc_html($marker->marker_lat); ?>, <?php echo esc_html($marker->marker_long); ?>), 
                  map: map,
                  icon: '<?php echo $marker->icon; ?>',
                  draggable:true
              });

              <?php if ($marker->infowindow !== Google_Maps_Ve_Defs::MAP_INFOWINDOW_NONE): ?>
                  var contentString = '<div class="<?php echo esc_html($marker->marker_infowindow_class); ?>"<?php if ($marker->marker_infowindow_width): ?> style="width: <?php echo $marker->marker_infowindow_width; ?>px;"<?php endif; ?>>';
                  contentString += "<div class='ve-gmap-infowindow-title'><?php echo esc_html($marker->post_title); ?></div>";
                  <?php if (!empty($marker->post_content)): ?>
                      contentString += '<div class="ve-gmap-infowindow-description"></div>';
                      <?php if (!empty($marker->thumb)): ?>
                          contentString += '<table class="ve-gmap-infowindow-table"><tr><td class="description"><?php echo str_replace(array("\n", "\t", "\r"), '', nl2br(str_replace('\'', "\'", $marker->post_content))); ?></td><td class="image" style="width: <?php echo $marker->marker_infowindow_thumb_width; ?>px;"><?php echo $marker->thumb; ?></td></tr></table>';
                      <?php else: ?>
                          contentString += '<table class="ve-gmap-infowindow-table"><tr><td class="description"><?php echo str_replace(array("\n", "\t", "\r"), '', nl2br(str_replace('\'', "\'", $marker->post_content))); ?></td></tr></table>';
                      <?php endif; ?>
                  
                  <?php elseif (!empty($marker->thumb)): ?>
                      contentString += '<table class="ve-gmap-infowindow-table"><tr><td class="image alone"><?php echo $marker->thumb; ?></td></tr></table>';
                  <?php endif; ?>
                  <?php if (!empty($marker->marker_infowindow_link_url)): ?>
                      contentString += "<a href='<?php echo $marker->marker_infowindow_link_url; ?>' target='_blank'><?php echo $marker->marker_infowindow_link_text; ?></a>";
                  <?php endif; ?>
                  contentString += '</div>';
                  var infowindow = new google.maps.InfoWindow({
                      content: contentString, disableAutoPan: true
                  });
              
                  google.maps.event.addListener(marker, 'click', function() {
                      infowindow.open(map,marker);
                  });
                  <?php if ($marker->infowindow == Google_Maps_Ve_Defs::MAP_INFOWINDOW_OPEN): ?>
                  infowindow.open(map,marker);
                  <?php endif; ?>
              <?php endif; ?>
              
              google.maps.event.addListener(marker,'dragend',function(event) {
                  jQuery('#ve-marker-lat').val(event.latLng.lat());
                  jQuery('#ve-marker-long').val(event.latLng.lng());
              });
              <?php if ($marker->animation !== Google_Maps_Ve_Defs::MAP_ANIMATION_NONE): ?>
              marker.setAnimation(google.maps.Animation.<?php echo $marker->animation; ?>);
              <?php endif; ?>
          <?php endif; ?>
          autocomplete = new google.maps.places.Autocomplete(
                  /** @type {HTMLInputElement} */(document.getElementById('ve-marker-address')),
                  { types: ['geocode'] });
        }
        google.maps.event.addDomListener(window, 'load', initialize);
        </script>
        <?php
    }
    
    public function render_meta_box_add_polygon()
    {
        wp_nonce_field('ve_map_polygon_post', 've_map_polygon_post_nonce');
        
        global $post;
        $postId = $post->ID;
        $terms = wp_get_post_terms($postId, Google_Maps_Ve_App::TAXONOMY_MARKER, array("fields" => "ids"));

        $infoWindow = get_post_meta($postId, 'marker_infowindow', true);
        $polyString = get_post_meta($postId, 'marker_polystring', true);
        $lineOpacity = get_post_meta($postId, 'marker_line_opacity', true);
        
        $marker = $this->_google_maps->getApp()->getPolygon($postId);
        
        ?>
        
        <p class="info"><?php _e('You can set infowindow image from "Featured image" box on right sidebar', GOOGLE_MAPS_VE_TD); ?></p>
        <p class="info"><?php _e('Click on map to add vertex', GOOGLE_MAPS_VE_TD); ?>.</p>
        <p class="info"><?php _e('Right click on vertex to remove it.', GOOGLE_MAPS_VE_TD); ?></p>
        <p class="info"><?php _e('Drag polygon or vertex to move it.', GOOGLE_MAPS_VE_TD); ?></p>
        <input type="hidden" value="<?php echo $polyString; ?>" id="ve-gmap-polystring" name="marker_polystring" />
        <div class="categorydiv" id="ve-gmap-marker-tabs" style="margin-top: 20px; margin-bottom: 20px;">
            <ul id="marker_settings-tabs" class="category-tabs ve-tabs">
                <li class="tabs"><a href="#marker_general-tab"><?php _e('Polygon settings', GOOGLE_MAPS_VE_TD); ?></a></li>
                <li><a href="#marker_infowindow-tab"><?php _e('Infowindow settings', GOOGLE_MAPS_VE_TD); ?></a></li>
                <li><a href="#marker_parameters-tab"><?php _e('Polygon parameters', GOOGLE_MAPS_VE_TD); ?></a></li>
            </ul>
            <div id="marker_general-tab" class="tabs-panel" style="max-height: 600px;">
                <table class="add-marker-table ve-gmap-table">
                    <tr>
                        <th rowspan="2"><label for="ve-marker-description"><?php _e('Description', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td rowspan="2"><textarea name="marker_description" id="ve-marker-description"><?php echo $post->post_content; ?></textarea></td>
                        <td width="90px"></td>
                        <th><label for="ve-marker-line-color"><?php _e('Line color', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td><input type="text" id="ve-marker-line-color" class="ve-color-field" name="marker_line_color" value="<?php echo esc_html(get_post_meta($postId, 'marker_line_color', true)); ?>" /></td>
                    </tr>
                    <tr>
                        <td width="90px"></td>
                        <th><label for="ve-marker-fill-color"><?php _e('Fill color', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td><input type="text" id="ve-marker-fill-color" class="ve-color-field" name="marker_fill_color" value="<?php echo esc_html(get_post_meta($postId, 'marker_fill_color', true)); ?>" /></td>
                    </tr>
                    <tr>
                        <th><?php _e('Line opacity', GOOGLE_MAPS_VE_TD); ?></th>
                        <td>
                            <div id="ve-marker-line-opacity-slider"></div>
                            <input type="hidden" id="ve-marker-line-opacity" name="marker_line_opacity" value="<?php echo $lineOpacity; ?>" readonly />
                        </td>
                        <td></td>
                        <th><?php _e('Fill opacity', GOOGLE_MAPS_VE_TD); ?></th>
                        <td>
                            <div id="ve-marker-fill-opacity-slider"></div>
                            <input type="hidden" id="ve-marker-fill-opacity" name="marker_fill_opacity" value="<?php echo $fillOpacity; ?>" readonly />
                        </td>
                    </tr>
                    <tr>
                        <th><label for="ve-marker-line-opacity-hover"><?php _e('On hover line opacity', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td>
                            <div id="ve-marker-line-opacity-hover-slider"></div>
                            <input type="hidden" id="ve-marker-line-opacity-hover" name="marker_line_opacity_hover" value="<?php echo $lineHoverOpacity; ?>" readonly />
                        </td>
                        <td></td>
                        <th><label for="ve-marker-fill-opacity-hover"><?php _e('On hover fill opacity', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td>
                            <div id="ve-marker-fill-opacity-hover-slider"></div>
                            <input type="hidden" id="ve-marker-fill-opacity-hover" name="marker_fill_opacity_hover" value="<?php echo $fillHoverOpacity; ?>" readonly />
                        </td>
                    </tr>
                    <tr>
                        <th><label for="ve-marker-map"><?php _e('Map', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td>
                            <select name="ve_marker_map">
                            <?php
                            $maps = get_posts(array(
                                'post_type' => Google_Maps_Ve_App::POST_TYPE_MAPS,
                                'posts_per_page' => -1,
                            ));
                            foreach ($maps as $map):
                            ?>
                                <option value="<?php echo $map->ID; ?>"<?php if ($map->ID == $post->post_parent || $map->ID == $_GET['map_id']): ?> selected<?php endif; ?>><?php echo esc_html($map->post_title); ?></option>
                            <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="marker_infowindow-tab" class="tabs-panel" style="display: none;">
                <div class="ve-gmap-pro-feature-info"><a href="http://www.googlemapswordpress.com/buy-pro-version/" title="" target="_blank"><?php _e('Get PRO version for this feature', GOOGLE_MAPS_VE_TD); ?></a></div>
                <table class="add-marker-table ve-gmap-table">
                    <tr>
                        <th><?php _e('InfoWindow', GOOGLE_MAPS_VE_TD); ?></th>
                        <td>
                            <select>
                                <?php foreach (Google_Maps_Ve_Defs::getInfoWindowTypes() as $mapK => $mapV): ?>
                                <option value="<?php echo $mapK; ?>"<?php if ($infoWindow == $mapK): ?> selected<?php endif; ?>><?php echo esc_html($mapV); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label><?php _e('Window width', GOOGLE_MAPS_VE_TD); ?></label>
                            <div style="font-size: 9px; font-weight: normal;"><?php _e('Leave empty for default', GOOGLE_MAPS_VE_TD); ?></div>
                        </th>
                        <td><input type="text" disabled /> px</td>
                        <td width="90px"></td>
                        <th>
                            <label><?php _e('Window CSS class', GOOGLE_MAPS_VE_TD); ?></label>
                        </th>
                        <td><input type="text" disabled style="width: 100px;" /></td>
                    </tr>
                    <tr>
                        <th>
                            <label><?php _e('Link text', GOOGLE_MAPS_VE_TD); ?></label>
                            <div style="font-size: 9px; font-weight: normal;"><?php _e('Example "See more"', GOOGLE_MAPS_VE_TD); ?></div>
                        </th>
                        <td><input type="text" disabled style="width: 150px;" /></td>
                        <td width="90px"></td>
                        <th>
                            <label><?php _e('Link URL', GOOGLE_MAPS_VE_TD); ?></label>
                        </th>
                        <td><input type="text" disabled style="width: 100%; min-width: 300px;" /></td>
                    </tr>
                    <tr>
                        <th>
                            <label><?php _e('Image width', GOOGLE_MAPS_VE_TD); ?></label>
                            <div style="font-size: 9px; font-weight: normal;"><?php _e('Leave empty for default', GOOGLE_MAPS_VE_TD); ?></div>
                        </th>
                        <td><input type="text" disabled /> px</td>
                        <td width="90px"></td>
                        <th>
                            <label><?php _e('Image height', GOOGLE_MAPS_VE_TD); ?></label>
                            <div style="font-size: 9px; font-weight: normal;"><?php _e('Leave empty for default', GOOGLE_MAPS_VE_TD); ?></div>
                        </th>
                        <td><input type="text" disabled style="width: 100px;" /> px</td>
                    </tr>
                </table>
            </div>
            <div id="marker_parameters-tab" class="tabs-panel" style="display: none;">
                <div class="ve-gmap-pro-feature-info"><a href="http://www.googlemapswordpress.com/buy-pro-version/" title="" target="_blank"><?php _e('Get PRO version for this feature', GOOGLE_MAPS_VE_TD); ?></a></div>
                <ul class="marker-parameters-tab-ul">
                <?php 
                wp_terms_checklist($post->ID, array(
                    'taxonomy' => Google_Maps_Ve_App::TAXONOMY_MARKER,
                    'selected_cats' => $terms,
                    'checked_ontop' => false,
                ));
                ?>
                </ul>
            </div>
        </div>
        <script>
            var fill_color= '<?php echo $marker->marker_fill_color; ?>';
            var stroke_color= '<?php echo $marker->marker_line_color; ?>';
            var stroke_opacity= '<?php echo $marker->marker_line_opacity; ?>';
            var fill_opacity = '<?php echo $marker->marker_fill_opacity; ?>';
            var stroke_opacity_hover = '<?php echo $marker->marker_line_opacity_hover; ?>';
            var fill_opacity_hover = '<?php echo $marker->marker_fill_opacity_hover; ?>';
            
            jQuery(function() {
                jQuery('#marker_parameters-tab input').attr('disabled', 'disabled');
                jQuery( "#ve-marker-line-opacity-hover-slider" ).slider({
                    range: "max",
                    min: 0,
                    max: 1,
                    step: 0.1,
                    value: stroke_opacity_hover,
                    slide: function( event, ui ) {
                    	stroke_opacity_hover = ui.value;
                        jQuery( "#ve-marker-line-opacity-hover" ).val( ui.value );
                    }
                });
                jQuery( "#ve-marker-line-opacity-hover" ).val( jQuery( "#ve-marker-line-opacity-hover-slider" ).slider( "value" ) );

                jQuery( "#ve-marker-fill-opacity-hover-slider" ).slider({
                    range: "max",
                    min: 0,
                    max: 1,
                    step: 0.1,
                    value: fill_opacity_hover,
                    slide: function( event, ui ) {
                    	fill_opacity_hover = ui.value;
                        jQuery( "#ve-marker-fill-opacity-hover" ).val( ui.value );
                    }
                });
                jQuery( "#ve-marker-fill-opacity-hover" ).val( jQuery( "#ve-marker-fill-opacity-hover-slider" ).slider( "value" ) );

                jQuery( "#ve-marker-line-opacity-slider" ).slider({
                    range: "max",
                    min: 0,
                    max: 1,
                    step: 0.1,
                    value: stroke_opacity,
                    slide: function( event, ui ) {
                    	stroke_opacity = ui.value;
                    	poly.setOptions({
                            strokeOpacity: stroke_opacity
                        });
                        jQuery( "#ve-marker-line-opacity" ).val( ui.value );
                    }
                });
                jQuery( "#ve-marker-line-opacity" ).val( jQuery( "#ve-marker-line-opacity-slider" ).slider( "value" ) );

                jQuery( "#ve-marker-fill-opacity-slider" ).slider({
                    range: "max",
                    min: 0,
                    max: 1,
                    step: 0.1,
                    value: fill_opacity,
                    slide: function( event, ui ) {
                        fill_opacity = ui.value;
                    	poly.setOptions({
                            fillOpacity: fill_opacity
                        });
                        jQuery( "#ve-marker-fill-opacity" ).val( ui.value );
                    }
                });
                jQuery( "#ve-marker-fill-opacity" ).val( jQuery( "#ve-marker-fill-opacity-slider" ).slider( "value" ) );
            });
        </script>
        <style>
        .ui-slider { position: relative; border: 1px solid #ddd; text-align: left; background: #eee; }
        .ui-slider .ui-slider-handle { position: absolute; z-index: 2; width: 1.2em; height: 1.2em; cursor: default; }
        .ui-slider .ui-slider-range { position: absolute; z-index: 1; font-size: .7em; display: block; border: 0; background: #ccc; background-position: 0 0; }
        
        .ui-slider-horizontal { height: .8em; }
        .ui-slider-horizontal .ui-slider-handle { top: -.3em; margin-left: -.6em; background: #fff; border: 1px solid #ccc; }
        .ui-slider-horizontal .ui-slider-range { top: 0; height: 100%; }
        .ui-slider-horizontal .ui-slider-range-min { left: 0; }
        .ui-slider-horizontal .ui-slider-range-max { right: 0; }
        
        .ui-slider-vertical { width: .8em; height: 100px; }
        .ui-slider-vertical .ui-slider-handle { left: -.3em; margin-left: 0; margin-bottom: -.6em; }
        .ui-slider-vertical .ui-slider-range { left: 0; width: 100%; }
        .ui-slider-vertical .ui-slider-range-min { bottom: 0; }
        .ui-slider-vertical .ui-slider-range-max { top: 0; }
        </style>
        <script type="text/javascript">
        jQuery(window).load(function() {

        	
        	jQuery('#ve-marker-fill-color').wpColorPicker({
        	    change: function() {
        	    	poly.setOptions({
                		fillColor: jQuery('#ve-marker-fill-color').val()
                    });
        	    }
        	});
        	jQuery('#ve-marker-line-color').wpColorPicker({
        	    change: function() {
        	    	poly.setOptions({
                		strokeColor: jQuery('#ve-marker-line-color').val()
                    });
        	    }
        	});
            jQuery('#ve-gmap-marker-tabs').tabs({
                activate: function( event, ui ) {
                    jQuery('#marker_settings-tabs li').removeClass('tabs');
                    ui.newTab.addClass('tabs');
                },
                tabsactivate: function (event, ui) {
                    jQuery('#marker_settings-tabs li').removeClass('tabs');
                    ui.newTab.addClass('tabs');
                }
            });
            <?php 
            $backMapLink = get_edit_post_link($post->post_parent);
            if (!empty($_GET['map_id'])) {
                $backMapLink = get_edit_post_link($_GET['map_id']);
            }
            ?>
            jQuery('.wrap').find('h2:first').before('<a href="<?php echo $backMapLink; ?>" class="button button-primary"><?php _e('Back to map', GOOGLE_MAPS_VE_TD); ?></a>');
            jQuery('#show-marker-on-map').click(function() {
                if (!jQuery('#ve-marker-lat').val() || !jQuery('#ve-marker-long').val()) {
                    alert('<?php _e('Coordinates missing!', GOOGLE_MAPS_VE_TD); ?>');
                    return;
                }
                map.setCenter(new google.maps.LatLng(jQuery('#ve-marker-lat').val(), jQuery('#ve-marker-long').val()));
            });
            var geocoder = new google.maps.Geocoder();
            jQuery('#get-coordinates').click(function() {
                var address = jQuery('#ve-marker-address').val();
                if (!address) {
                    alert('<?php _e('Address is empty', GOOGLE_MAPS_VE_TD); ?>');
                    return;
                }

                geocoder.geocode({
                    address: address
                }, function(locResult) {
                    jQuery('#ve-marker-lat').val(locResult[0].geometry.location.lat());
                    jQuery('#ve-marker-long').val(locResult[0].geometry.location.lng());
                });
            });
        });
        </script>
        <?php
        
    }
    
    public function render_meta_box_polygon_map_container($post)
    {
        $markerId = $post->ID;
        $mapId = $post->post_parent;
        if (empty($mapId) && !empty($_GET['map_id'])) {
            $mapId = (int) $_GET['map_id'];
        }
        $map = $this->_google_maps->getApp()->getMap($mapId);
        $marker = $this->_google_maps->getApp()->getPolygon($markerId);

        $mapLat = get_post_meta($mapId, 've_map_lat', true);
        $mapLong = get_post_meta($mapId, 've_map_long', true);

        $mapZoom = get_post_meta($mapId, 've_map_zoom', true);
        $mapType = get_post_meta($mapId, 've_map_type', true);
        
        $polyString = json_decode(get_post_meta($markerId, 'marker_polystring', true));
        
        if (empty($mapType)) {
            $mapType = Google_Maps_Ve_Defs::MAP_TYPE_ROADMAP;
        }
        if (!is_numeric($mapLat)) {
            $mapLat = -34.397;
        }
        if (!is_numeric($mapLong)) {
            $mapLong = 150.644;
        }
        ?>
        <div id="ve-gmap-container" style="width: 100%; height: 400px; margin-bottom: 10px;"></div>
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
        <script>
        var map;
        var marker;
        var markers = [];
        var path = new google.maps.MVCArray;
        function initialize() {
          var polyPaths = [];
          <?php foreach ($polyString as $poly): ?>
              polyPaths.push(new google.maps.LatLng(<?php echo $poly[0]; ?>, <?php echo $poly[1]; ?>));
          <?php endforeach; ?>
          <?php if (!empty($polyString)): ?>
          var bounds = new google.maps.LatLngBounds();
          for (i = 0; i < polyPaths.length; i++) {
        	  bounds.extend(polyPaths[i]);
        	}
          var mapCenter = bounds.getCenter();
          <?php else: ?>
          var mapCenter = new google.maps.LatLng(<?php echo $mapLat; ?>, <?php echo $mapLong; ?>);
          <?php endif; ?>
          
          var mapOptions = {
            zoom: <?php echo (int) $mapZoom; ?>,
            center: mapCenter,
            mapTypeId: google.maps.MapTypeId.<?php echo $mapType; ?>,
    		panControl: <?php echo Google_Maps_Ve_Defs::getJavascriptBoolean($map->map_control_pan); ?>,
              zoomControl: <?php echo Google_Maps_Ve_Defs::getJavascriptBoolean($map->map_control_zoom); ?>,
              mapTypeControl: <?php echo Google_Maps_Ve_Defs::getJavascriptBoolean($map->map_control_map_type); ?>,
              scaleControl: <?php echo Google_Maps_Ve_Defs::getJavascriptBoolean($map->map_control_scale); ?>,
              streetViewControl: <?php echo Google_Maps_Ve_Defs::getJavascriptBoolean($map->map_control_street_view); ?>,
              overviewMapControl: <?php echo Google_Maps_Ve_Defs::getJavascriptBoolean($map->map_control_overview); ?>
          };
          map = new google.maps.Map(document.getElementById('ve-gmap-container'),
              mapOptions);
          window.ve_gmaps[<?php echo $mapId; ?>] = map;

          var bikeLayer = new google.maps.BicyclingLayer();
          var transitLayer = new google.maps.TransitLayer();
          var trafficLayer = new google.maps.TrafficLayer();
          <?php if ($map->map_layer_bicycling == Google_Maps_Ve_Defs::MAP_OPTION_YES): ?>
          bikeLayer.setMap(map);
          <?php endif; ?>
          <?php if ($map->map_layer_transit == Google_Maps_Ve_Defs::MAP_OPTION_YES): ?>
          transitLayer.setMap(map);
          <?php endif; ?>
          <?php if ($map->map_layer_traffic == Google_Maps_Ve_Defs::MAP_OPTION_YES): ?>
          trafficLayer.setMap(map);
          <?php endif; ?>

          

          poly = new google.maps.Polygon({
              paths: polyPaths,
              strokeWeight: 3,
              fillColor: '<?php echo $marker->marker_fill_color; ?>',
              strokeColor: '<?php echo $marker->marker_line_color; ?>',
              strokeOpacity: '<?php echo $marker->marker_line_opacity; ?>',
              fillOpacity: '<?php echo $marker->marker_fill_opacity; ?>',
              editable: true,
              draggable: true
          });
          poly.setMap(map);
          <?php if (empty($polyString)): ?>
          poly.setPaths(new google.maps.MVCArray([path]));
          <?php endif; ?>

           var polymouseover = function() {
            poly.setOptions({
                strokeOpacity: stroke_opacity_hover,
                fillOpacity: fill_opacity_hover
            });
           }
           var polymouseout = function() {
            poly.setOptions({
                strokeOpacity: stroke_opacity,
                fillOpacity: fill_opacity,
            });
          }

          google.maps.event.addListener(poly, 'mouseover', polymouseover);
          google.maps.event.addListener(poly, 'mouseout', polymouseout);

          
          var deleteNode = function(mev) {
        	  if (mev.vertex != null) {
        		  poly.getPath().removeAt(mev.vertex);
        	  }
        	}
    
          google.maps.event.addListener(map, 'click', addPoint);
          google.maps.event.addListener(poly, "dragend", getPolygonCoords);
          google.maps.event.addListener(poly.getPath(), "insert_at", getPolygonCoords);
          google.maps.event.addListener(poly.getPath(), "remove_at", getPolygonCoords);
          google.maps.event.addListener(poly.getPath(), "set_at", getPolygonCoords);
          google.maps.event.addListener(poly, 'rightclick', deleteNode);

          
        }
        
        google.maps.event.addDomListener(window, 'load', initialize);
        
        function addPoint(event) {
            path.insertAt(path.length, event.latLng);
        }

        function getPolygonCoords() {
            var len = poly.getPath().getLength();
            var res = [];
            for (var i = 0; i < len; i++) {
                res.push([poly.getPath().getAt(i).lat(), poly.getPath().getAt(i).lng()]);
            }
            var json = JSON.stringify(res);
            jQuery('#ve-gmap-polystring').val(json);
        }  

        </script>
        <?php
    }
    
    public function render_meta_box_add_polyline()
    {
        wp_nonce_field('ve_map_polyline_post', 've_map_polyline_post_nonce');
        
        global $post;
        $postId = $post->ID;
        $terms = wp_get_post_terms($postId, Google_Maps_Ve_App::TAXONOMY_MARKER, array("fields" => "ids"));

        $infoWindow = get_post_meta($postId, 'marker_infowindow', true);
        $polyString = get_post_meta($postId, 'marker_polystring', true);
        
        $lineOpacity = get_post_meta($postId, 'marker_line_opacity', true);
        $markerLineThick = get_post_meta($postId, 'marker_line_thick', true);
        if (empty($markerLineThick)) {
            $markerLineThick = 3;
        }
        
        $marker = $this->_google_maps->getApp()->getPolyline($postId);
        ?>
        
        <p class="info"><?php _e('You can set infowindow image from "Featured image" box on right sidebar', GOOGLE_MAPS_VE_TD); ?></p>
        <p class="info"><?php _e('Click on map to add vertex', GOOGLE_MAPS_VE_TD); ?>.</p>
        <p class="info"><?php _e('Right click on vertex to remove it.', GOOGLE_MAPS_VE_TD); ?></p>
        <p class="info"><?php _e('Drag polyline or vertex to move it.', GOOGLE_MAPS_VE_TD); ?></p>
        <input type="hidden" value="<?php echo $polyString; ?>" id="ve-gmap-polystring" name="marker_polystring" />
        <div class="categorydiv" id="ve-gmap-marker-tabs" style="margin-top: 20px; margin-bottom: 20px;">
            <ul id="marker_settings-tabs" class="category-tabs ve-tabs">
                <li class="tabs"><a href="#marker_general-tab"><?php _e('Polyline settings', GOOGLE_MAPS_VE_TD); ?></a></li>
                <li><a href="#marker_infowindow-tab"><?php _e('Infowindow settings', GOOGLE_MAPS_VE_TD); ?></a></li>
                <li><a href="#marker_parameters-tab"><?php _e('Polyline parameters', GOOGLE_MAPS_VE_TD); ?></a></li>
            </ul>
            <div id="marker_general-tab" class="tabs-panel" style="max-height: 600px;">
                <table class="add-marker-table ve-gmap-table">
                    <tr>
                        <th rowspan="2"><label for="ve-marker-description"><?php _e('Description', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td rowspan="2"><textarea name="marker_description" id="ve-marker-description"><?php echo $post->post_content; ?></textarea></td>
                        <td width="90px"></td>
                        <th><label for="ve-marker-line-color"><?php _e('Line color', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td><input type="text" id="ve-marker-line-color" class="ve-color-field" name="marker_line_color" value="<?php echo $marker->marker_line_color; ?>" /></td>
                    </tr>
                    <tr>
                        <td width="90px"></td>
                        <th><?php _e('Line opacity', GOOGLE_MAPS_VE_TD); ?></th>
                        <td>
                            <div id="ve-marker-line-opacity-slider"></div>
                            <input type="hidden" id="ve-marker-line-opacity" name="marker_line_opacity" value="<?php echo $marker->marker_line_opacity; ?>" readonly />
                        </td>
                    </tr>
                    <tr>
                        <th><label for="ve-marker-thickness"><?php _e('Line thickness', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td>
                            <div id="ve-marker-thickness-slider"></div>
                            <input type="hidden" id="ve-marker-thickness" name="marker_line_thick" value="<?php echo $marker->marker_line_thick; ?>" readonly />
                        </td>
                        <td></td>
                        <th><label for="ve-marker-line-opacity-hover"><?php _e('On hover line opacity', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td>
                            <div id="ve-marker-line-opacity-hover-slider"></div>
                            <input type="hidden" id="ve-marker-line-opacity-hover" name="marker_line_opacity_hover" value="<?php echo $marker->marker_line_opacity_hover; ?>" readonly />
                        </td>
                    </tr>
                    <tr>
                        <th><label for="ve-marker-map"><?php _e('Map', GOOGLE_MAPS_VE_TD); ?></label></th>
                        <td>
                            <select name="ve_marker_map">
                            <?php
                            $maps = get_posts(array(
                                'post_type' => Google_Maps_Ve_App::POST_TYPE_MAPS,
                                'posts_per_page' => -1,
                            ));
                            foreach ($maps as $map):
                            ?>
                                <option value="<?php echo $map->ID; ?>"<?php if ($map->ID == $post->post_parent || $map->ID == $_GET['map_id']): ?> selected<?php endif; ?>><?php echo esc_html($map->post_title); ?></option>
                            <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="marker_infowindow-tab" class="tabs-panel" style="display: none;">
                <div class="ve-gmap-pro-feature-info"><a href="http://www.googlemapswordpress.com/buy-pro-version/" title="" target="_blank"><?php _e('Get PRO version for this feature', GOOGLE_MAPS_VE_TD); ?></a></div>
                <table class="add-marker-table ve-gmap-table">
                    <tr>
                        <th><?php _e('InfoWindow', GOOGLE_MAPS_VE_TD); ?></th>
                        <td>
                            <select>
                                <?php foreach (Google_Maps_Ve_Defs::getInfoWindowTypes() as $mapK => $mapV): ?>
                                <option value="<?php echo $mapK; ?>"<?php if ($infoWindow == $mapK): ?> selected<?php endif; ?>><?php echo esc_html($mapV); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label><?php _e('Window width', GOOGLE_MAPS_VE_TD); ?></label>
                            <div style="font-size: 9px; font-weight: normal;"><?php _e('Leave empty for default', GOOGLE_MAPS_VE_TD); ?></div>
                        </th>
                        <td><input type="text" disabled style="width: 100px;" /> px</td>
                        <td width="90px"></td>
                        <th>
                            <label><?php _e('Window CSS class', GOOGLE_MAPS_VE_TD); ?></label>
                        </th>
                        <td><input type="text" disabled style="width: 100px;" /></td>
                    </tr>
                    <tr>
                        <th>
                            <label><?php _e('Link text', GOOGLE_MAPS_VE_TD); ?></label>
                            <div style="font-size: 9px; font-weight: normal;"><?php _e('Example "See more"', GOOGLE_MAPS_VE_TD); ?></div>
                        </th>
                        <td><input type="text" disabled style="width: 150px;" /></td>
                        <td width="90px"></td>
                        <th>
                            <label><?php _e('Link URL', GOOGLE_MAPS_VE_TD); ?></label>
                        </th>
                        <td><input type="text" disabled style="width: 100%; min-width: 300px;" /></td>
                    </tr>
                    <tr>
                        <th>
                            <label><?php _e('Image width', GOOGLE_MAPS_VE_TD); ?></label>
                            <div style="font-size: 9px; font-weight: normal;"><?php _e('Leave empty for default', GOOGLE_MAPS_VE_TD); ?></div>
                        </th>
                        <td><input type="text" disabled style="width: 100px;" /> px</td>
                        <td width="90px"></td>
                        <th>
                            <label><?php _e('Image height', GOOGLE_MAPS_VE_TD); ?></label>
                            <div style="font-size: 9px; font-weight: normal;"><?php _e('Leave empty for default', GOOGLE_MAPS_VE_TD); ?></div>
                        </th>
                        <td><input type="text" disabled style="width: 100px;" /> px</td>
                    </tr>
                </table>
            </div>
            <div id="marker_parameters-tab" class="tabs-panel" style="display: none;">
                <div class="ve-gmap-pro-feature-info"><a href="http://www.googlemapswordpress.com/buy-pro-version/" title="" target="_blank"><?php _e('Get PRO version for this feature', GOOGLE_MAPS_VE_TD); ?></a></div>
                <ul class="marker-parameters-tab-ul">
                <?php 
                wp_terms_checklist($post->ID, array(
                    'taxonomy' => Google_Maps_Ve_App::TAXONOMY_MARKER,
                    'selected_cats' => $terms,
                    'checked_ontop' => false,
                ));
                ?>
                </ul>
            </div>
        </div>
        <script>
            var stroke_color= '<?php echo $marker->marker_line_color; ?>';
            var stroke_opacity= '<?php echo $marker->marker_line_opacity; ?>';
            var stroke_opacity_hover = '<?php echo $marker->marker_line_opacity_hover; ?>';
            var line_thickness = '<?php echo $marker->marker_line_thick; ?>';
            
            jQuery(function() {
                jQuery('#marker_parameters-tab input').attr('disabled', 'disabled');
                jQuery( "#ve-marker-line-opacity-hover-slider" ).slider({
                    range: "max",
                    min: 0,
                    max: 1,
                    step: 0.1,
                    value: stroke_opacity_hover,
                    slide: function( event, ui ) {
                    	stroke_opacity_hover = ui.value;
                        jQuery( "#ve-marker-line-opacity-hover" ).val( ui.value );
                    }
                });
                jQuery( "#ve-marker-line-opacity-hover" ).val( jQuery( "#ve-marker-line-opacity-hover-slider" ).slider( "value" ) );

                jQuery( "#ve-marker-thickness-slider" ).slider({
                    range: "max",
                    min: 1,
                    max: 50,
                    step: 1,
                    value: line_thickness,
                    slide: function( event, ui ) {
                    	line_thickness = ui.value;
                    	poly.setOptions({
                    		strokeWeight: line_thickness
                        });
                        jQuery( "#ve-marker-thickness" ).val( ui.value );
                    }
                });
                jQuery( "#ve-marker-thickness" ).val( jQuery( "#ve-marker-thickness-slider" ).slider( "value" ) );

                jQuery( "#ve-marker-line-opacity-slider" ).slider({
                    range: "max",
                    min: 0,
                    max: 1,
                    step: 0.1,
                    value: stroke_opacity,
                    slide: function( event, ui ) {
                    	stroke_opacity = ui.value;
                    	poly.setOptions({
                            strokeOpacity: stroke_opacity
                        });
                        jQuery( "#ve-marker-line-opacity" ).val( ui.value );
                    }
                });
                jQuery( "#ve-marker-line-opacity" ).val( jQuery( "#ve-marker-line-opacity-slider" ).slider( "value" ) );
            });
        </script>
        <style>
        .ui-slider { position: relative; border: 1px solid #ddd; text-align: left; background: #eee; }
        .ui-slider .ui-slider-handle { position: absolute; z-index: 2; width: 1.2em; height: 1.2em; cursor: default; }
        .ui-slider .ui-slider-range { position: absolute; z-index: 1; font-size: .7em; display: block; border: 0; background: #ccc; background-position: 0 0; }
        
        .ui-slider-horizontal { height: .8em; }
        .ui-slider-horizontal .ui-slider-handle { top: -.3em; margin-left: -.6em; background: #fff; border: 1px solid #ccc; }
        .ui-slider-horizontal .ui-slider-range { top: 0; height: 100%; }
        .ui-slider-horizontal .ui-slider-range-min { left: 0; }
        .ui-slider-horizontal .ui-slider-range-max { right: 0; }
        
        .ui-slider-vertical { width: .8em; height: 100px; }
        .ui-slider-vertical .ui-slider-handle { left: -.3em; margin-left: 0; margin-bottom: -.6em; }
        .ui-slider-vertical .ui-slider-range { left: 0; width: 100%; }
        .ui-slider-vertical .ui-slider-range-min { bottom: 0; }
        .ui-slider-vertical .ui-slider-range-max { top: 0; }
        </style>
        <script type="text/javascript">
        jQuery(window).load(function() {

        	
        	jQuery('#ve-marker-fill-color').wpColorPicker({
        	    change: function() {
        	    	poly.setOptions({
                		fillColor: jQuery('#ve-marker-fill-color').val()
                    });
        	    }
        	});
        	jQuery('#ve-marker-line-color').wpColorPicker({
        	    change: function() {
        	    	poly.setOptions({
                		strokeColor: jQuery('#ve-marker-line-color').val()
                    });
        	    }
        	});
            jQuery('#ve-gmap-marker-tabs').tabs({
                activate: function( event, ui ) {
                    jQuery('#marker_settings-tabs li').removeClass('tabs');
                    ui.newTab.addClass('tabs');
                },
                tabsactivate: function (event, ui) {
                    jQuery('#marker_settings-tabs li').removeClass('tabs');
                    ui.newTab.addClass('tabs');
                }
            });
            <?php 
            $backMapLink = get_edit_post_link($post->post_parent);
            if (!empty($_GET['map_id'])) {
                $backMapLink = get_edit_post_link($_GET['map_id']);
            }
            ?>
            jQuery('.wrap').find('h2:first').before('<a href="<?php echo $backMapLink; ?>" class="button button-primary"><?php _e('Back to map', GOOGLE_MAPS_VE_TD); ?></a>');
            jQuery('#show-marker-on-map').click(function() {
                if (!jQuery('#ve-marker-lat').val() || !jQuery('#ve-marker-long').val()) {
                    alert('<?php _e('Coordinates missing!', GOOGLE_MAPS_VE_TD); ?>');
                    return;
                }
                map.setCenter(new google.maps.LatLng(jQuery('#ve-marker-lat').val(), jQuery('#ve-marker-long').val()));
            });
            var geocoder = new google.maps.Geocoder();
            jQuery('#get-coordinates').click(function() {
                var address = jQuery('#ve-marker-address').val();
                if (!address) {
                    alert('<?php _e('Address is empty', GOOGLE_MAPS_VE_TD); ?>');
                    return;
                }

                geocoder.geocode({
                    address: address
                }, function(locResult) {
                    jQuery('#ve-marker-lat').val(locResult[0].geometry.location.lat());
                    jQuery('#ve-marker-long').val(locResult[0].geometry.location.lng());
                });
            });
        });
        </script>
        <?php
        
    }
    
    public function render_meta_box_polyline_map_container($post)
    {
        $markerId = $post->ID;
        $mapId = $post->post_parent;
        $map = $this->_google_maps->getApp()->getMap($mapId);
        if (empty($mapId) && !empty($_GET['map_id'])) {
            $mapId = (int) $_GET['map_id'];
        }
        $marker = $this->_google_maps->getApp()->getPolyline($markerId);

        $mapLat = get_post_meta($mapId, 've_map_lat', true);
        $mapLong = get_post_meta($mapId, 've_map_long', true);

        $mapZoom = get_post_meta($mapId, 've_map_zoom', true);
        $mapType = get_post_meta($mapId, 've_map_type', true);
        
        $polyString = json_decode(get_post_meta($markerId, 'marker_polystring', true));
        
        if (empty($mapType)) {
            $mapType = Google_Maps_Ve_Defs::MAP_TYPE_ROADMAP;
        }
        if (!is_numeric($mapLat)) {
            $mapLat = -34.397;
        }
        if (!is_numeric($mapLong)) {
            $mapLong = 150.644;
        }
        ?>
        <div id="ve-gmap-container" style="width: 100%; height: 400px; margin-bottom: 10px;"></div>
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
        <script>
        var map;
        var marker;
        var markers = [];
        var path = new google.maps.MVCArray;
        function initialize() {
          var polyPaths = [];
          <?php foreach ($polyString as $poly): ?>
              polyPaths.push(new google.maps.LatLng(<?php echo $poly[0]; ?>, <?php echo $poly[1]; ?>));
          <?php endforeach; ?>
          <?php if (!empty($polyString)): ?>
          var bounds = new google.maps.LatLngBounds();
          for (i = 0; i < polyPaths.length; i++) {
              bounds.extend(polyPaths[i]);
          }
          var mapCenter = bounds.getCenter();
          <?php else: ?>
          var mapCenter = new google.maps.LatLng(<?php echo $mapLat; ?>, <?php echo $mapLong; ?>);
          <?php endif; ?>
          
          var mapOptions = {
            zoom: <?php echo (int) $mapZoom; ?>,
            center: mapCenter,
            mapTypeId: google.maps.MapTypeId.<?php echo $mapType; ?>,
    		panControl: <?php echo Google_Maps_Ve_Defs::getJavascriptBoolean($map->map_control_pan); ?>,
          zoomControl: <?php echo Google_Maps_Ve_Defs::getJavascriptBoolean($map->map_control_zoom); ?>,
          mapTypeControl: <?php echo Google_Maps_Ve_Defs::getJavascriptBoolean($map->map_control_map_type); ?>,
          scaleControl: <?php echo Google_Maps_Ve_Defs::getJavascriptBoolean($map->map_control_scale); ?>,
          streetViewControl: <?php echo Google_Maps_Ve_Defs::getJavascriptBoolean($map->map_control_street_view); ?>,
          overviewMapControl: <?php echo Google_Maps_Ve_Defs::getJavascriptBoolean($map->map_control_overview); ?>
          };
          map = new google.maps.Map(document.getElementById('ve-gmap-container'),
              mapOptions);
          window.ve_gmaps[<?php echo $mapId; ?>] = map;

          var bikeLayer = new google.maps.BicyclingLayer();
          var transitLayer = new google.maps.TransitLayer();
          var trafficLayer = new google.maps.TrafficLayer();
          <?php if ($map->map_layer_bicycling == Google_Maps_Ve_Defs::MAP_OPTION_YES): ?>
          bikeLayer.setMap(map);
          <?php endif; ?>
          <?php if ($map->map_layer_transit == Google_Maps_Ve_Defs::MAP_OPTION_YES): ?>
          transitLayer.setMap(map);
          <?php endif; ?>
          <?php if ($map->map_layer_traffic == Google_Maps_Ve_Defs::MAP_OPTION_YES): ?>
          trafficLayer.setMap(map);
          <?php endif; ?>

          var polyOptions = {
            strokeColor: '<?php echo $marker->marker_line_color; ?>',
            strokeOpacity: <?php echo $marker->marker_line_opacity; ?>,
            strokeWeight: <?php echo $marker->marker_line_thick; ?>,
            editable: true,
            draggable: true
          };
          poly = new google.maps.Polyline(polyOptions);

          for (i = 0; i < polyPaths.length; i++) {
              poly.getPath().push(polyPaths[i]);
          }
          poly.setMap(map);

          google.maps.event.addListener(map, 'click', addPoint);
          

           var polymouseover = function() {
            poly.setOptions({
                strokeOpacity: stroke_opacity_hover
            });
           }
           var polymouseout = function() {
            poly.setOptions({
                strokeOpacity: stroke_opacity
            });
          }

          google.maps.event.addListener(poly, 'mouseover', polymouseover);
          google.maps.event.addListener(poly, 'mouseout', polymouseout);
          google.maps.event.addListener(poly, "dragend", getPolygonCoords);

          
          var deleteNode = function(mev) {
              if (mev.vertex != null) {
                poly.getPath().removeAt(mev.vertex);
              }
              getPolygonCoords();
          }

          google.maps.event.addListener(poly, 'rightclick', deleteNode);

          
        }
        
        google.maps.event.addDomListener(window, 'load', initialize);
        
        function addPoint(event) {
            var path = poly.getPath();
            path.push(event.latLng);
            getPolygonCoords();
        }

        function getPolygonCoords() {
            var len = poly.getPath().getLength();
            var res = [];
            for (var i = 0; i < len; i++) {
                res.push([poly.getPath().getAt(i).lat(), poly.getPath().getAt(i).lng()]);
            }
            var json = JSON.stringify(res);
            jQuery('#ve-gmap-polystring').val(json);
        }  

        </script>
        <?php
    }
    
    public function render_meta_box_map_polylines_list($post)
    {
        $markers = $this->_google_maps->getApp()->getMapPolylines($post->ID);
        ?>
        <a href="/wp-admin/post-new.php?post_type=<?php echo Google_Maps_Ve_App::POST_TYPE_POLYLINE; ?>&map_id=<?php echo $post->ID; ?>" class="button button-primary"><?php _e('Add new polyline', GOOGLE_MAPS_VE_TD); ?></a>
        <table class="ve-gmap-markers-table ve-gmap-table" style="margin-top: 20px;">
            <thead>
                <tr>
                    <th><?php _e('Info', GOOGLE_MAPS_VE_TD); ?></th>
                    <th colspan="3"></th>
                </tr>
            </thead>
            <tbody>
            <?php 
            foreach ($markers as $marker):
            $polys = null;
            if (!empty($marker->polystring)) {
                $polys = json_decode($marker->polystring);
            }
            ?>
            <tr>
                <td>
                    <strong><?php echo esc_html($marker->post_title); ?></strong><br />
                    <?php echo apply_filters( 'the_content', $marker->post_content ); ?>
                </td>
                <td><?php if (!empty($polys)): ?><a href="javascript:ve_center_map(<?php echo $marker->post_parent; ?>, <?php echo $polys[0][0]; ?>, <?php echo $polys[0][1]; ?>, 've-gmap-container');"><?php _e('See on map', GOOGLE_MAPS_VE_TD); ?></a><?php endif; ?></td>
                <td><a href="<?php echo get_edit_post_link($marker->ID); ?>"><?php _e('Edit', GOOGLE_MAPS_VE_TD); ?></a></td>
                <td class="submitbox"><a href="<?php echo get_delete_post_link($marker->ID); ?>&redirect_to=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" class="submitdelete deletion" onclick="return confirm('<?php _e('Are you sure?', GOOGLE_MAPS_VE_TD); ?>'); "><?php _e('Delete', GOOGLE_MAPS_VE_TD); ?></a></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <script type="text/javascript">
        function ve_load_polylines(map) {
            window.ve_gmap_polylines[<?php echo $post->ID; ?>] = {};
            <?php foreach ($markers as $marker): ?>
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

                window.ve_gmap_polylines[<?php echo $post->ID; ?>][<?php echo $marker->ID; ?>] = new google.maps.Polyline({
                    strokeColor: '<?php echo $marker->marker_line_color; ?>',
                    strokeOpacity: <?php echo $marker->marker_line_opacity; ?>,
                    strokeWeight: <?php echo $marker->marker_line_thick; ?>
                });

                for (i = 0; i < polyPaths.length; i++) {
                	window.ve_gmap_polylines[<?php echo $post->ID; ?>][<?php echo $marker->ID; ?>].getPath().push(polyPaths[i]);
                }
                window.ve_gmap_polylines[<?php echo $post->ID; ?>][<?php echo $marker->ID; ?>].setMap(map);

                google.maps.event.addListener(window.ve_gmap_polylines[<?php echo $post->ID; ?>][<?php echo $marker->ID; ?>], 'mouseover', function() {
                	window.ve_gmap_polylines[<?php echo $post->ID; ?>][<?php echo $marker->ID; ?>].setOptions({
                        strokeOpacity: '<?php echo $marker->marker_line_opacity_hover; ?>'
                    });
                });
                google.maps.event.addListener(window.ve_gmap_polylines[<?php echo $post->ID; ?>][<?php echo $marker->ID; ?>], 'mouseout', function() {
                	window.ve_gmap_polylines[<?php echo $post->ID; ?>][<?php echo $marker->ID; ?>].setOptions({
                        strokeOpacity: '<?php echo $marker->marker_line_opacity; ?>'
                    });
                });
                
                
            <?php endforeach; ?>
        };
        </script>
        <?php
    }
}
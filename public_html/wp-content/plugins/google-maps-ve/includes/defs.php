<?php

class Google_Maps_Ve_Defs
{
    
    const MAP_TYPE_ROADMAP = 'ROADMAP';
    const MAP_TYPE_SATELLITE = 'SATELLITE';
    const MAP_TYPE_HYBRID = 'HYBRID';
    const MAP_TYPE_TERRAIN = 'TERRAIN';
    
    const MAP_SIZE_TYPE_PX = 'px';
    const MAP_SIZE_TYPE_PERC = '%';
    
    const MAP_OPTION_YES = 'yes';
    const MAP_OPTION_NO = 'no';
    
    const MAP_INFOWINDOW_OPEN = 'open';
    const MAP_INFOWINDOW_CLOSE = 'close';
    const MAP_INFOWINDOW_NONE = 'none';
    
    const MAP_ANIMATION_NONE = 'none';
    const MAP_ANIMATION_BOUNCE = 'BOUNCE';
    const MAP_ANIMATION_DROP = 'DROP';
    
    public static function getJavascriptBoolean($value)
    {
        if (empty($value)) {
            return 'true';
        }
        else if ($value == self::MAP_OPTION_YES) {
            return 'true';
        }
        return 'false';
    }
    
    public static function getMapTypes()
    {
        return array(
            self::MAP_TYPE_ROADMAP => __('Roadmap', GOOGLE_MAPS_VE_TD),
            self::MAP_TYPE_SATELLITE => __('Satellite', GOOGLE_MAPS_VE_TD),
            self::MAP_TYPE_HYBRID => __('Hybrid', GOOGLE_MAPS_VE_TD),
            self::MAP_TYPE_TERRAIN => __('Terrain', GOOGLE_MAPS_VE_TD),
        );
    }
    
    public static function getSizeTypes()
    {
        return array(
            self::MAP_SIZE_TYPE_PX => __('px', GOOGLE_MAPS_VE_TD),
            self::MAP_SIZE_TYPE_PERC => __('%', GOOGLE_MAPS_VE_TD),
        );
    }
    
    public static function getYesNoTypes()
    {
        return array(
            self::MAP_OPTION_NO => __('No', GOOGLE_MAPS_VE_TD),
            self::MAP_OPTION_YES => __('Yes', GOOGLE_MAPS_VE_TD),
        );
    }
    
    public static function getInfoWindowTypes()
    {
        return array(
            self::MAP_INFOWINDOW_NONE => __('Not enabled', GOOGLE_MAPS_VE_TD),
            self::MAP_INFOWINDOW_CLOSE => __('Default closed', GOOGLE_MAPS_VE_TD),
            self::MAP_INFOWINDOW_OPEN => __('Default open', GOOGLE_MAPS_VE_TD),
        );
    }
    
    public static function getAnimationTypes()
    {
        return array(
            self::MAP_ANIMATION_NONE => __('None', GOOGLE_MAPS_VE_TD),
            self::MAP_ANIMATION_DROP => __('Drop', GOOGLE_MAPS_VE_TD),
            self::MAP_ANIMATION_BOUNCE => __('Bounce', GOOGLE_MAPS_VE_TD),
        );
    }
}
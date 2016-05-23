<?

/* Modify these parameters only BEFORE installing this plugin */
$preInstall = array();
$preInstall['profile_field_label'] = "HeatWare Feedback";
$preInstall['profile_tab_name'] = "HeatWare Feedback";
$preInstall['profile_field_description'] = "Display your HeatWare information in your forum profile?";

$hwconfig = array();
$hwconfig['stats_table'] = 'hw_stats';
$hwconfig['api_key'] = '111';
$hwconfig['api_find_user'] = 'http://local.heatware.com/api/findUser';
$hwconfig['api_get_stats'] = 'http://local.heatware.com/api/user';
$hwconfig['api_phone_home'] = 'http://local.heatware.com/api/phoneHome';


$configAll = array_merge($preInstall, $hwconfig);
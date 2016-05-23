<?

$API_KEY = ''; // REQUIRED: Enter the API key that you obtained from www.heatware.com

$preInstall = array();
$hwconfig = array();

/* DO NOT MODIFY PARAMETERS AFTER INSTALLING THE PLUGIN */
$preInstall['profile_field_label'] = "HeatWare Feedback";
$preInstall['profile_tab_name'] = "HeatWare Feedback";
$preInstall['profile_field_description'] = "Display your HeatWare feedback stats in your forum profile?";


$hwconfig['api_key'] = $API_KEY;
$hwconfig['api_find_user'] = 'http://local.heatware.com/api/findUser';
$hwconfig['api_get_stats'] = 'http://local.heatware.com/api/user';
$hwconfig['api_phone_home'] = 'http://local.heatware.com/api/phoneHome';
$hwconfig['stats_table'] = 'hw_stats'; // DO NOT MODIFY
$configAll = array_merge($preInstall, $hwconfig);
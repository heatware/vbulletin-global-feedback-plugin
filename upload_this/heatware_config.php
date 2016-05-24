<?

$API_KEY = ''; // REQUIRED: Enter the API key that you obtained from www.heatware.com (heatware.support [at] gmail.com)

$preInstall = array();
$hwconfig = array();

/* DO NOT MODIFY PARAMETERS AFTER INSTALLING THE PLUGIN */
$preInstall['profile_field_label'] = "HeatWare Feedback";
$preInstall['profile_tab_name'] = "HeatWare Feedback";
$preInstall['profile_field_description'] = "Display your HeatWare feedback stats in your forum profile?";


$hwconfig['api_key'] = $API_KEY;
$hwconfig['api_find_user'] = 'http://www.heatware.com/api/findUser';
$hwconfig['api_get_stats'] = 'http://www.heatware.com/api/user';
$hwconfig['api_phone_home'] = 'http://www.heatware.com/api/phoneHome';
$configAll = array_merge($preInstall, $hwconfig);

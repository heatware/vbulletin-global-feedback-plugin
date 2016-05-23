<?php
error_reporting(E_ALL & ~E_NOTICE);

if (!defined('VB_ENTRY')){
die('Access denied.');
}

require_once ('./global.php');
require_once (DIR . '/vb/search/core.php');
require_once(DIR . '/heatware_common.php');

@set_time_limit(0);

$helper = new HW_Helper($configAll);
$helper->find_heatware_user();

log_cron_action('', $nextitem, 1);
<?php
if (!defined('VB_ENTRY'))
{
die('Access denied.');
}

require_once ('./global.php');
require_once(DIR . '/heatware_common.php');
$helper = new HW_Helper($configAll);

$row = $helper->get_stats_by_forum_user_id($vbulletin->GPC['userid']);
?>

<? if(is_array($row) && ($row['eval_total'] != '')): ?>
    <div class="blocksubhead subsectionhead userprof_headers userprof_headers_border">
        <h4 class="subsectionhead-understate">Account Information</h4>
    </div>
    <div class="blockbody subsection userprof_content userprof_content_border">
        <?=$helper->display_profile_name_value('Username', $row['username'] . '&nbsp;&nbsp;<a href="http://www.heatware.com/u/'.$row['heatware_user_id'] . '" target="_blank"><small>[View Full Profile]</small></a>');?>        
        <?=$helper->display_profile_name_value('Account Status', $row['account_status']);?>        
        <?=$helper->display_profile_name_value('Rank', $row['rank']);?>    
     </div>
    <div class="blocksubhead subsectionhead userprof_headers userprof_headers_border">
        <h4 class="subsectionhead-understate">User Feedback</h4>
    </div>
    <div class="blockbody subsection userprof_content userprof_content_border">
        <?=$helper->display_profile_name_value('Total', $row['eval_total']);?>
        <?=$helper->display_profile_name_value('Positive', $row['eval_pos']);?>    
        <?=$helper->display_profile_name_value('Neutral', $row['eval_neu']);?>    
        <?=$helper->display_profile_name_value('Negative', $row['eval_neg']);?>    
     </div>
<? else: ?>
<br />
<div class="blocksubhead subsectionhead userprof_headers userprof_headers_border">
    <h4 class="subsectionhead-understate">Account Information</h4>
</div><br />
    <? if($row['heatware_user_id'] != ''): ?>
       <div style="font-style:italic;width:100%;text-align:center;font-size:110%">We are in the process of getting Heatware stats for this user. However, until then you may view them on <a href="http://www.heatware.com/u/<?=$row['heatware_user_id'];?>" target="_blank">heatware.com</a></div>
    <? else: ?>
        <? if(($vbulletin->userinfo['userid'] == $vbulletin->GPC['userid']) && ($row['last_error_at'] > 0)):?>
               <div style="font-style:italic;width:100%;text-align:center;font-size:110%">We are unable to find your HeatWare account. Please ensure that your forum email address <span style="font-weight:bold"><?=$vbulletin->userinfo['email'];?></span> is associated with your HeatWare account.</div>
        <? else:?>
             <div style="font-style:italic;width:100%;text-align:center;font-size:110%">We are in the process of getting Heatware stats for this user</div>
        <? endif; ?>
    <? endif; ?>
<? endif ;?>

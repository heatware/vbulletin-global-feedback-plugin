<?php
if (!defined('VB_ENTRY')) {
    die('Access denied.');
}

require_once('./global.php');
require_once(DIR . '/heatware_restclient.php');
require_once(DIR . '/heatware_config.php');

class HW_Helper
{
    public $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function is_enabled($user_id)
    {
        global $vbulletin;

        $field = 'field' . $this->get_fieldnum();
        $row = $vbulletin->db->query_first("SELECT {$field} from ".TABLE_PREFIX."userfield WHERE userid='$user_id'");
        if (count($row) == 0) {
            return false;
        }
        if (strtolower($row[$field]) == 'yes') {
            return true;
        }
    }

    public function display_profile_name_value($name, $value)
    {
        return '<dl class="blockrow stats" style="padding-bottom:3px"><dt style="width:225px">' . $name .'</dt><dd>'. $value .'</dd></dl>';
    }
    public function show_profile_tab($user_id)
    {
        return $this->is_enabled($user_id);
    }

    public function has_stats($row)
    {
        if (count($row) == 0) {
            return false;
        }
        if ($row['heatware_user_id'] == '' || $row['eval_total']) {
            return true;
        }
    }

    public function get_fieldnum()
    {
        global $vbulletin;
        $row = $vbulletin->db->query_first('
			SELECT phrase.*
			FROM ' . TABLE_PREFIX . "phrase AS phrase
			WHERE text = '{$this->config['profile_field_label']}' AND fieldname='cprofilefield' AND product='heatware'
	");

        return preg_replace('/[^0-9]/', '', $row['varname']);
    }

    public function get_stats_by_forum_user_id($user_id)
    {
        global $vbulletin;
        $row = $vbulletin->db->query_first('SELECT * from '.TABLE_PREFIX."{$this->config['stats_table']} WHERE userid='$user_id'");

        return $row;
    }

    public function get_stats_by_heatware_user_id($user_id)
    {
        global $vbulletin;
        $row = $vbulletin->db->query_first('SELECT * from '.TABLE_PREFIX."{$this->config['stats_table']} WHERE heatware_id='$user_id");

        return $row;
    }

    public function check_profile_setting()
    {
        global $vbulletin;

        $field = 'field' . $this->get_fieldnum();
        $vbulletin->db->query_write('INSERT INTO '.TABLE_PREFIX."{$this->config['stats_table']}  (userid) (SELECT userid FROM ".TABLE_PREFIX."userfield WHERE LOWER({$field})='yes' AND userid NOT IN (SELECT userid FROM ".TABLE_PREFIX."{$this->config['stats_table']}) )");
        $row = $vbulletin->db->query_write('DELETE FROM '.TABLE_PREFIX."{$this->config['stats_table']} WHERE userid IN (SELECT userid FROM ".TABLE_PREFIX."userfield WHERE {$field}= '' OR LOWER({$field})='no')");
    }

    public function fetch_heatware_stats()
    {
        global $vbulletin;
        $secs_since_last_update = 86400;    //	1 day
                $limit = 50;
        $query = $vbulletin->db->query_read('SELECT userid, heatware_user_id FROM '.TABLE_PREFIX."{$this->config['stats_table']} WHERE heatware_user_id > 0 AND last_updated_at < (UNIX_TIMESTAMP() - {$secs_since_last_update}) ORDER by last_updated_at ASC LIMIT {$limit}");
        while ($row = $query->fetch_array()) {
            if ($response = $this->api_get_stats($row['heatware_user_id'])) {
                $vbulletin->db->query_write('UPDATE '.TABLE_PREFIX."{$this->config['stats_table']} SET
				username='{$response->profile->username}',
				eval_total={$response->profile->feedback->numTotal},
				eval_pos={$response->profile->feedback->numPositive},
				eval_neg={$response->profile->feedback->numNegative},
				eval_neu={$response->profile->feedback->numNeutral},
				rank={$response->profile->feedback->rank},
				account_status='{$response->profile->accountStatus}',
				last_updated_at=UNIX_TIMESTAMP(),
				last_error_at=0
				WHERE userid={$row['userid']}");
            }
        }
    }

    public function find_heatware_user()
    {
        global $vbulletin;
        $limit = 50;
        $query = $vbulletin->db->query_read('SELECT h.userid, u.email FROM '.TABLE_PREFIX."{$this->config['stats_table']} h JOIN ".TABLE_PREFIX."user u ON h.userid=u.userid WHERE heatware_user_id IS NULL OR heatware_user_id = '' ORDER by last_error_at ASC LIMIT {$limit}");

        while ($row = $query->fetch_array()) {
            {
                if ($heatware_user_id = $this->api_find_user_by_email($row['email'])) {
                    $vbulletin->db->query_write('UPDATE '.TABLE_PREFIX."{$this->config['stats_table']} SET heatware_user_id={$heatware_user_id}, last_error_at='0' WHERE userid={$row['userid']}");
                } else {
                    $vbulletin->db->query_write('UPDATE '.TABLE_PREFIX."{$this->config['stats_table']} SET last_error_at=UNIX_TIMESTAMP() WHERE userid={$row['userid']}");
                }
            }
        }
    }

    public function api_find_user_by_email($email)
    {
        global $vbulletin;

        $api = new RestClient();
        $result = $api->get($this->config['api_find_user'], array('email' => $email), array('X-API-KEY' => $this->config['api_key']));
        if ($result->info->http_code  == '404') {
            return false;
        }
        $response_json = $result->decode_response();

        return $response_json->userId;
    }

    public function api_get_stats($heatware_user_id)
    {
        global $vbulletin;

        $api = new RestClient();
        $result = $api->get($this->config['api_get_stats'], array('userId' => $heatware_user_id), array('X-API-KEY' => $this->config['api_key']));
        if ($result->info->http_code  == '404') {
            return false;
        }
        $response_json = $result->decode_response();

        return $response_json;
    }

    public function api_phone_home()
    {
        global $vbulletin;

        $total_hw_table = $vbulletin->db->query_first('SELECT count(*) as count FROM '.TABLE_PREFIX."{$this->config['stats_table']}");
        $no_hw_id = $vbulletin->db->query_first('SELECT count(*) as count FROM '.TABLE_PREFIX."{$this->config['stats_table']} WHERE heatware_user_id IS NULL OR heatware_user_id = ''");
        $has_stats = $vbulletin->db->query_first('SELECT count(*) as count FROM '.TABLE_PREFIX."{$this->config['stats_table']} WHERE heatware_user_id > 0 AND eval_total IS NOT NULL");
        $no_stats = $vbulletin->db->query_first('SELECT count(*) as count FROM '.TABLE_PREFIX."{$this->config['stats_table']} WHERE heatware_user_id > 0 AND eval_total IS NULL");

        $postData = array(
                    'site_url' => $vbulletin->options['bburl'],
                    'site_title' => $vbulletin->options['bbtitle'],
                    'site_type' => 'vBulletin',
                    'site_version' => $vbulletin->options['templateversion'],
                    'plugin_status' => (isset($vbulletin->products['heatware'])) ? $vbulletin->products['heatware'] : -1,
                    'stats_table_count' => $total_hw_table['count'],
                    'stats_table_has_stats' => $has_stats['count'],
                    'stats_table_no_hw_id' => $no_hw_id['count'],
                    'stats_table_no_stats' => $no_stats['count']
                    );

        $api = new RestClient();
        $result = $api->post($this->config['api_phone_home'], array('body' => json_encode($postData, JSON_UNESCAPED_SLASHES)), array('X-API-KEY' => $this->config['api_key']));
    }
}
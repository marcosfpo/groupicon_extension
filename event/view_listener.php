<?php

/**
*
* Group Icon
*
* @copyright (c) 2015 MarcosFPo
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace marcosfpo\groupicon\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class view_listener implements EventSubscriberInterface
{

    /** @var \phpbb\db\driver\driver_interface */
    protected $db;
    
    /** @var \phpbb\template\template */
    protected $template;


    public function __construct(\phpbb\template\template $template, \phpbb\db\driver\driver_interface $db)
    {
        $this->db = $db;
        $this->template = $template;
    }

    static public function getSubscribedEvents()
    {
        return array(
            'core.viewtopic_modify_post_row' => 'viewtopic_modify_post_row',
            'core.viewtopic_cache_user_data' => 'viewtopic_cache_user_data',
            'core.viewtopic_cache_guest_data' => 'viewtopic_cache_guest_data',
            'core.memberlist_view_profile' => 'memberlist_view_profile',
        );
    }

     public function viewtopic_modify_post_row($event)
     {
        $row = $event['row'];
	
	$event['post_row'] = array_merge($event['post_row'], array(
		'POSTER_GROUPS_ICONS' => $this->gererate_groupicons($row['user_id']),
	));
    }

    public function viewtopic_cache_user_data($event)
    {
        $array = $event['user_cache_data'];
        $array['group_id'] = $event['row']['group_id'];
        $event['user_cache_data'] = $array;
    }

    public function viewtopic_cache_guest_data($event)
    {
        $array = $event['user_cache_data'];
        $array['group_id'] = '';
        $event['user_cache_data'] = $array;
    }
    
    public function memberlist_view_profile($event)
    {
	$data = $event['member'];
	$groupicons = $this->gererate_groupicons($data['user_id']);
	
	$this->template->assign_vars(array(
		'GROUPS_ICONS'	=> $groupicons,
	));
    }
    
    protected function gererate_groupicons($user_id) 
    {

	$sql = 'SELECT g.group_id , g.group_name , g.group_groupicon_iconpath, g.group_type, gu.user_id 
		FROM ' . GROUPS_TABLE . ' g INNER JOIN ' . USER_GROUP_TABLE . ' gu 
			ON g.group_id = gu.group_id 
		WHERE (g.group_groupicon_iconpath <> NULL OR g.group_groupicon_iconpath <>  "") 
			AND g.group_type <> ' . GROUP_HIDDEN . ' 
			AND gu.user_id = ' . $user_id;

	$return_code = '';
        $result = $this->db->sql_query($sql);
        while ($row = $this->db->sql_fetchrow($result))
        {
            $group_name = ($row['group_type'] == GROUP_SPECIAL) ? $this->user->lang['G_' . $row['group_name']] : $row['group_name'];
            $return_code .= '<span title="' . $group_name . '" style="padding-right: 3px; padding-top: 3px; display: inline-block;">';
            if (isset($row['group_groupicon_grouptopic']) && $row['group_groupicon_grouptopic'] <> 0)
            {
                $return_code .= '<a href="/viewtopic.php?t=' . $row['group_groupicon_iconpath'] . '" target="_parent">';
                $return_code .= '<img width="48px" height="48px" src="' . $row['group_groupicon_iconpath'] . '">';
                $return_code .= '</a>';
            } 
            else
            {
                $return_code .= '<img width="48px" height="48px" src="' . $row['group_groupicon_iconpath'] . '">';
            }
            $return_code .= '</span>';
        }
        $this->db->sql_freeresult($result);

	return $return_code;
    }
    
}
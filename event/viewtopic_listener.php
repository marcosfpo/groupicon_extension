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

/**
 * @ignore
 */
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event listener
 */
class viewtopic_listener implements EventSubscriberInterface
{

    /** @var \phpbb\user */
    protected $user;

    /** @var \phpbb\db\driver\driver_interface */
    protected $db;
    
    /** @var \phpbb\template\template */
    protected $template;


    public function __construct(\phpbb\user $user, \phpbb\template\template $template, \phpbb\db\driver\driver_interface $db)
    {
        $this->user = $user;
        $this->db = $db;
        $this->template = $template;
    }

    /**
     * Assign functions defined in this class to event listeners in the core
     *
     * @return array
     * @static
     * @access public
     */
    static public function getSubscribedEvents()
    {
        return array(
            'core.viewtopic_modify_post_row' => 'viewtopic_modify_post_row',
            'core.viewtopic_cache_user_data' => 'viewtopic_cache_user_data',
            'core.viewtopic_cache_guest_data' => 'viewtopic_cache_guest_data',
        );
    }

    /**
     * Modify the viewtopic post row
     *
     * @param object $event The event object
     * @return null
     * @access public
     */
     public function viewtopic_modify_post_row($event)
     {
        $row = $event['row'];
        $user_cache = $event['user_poster_data'];
        
        $user_id = $row['user_id'];

	$sql = 'SELECT g.group_id , g.group_name , g.group_groupicon_iconpath, g.group_type, gu.user_id 
		FROM ' . GROUPS_TABLE . ' g INNER JOIN ' . USER_GROUP_TABLE . ' gu 
			ON g.group_id = gu.group_id 
		WHERE (g.group_groupicon_iconpath <> NULL OR g.group_groupicon_iconpath <>  "") 
			AND g.group_type <> ' . GROUP_HIDDEN . ' 
			AND gu.user_id = ' . $user_id;

	$groupsicons = array();
        $result = $this->db->sql_query($sql);
        while ($row = $this->db->sql_fetchrow($result))
        {
            $group_name = ($row['group_type'] == GROUP_SPECIAL) ? $this->user->lang['G_' . $row['group_name']] : $row['group_name'];
            $group_icon = $row['group_groupicon_iconpath'];
            $groupsicons[$group_name] = $group_icon; 
        }
        $this->db->sql_freeresult($result);

	$icons = $this->generate_icons($groupsicons);
	
	/*
	$this->template->assign_vars(array(
		'POSTER_GROUPS_ICONS'	=> $icons,
	));
	*/
	
	$event['post_row'] = array_merge($event['post_row'], array(
		'POSTER_GROUPS_ICONS' => $icons,
	));
    }

    /**
     * Update viewtopic user data
     *
     * @param object $event The event object
     * @return null
     * @access public
     */
    public function viewtopic_cache_user_data($event)
    {
        $array = $event['user_cache_data'];
        $array['group_id'] = $event['row']['group_id'];
        $event['user_cache_data'] = $array;
    }

    /**
     * Update viewtopic guest data
     *
     * @param object $event The event object
     * @return null
     * @access public
     */
    public function viewtopic_cache_guest_data($event)
    {
        $array = $event['user_cache_data'];
        $array['group_id'] = '';
        $event['user_cache_data'] = $array;
    }
    
    protected function generate_icons($groupsicons)
    {
    	$return = '';
    	foreach ($groupsicons as $key=>$val) 
    	{
    		$return .= '<span title="' . $key . '" style="padding-right: 3px; padding-top: 3px; display: inline-block;"><img width="48px" height="48px" src="' . $val . '"></span>';
    	}
    	
    	return $return;
    }

}
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

/**
 * Event listener
 */
class acp_listener implements EventSubscriberInterface
{

    /** @var \phpbb\request\request */
    protected $request;

    /** @var \phpbb\template\template */
    protected $template;
    
    protected $config;

    /**
     * Constructor
     *
     * @param \phpbb\request\request     $request    Request object
     * @param \phpbb\template\template   $template   Template object
     * @return \pico\reputation\event\acp_listener
     * @access public
     */
    public function __construct(\phpbb\request\request $request, \phpbb\template\template $template, \phpbb\log\log $log, \phpbb\user $user, \phpbb\config\config $config)
    {
        $this->request = $request;
        $this->template = $template;
        $this->config = $config;
    }

    static public function getSubscribedEvents()
    {
        return array(
            'core.user_setup' => 'load_language_on_setup',
            'core.acp_manage_group_initialise_data' => 'group_initialise_data',
            'core.acp_manage_group_request_data' => 'group_request_data',
            'core.acp_manage_group_display_form' => 'group_display_form',
        );
    }

    public function group_request_data($event)
    {
        $submit_ary = $event['submit_ary'];

        $submit_ary['groupicon_iconpath'] = $this->request->variable('group_groupicon_iconpath', '');
        $submit_ary['url'] = $this->request->variable('group_url', '');
        $event['submit_ary'] = $submit_ary;
    }

    public function group_display_form($event)
    {
        $group_row = $event['group_row'];

        $this->template->assign_vars(array(
            'GROUP_GROUPICON_ICONPATH' => (isset($group_row['group_groupicon_iconpath'])) ? $group_row['group_groupicon_iconpath'] : '',
            'GROUP_URL' => (isset($group_row['group_url'])) ? $group_row['group_url'] : '',
            'GROUPICON_VERSION' => $this->config['mfpo_groupicon_version'],
        ));
    }

    public function group_initialise_data($event)
    {
        $test_variables = $event['test_variables'];
        $test_variables['groupicon_iconpath'] = 'string';
        $test_variables['url'] = 'string';
        $event['test_variables'] = $test_variables;
    }

    public function load_language_on_setup($event)
    {
        $lang_set_ext = $event['lang_set_ext'];
        $lang_set_ext[] = array(
            'ext_name' => 'marcosfpo/groupicon',
            'lang_set' => 'groupicon_acp',
        );
        $event['lang_set_ext'] = $lang_set_ext;
    }

}
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

    /**
     * Constructor
     *
     * @param \phpbb\request\request     $request    Request object
     * @param \phpbb\template\template   $template   Template object
     * @return \pico\reputation\event\acp_listener
     * @access public
     */
    public function __construct(\phpbb\request\request $request, \phpbb\template\template $template)
    {
        $this->request = $request;
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
            'core.acp_manage_group_request_data' => 'group_request_data',
            'core.acp_manage_group_display_form' => 'group_display_form',
        );
    }

    /**
     * Add reputation group request data
     *
     * @param object $event The event object
     * @return null
     * @access public
     */
    public function group_request_data($event)
    {
        $submit_ary = $event['submit_ary'];
        $submit_ary['group_groupicon_iconpath'] = $this->request->variable('group_groupicon_iconpath', 0);
        $event['submit_ary'] = $submit_ary;
    }

    /**
     * Assign reputation data to group template
     *
     * @param object $event The event object
     * @return null
     * @access public
     */
    public function group_display_form($event)
    {
        $group_row = $event['group_row'];

        $this->template->assign_vars(array(
            'GROUP_ICON_PATH' => (isset($group_row['group_groupicon_iconpath'])) ? $group_row['group_groupicon_iconpath'] : 0,
        ));
    }

}

<?php

/**
 *
 * Group Icon
 *
 * @copyright (c) 2015 MarcosFPo
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace marcosfpo\groupicon\migrations\v10x;

/**
 * Migration stage 1: Initial schema
 */
class m1_initial_schema extends \phpbb\db\migration\migration
{

    /**
     * Assign migration file dependencies for this migration
     *
     * @return array Array of migration files
     * @static
     * @access public
     */
    static public function depends_on()
    {
        return array('\phpbb\db\migration\data\v310\gold');
    }

    public function update_schema()
    {
        return array(
            'add_columns' => array(
                $this->table_prefix . 'groups' => array(
                    'group_groupicon_iconpath' => array('VCHAR:255', ''),
                ),
            ),
        );
    }

    public function revert_schema()
    {
        return array(
            'drop_columns' => array(
                $this->table_prefix . 'groups' => array(
                    'group_groupicon_iconpath',
                ),
            ),
        );
    }

}
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

class schema_1_1_0 extends \phpbb\db\migration\migration
{

    static public function depends_on()
    {
        return array('\marcosfpo\groupicon\migrations\v10x\m1_initial_schema');
    }

    public function update_schema()
    {
        return array(
            'add_columns' => array(
                $this->table_prefix . 'groups' => array(
                    'group_groupicon_grouptopic' => array('INT', '0'),
                ),
            ),
        );
    }

    public function revert_schema()
    {
        return array(
            'drop_columns' => array(
                $this->table_prefix . 'groups' => array(
                    'group_groupicon_grouptopic',
                ),
            ),
        );
    }

}
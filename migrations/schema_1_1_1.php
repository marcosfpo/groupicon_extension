<?php

/**
*
* Group Icon
*
* @copyright (c) 2015 MarcosFPo
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace marcosfpo\groupicon\migrations;

class schema_1_1_1 extends \phpbb\db\migration\migration
{

    static public function depends_on()
    {
        return array('\marcosfpo\groupicon\migrations\schema_1_1_0');
    }
    
    public function update_data()
    {
	return array(
		// Add configs
		array('config.update', array('mfpo_groupicon_version', '1.1.1')),
	);
    }
}

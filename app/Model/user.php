<?php

class User extends AppModel {

    var $name = 'User';
    var $actsAs = array('Acl' => array('type' => 'requester'));
    var $belongsTo = array( 'Group' => array( 'className' => 'Group'));
    
    function parentNode() {
    	if (!$this->id && empty($this->data)) {
    		return null;
    	}
    	if (isset($this->data['User']['group_id'])) {
    		$groupId = $this->data['User']['group_id'];
    	} else {
    		$groupId = $this->field('group_id');
    	}
    	if (!$groupId) {
    		return null;
    	} else {
    		return array('Group' => array('id' => $groupId));
    	}
    }
}
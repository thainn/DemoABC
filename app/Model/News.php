<?php

class News extends AppModel {
    var $name = 'Recruit';
    var $belongsTo = array( 'User' => array( 'className' => 'User'));
}
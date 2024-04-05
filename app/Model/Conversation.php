<?php
App::uses('AppModel', 'Model');
/**
 * Conversation Model
 *
 */
class Conversation extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'conversationId' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'subject' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'userId_1' => array(
			'notBlank' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'userId_2' => array(
			'notBlank' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	public $belongsTo = array(
        'User1' => array(
            'className' => 'User',
            'foreignKey' => 'userId_1'
        ),
        'User2' => array(
            'className' => 'User',
            'foreignKey' => 'userId_2'
        ),
        // 'messages' => array(
        //     'className' => 'Message',
        //     'foreignKey' => 'conversationId'
        // )
    );
	
}

<?php
/**
 * Conversation Fixture
 */
class ConversationFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'conversation_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'subject' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'charset' => 'utf8mb4'),
		'started_by_user_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'created_at' => array('type' => 'timestamp', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'conversation_id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8mb4', 'collate' => 'utf8mb4_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'conversation_id' => 1,
			'subject' => 'Lorem ipsum dolor sit amet',
			'started_by_user_id' => 1,
			'created_at' => 1712024566
		),
	);

}

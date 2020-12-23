<?php

return [
    'action' => [
	'check_in_success' => ':count item(s) successfully checked-in.',
	'checked_out_item' => 'The ":name" item cannot be modified as it is currently checked out by a user.',
	'check_out_do_not_match' => 'The user checking out doesn\'t match the user who checked out the item. You are not permitted to use that link to directly access that page.',
    ],
    'messages' => [
	'required_field' => 'This field is required',
    ],
    'account' => [
	'template' => 'Template',
	'template_desc' => 'The template to display when the user is logged out.',
	'sharedFields' => 'Shared Fields',
	'sharedFields_desc' => '',
	'extraRegistrationFields_desc' => 'The name of a partial which provides extra registration fields on the behalf of another plugin. Only used with the register template.',
    ]
];

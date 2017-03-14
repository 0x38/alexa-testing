<?php

require_once('../alexa.func.php');
require_once('seizure.users.php');
require_once('seizure.events.php');

// Get the input from Alexa and JSON-decode it
$input = json_decode(file_get_contents("php://input"));

// Continue with finding the user and handling intent assuming we have somewhat valid input
if ( (isset($input->session->user->userId)) && (!empty($input->session->user->userId)) && (isset($input->request->intent)) && (isset($input->request->intent->name)) ) {

	// Set MySQL database credentials and connect to MySQL
	$db_hostname = 'localhost';
	$db_username = $db_database = 'seizuretest';
	require_once('.seizure.dbpassword.php');
	$db_link = new PDO("mysql:host=$db_hostname;dbname=$db_database", $db_username, $db_password);

	// Get user ID using Alexa ID
	$user_id = get_user($input->session->user->userId, $db_link);

	// Continue if user ID was found
	if (is_numeric($user_id)) {

		// Handle the event based on the intent sent from Alexa
		$handle_seizure = handle_seizure($db_link, $user_id, $input->request->intent);

		// Set the message awkwardly
		// (TODO: find a better way of doing this)
		if ( (isset($handle_seizure)) && (is_string($handle_seizure)) ) {
			$message = $handle_seizure;

		// Otherwise there was an error adding or finding/updating the seizure
		} else {
			$message = 'Sorry. There was an unknown error.';
		}

	// Otherwise, there was an error finding or adding the user
	} else {
		$message = "Sorry. There was an error with your user account.";
	}

	// Disconnect from MySQL
	$db_link = null;

// Otherwise, invalid input gets a default set of instructions for using this Alexa Skill
} else {
	$message = 'Please say, "Tell SeizureTracker to track a seizure", if you would like to track a seizure.';
}

// The output is always JSON, return it!
header('Content-Type: application/json;charset=UTF-8');
$out = AlexaOut($message, 'SeizureTest', $message);
echo "$out\n";

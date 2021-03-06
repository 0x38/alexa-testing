<?php

// Create an array of arrays of all the games ($games!)
$games = array(

	'20170312' => array(
		'opponent' => 'Los Angeles Lakers',
		'when' => '20170312T213000',
		'stadium' => 'Staples Center',
		'city' => 'Los Angeles',
		'state' => 'CA'
	),

	'20170314' => array(
		'opponent' => 'Golden State Warriors',
		'when' => '20170314T223000',
		'stadium' => 'Oracle Arena',
		'city' => 'Oakland',
		'state' => 'CA'
	),

	'20170317' => array(
		'opponent' => 'Dallas Mavericks',
		'when' => '20170317T190000',
		'stadium' => 'Wells Fargo Center',
		'city' => 'Philadelphia',
		'state' => 'PA'
	),

	'20170319' => array(
		'opponent' => 'Boston Celtics',
		'when' => '20170319T130000',
		'stadium' => 'Wells Fargo Center',
		'city' => 'Philadelphia',
		'state' => 'PA'
	),

	'20170320' => array(
		'opponent' => 'Orlando Magic',
		'when' => '20170320T190000',
		'stadium' => 'Amway Center',
		'city' => 'Orlando',
		'state' => 'FL'
	),

	'20170322' => array(
		'opponent' => 'Oklahoma City Thunder',
		'when' => '20170322T200000',
		'stadium' => 'Chesapeake Energy Arena',
		'city' => 'Oklahoma City',
		'state' => 'OK'
	),

	'20170324' => array(
		'opponent' => 'Chicago Bulls',
		'when' => '20170324T200000',
		'stadium' => 'United Center',
		'city' => 'Chicago',
		'state' => 'IL'
	),

	'20170326' => array(
		'opponent' => 'Indiana Pacers',
		'when' => '20170326T180000',
		'stadium' => 'Bankers Life Fieldhouse',
		'city' => 'Indianapolis',
		'state' => 'IN'
	),

	'20170328' => array(
		'opponent' => 'Brooklyn Nets',
		'when' => '20170328T193000',
		'stadium' => 'Barclays Center',
		'city' => 'Brooklyn',
		'state' => 'NY'
	),

	'20170329' => array(
		'opponent' => 'Atlanta Hawks',
		'when' => '20170329T190000',
		'stadium' => 'Wells Fargo Center',
		'city' => 'Philadelphia',
		'state' => 'PA'
	),

	'20170331' => array(
		'opponent' => 'Cleveland Cavaliers',
		'when' => '20170331T193000',
		'stadium' => 'Quicken Loans Arena',
		'city' => 'Cleveland',
		'state' => 'OH'
	),

	'20170402' => array(
		'opponent' => 'Toronto Raptors',
		'when' => '20170402T180000',
		'stadium' => 'Air Canada Centre',
		'city' => 'Toronto',
		'state' => 'ON'
	),

	'20170404' => array(
		'opponent' => 'Brooklyn Nets',
		'when' => '20170404T190000',
		'stadium' => 'Wells Fargo Center',
		'city' => 'Philadelphia',
		'state' => 'PA'
	),

	'20170406' => array(
		'opponent' => 'Chicago Bulls',
		'when' => '20170406T190000',
		'stadium' => 'Wells Fargo Center',
		'city' => 'Philadelphia',
		'state' => 'PA'
	),

	'20170408' => array(
		'opponent' => 'Milwaukee Bucks',
		'when' => '20170408T190000',
		'stadium' => 'Wells Fargo Center',
		'city' => 'Philadelphia',
		'state' => 'PA'
	),

	'20170410' => array(
		'opponent' => 'Indiana Pacers',
		'when' => '20170410T190000',
		'stadium' => 'Wells Fargo Center',
		'city' => 'Philadelphia',
		'state' => 'PA'
	),

	'20170412' => array(
		'opponent' => 'New York Knicks',
		'when' => '20170412T200000',
		'stadium' => 'Madison Square Garden',
		'city' => 'New York',
		'state' => 'NY'
	)
);

// Define a function that will create a friendly phrase to describe a game
function createPhrase ($game) {

	// Start a friendly phrase to respond with
	$phrase = 'The ';

	// Change the phrasing so that the home stadium is always referenced last
	if ($game['city'] === 'Philadelphia') {
		$phrase .= $game['opponent'] . ' are playing the Philadelphia 76ers';
	} else {
		$phrase .= 'Philadelphia 76ers are playing the ' . $game['opponent'];
	}

	// Include the time, date, stadium, city, and state at the end of the friendly phrase
	$when = strtotime($game['when']);
	$phrase .= ' at ' . date('g:i A', $when) . ' on ' . date('l, F jS', $when);
	$phrase .= ' at ' . $game['stadium'] . ' in ' . $game['city'] . ', ' . $game['state'];

	// Return the phrase string that was built!
	return $phrase;
}

// Define a function to create JSON for Alexa Flash Briefing skills
function BriefingOut ($uid, $update_date, $title_text, $main_text, $redirect_url = null) {

	// Create the short array to return for Flash Briefing skills, JSON encode it, and return it
	$final = array('uid' => $uid, 'updateDate' => $update_date, 'titleText' => $title_text, 'mainText' => $main_text, 'redirectUrl' => null);
	$out = json_encode($final, JSON_PRETTY_PRINT);
	return $out;
}

// Get todays date as YYYYMMDD for checking the games array
$today = date('Ymd');

// Get the date in ISO format, generate a UID, and set the title for Alexa
$update_date = date('c', strtotime($today));
$uid = uniqid('nba-phila-76ers-', true);
$title = 'Philadelphia 76ers Games (Flash Briefing)';

// Continue if there is a game today
if ( (isset($games["$today"])) && (!empty($games["$today"])) ) {

	// Create a friendly phrase with the details of the game today
	$phrase = createPhrase($games["$today"]);

// If there is no game today, set the phrase accordingly
} else {
	$phrase = 'Unfortunately, the Philadelphia 76ers are not playing today.';
}

// Finally, generate and return the JSON
header('Content-Type: application/json;charset=UTF-8');
$out = BriefingOut($uid, $update_date, $title, $phrase);
echo $out;

<?php
function playWar($shuffled){
	// echo "<p>prewar cards: ". count($shuffled);
	$dealer = array_pop($shuffled);
	$dealer = array_pop($shuffled);
	$dealer = array_pop($shuffled);
	$dealer = array_pop($shuffled);
	$player = array_pop($shuffled);
	$player = array_pop($shuffled);
	$player = array_pop($shuffled);
	$player = array_pop($shuffled);
	$warcards = array('dealer' => $dealer, 'player' => $player);
	// echo "<p>postwar cards: ". count($shuffled);
	return $warcards;
}

function processOutcomes($dealer, $round, $scores, $wins, $hand){
	echo "<p>---------------- DEALER CARD: $dealer ----------------------------------------------------------------</p>";

	$cards = "";
	foreach($round as $player => $card){
		$cards .= strtoupper($player) .": $card | ";
	}
	$cards = substr_replace($cards,"",-2);
	echo "<p>-------- PLAYER CARDS: $cards</p>";

	foreach($round as $player => $card){
		$score = $scores['player'];
		if($dealer > $card){
			$wins['dealer'] = $wins['dealer'] + 1;
			$scores[$player] = $wins[$player] / $hand;			
			$scores['dealer'] = $wins['dealer'] / $hand;
			echo "<p>DEALER WINS. BEATS PLAYER $player --- ". $wins['dealer']. " | overall: ". $scores['dealer']."</p>";
			// print_r($wins);
		}
		if($dealer < $card){
			$wins[$player] = $wins[$player] + 1;
			$scores[$player] = $wins[$player] / $hand;	
			$scores['dealer'] = $wins['dealer'] / $hand;		
			echo "<p>PLAYER $player WINS --- ". $wins[$player]. " | overall: ".$scores[$player]."</p>";
			// print_r($wins);
		}
		if($dealer == $card){
			$scores[$player] = $wins[$player] / $hand;			
			$scores['dealer'] = $wins['dealer'] / $hand;			
			echo "<p>****************************** WARRRRRR! ****************************** </p>";
			$status = $player;
		}
	}

	$result = array('status' => $status, 'wins' => $wins, 'scores' => $scores);
	return $result;
}

//starting scores
$scores = array('dealer' => 0, 'a' => 0, 'b' => 0, 'c' => 0, 'd' => 0, 'e' => 0, 'f' => 0);
$wins = array('dealer' => 0, 'a' => 0, 'b' => 0, 'c' => 0, 'd' => 0, 'e' => 0, 'f' => 0);
$hand = 1;

//5 decks of cards
$decks = array(2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,9,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,11,11,11,11,11,11,11,11,11,11,11,11,11,11,11,11,11,11,11,11,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14);

//echo count($decks);

//shuffle the cards
shuffle($decks);

foreach($decks as $card){
	$shuffled[] = $card;
}

//cut the deck
$decksize = count($decks);
$cut = rand(0, $decksize);
$shuffled = array_slice($shuffled, 0, $cut, true);

//show the cut deck:
// echo count($shuffled);
// print_r($shuffled);

//customarily kill the top card:
$killcard = array_pop($shuffled);

while(!empty($shuffled)){

//deal the cards
	$dealer = array_pop($shuffled);
	$a = array_pop($shuffled);	
	$b = array_pop($shuffled);
	$c = array_pop($shuffled);
	$d = array_pop($shuffled);
	$e = array_pop($shuffled);
	$f = array_pop($shuffled);

	//process these cards
	$round = array('a' => $a, 'b' => $b, 'c' => $c, 'd' => $d, 'e' => $e, 'f' => $f);
	$outcome = processOutcomes($dealer, $round, $scores, $wins, $hand);

	$wins = $outcome['wins'];
	$scores = $outcome['scores'];
	
	//there's a war!
	if(!empty($outcome['status'])){
		$war = playWar($shuffled);
		$dealer = $war['dealer'];
		$card = $war['player'];
		$player = $outcome['status'];
		$round = array($player => $card);
		$outcome = processOutcomes($dealer, $round, $scores, $wins, $hand);
		$wins = $outcome['wins'];
		$scores = $outcome['scores'];
	}

	$hand++;
}
?>
<h2>FINAL OUTCOME:</h2>
<?php
//ok so what happened?
foreach($wins as $player => $win){
	if($player == 'dealer'){$finalhand = $hand * 6;}else{$finalhand = $hand;}
	echo "<h4>$player: ".$win / $finalhand. "</h4>";
}
?>
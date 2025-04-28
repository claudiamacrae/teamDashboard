<?php

$players = range(1, 60); // Player IDs from 1 to 60
$games = range(1, 18);   // Game IDs from 1 to 18

$rankings = [];

foreach ($games as $game_id) {
    // Randomly decide how many players participated (between 45 and 55)
    $num_players = rand(45, 55);
    
    // Randomly select players for this game
    $players_in_game = $players;
    shuffle($players_in_game);
    $players_in_game = array_slice($players_in_game, 0, $num_players);
    
    // Shuffle again to randomize the ranking
    shuffle($players_in_game);
    
    foreach ($players_in_game as $rank => $player_id) {
        $rankings[] = [
            'player_id' => $player_id,
            'game_id' => $game_id,
            'rank' => $rank + 1 // Ranks start at 1
        ];
    }
}

foreach ($rankings as $r) {
    $stmt = $pdo->prepare("INSERT INTO `player_game_ranks` (`player_id`, `game_id`, `rank`) VALUES (?, ?, ?)");

    foreach ($rankings as $r) {
        $stmt->execute([
            $r['player_id'],
            $r['game_id'],
            $r['rank']
        ]);
    }
    
}
?>

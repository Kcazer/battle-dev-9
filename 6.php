<?php

$users = (int)fgets(STDIN);
$count = (int)fgets(STDIN);

$trades = [];
$givers = [];
$takers = [];
$output = array_fill(1, $users, 0);
while ($count--) {
    $trade = explode(' ', fgets(STDIN));
    $giver = (int)$trade[0];
    $taker = (int)$trade[1];
    $givers[] = $giver;
    if (!isset($trades[$giver])) $trades[$giver] = [];
    $trades[$giver][] = $taker;
}
$givers = array_unique($givers);

function findTaker($giver, $endLoopOn)
{
    global $trades, $takers, $givers, $output;
    $choices = $trades[$giver];
    // Ends loop if possible
    if (!is_array($choices)) return 0;
    if (in_array($endLoopOn, $choices)) return $endLoopOn;
    // Filter out ppl who already received
    $choices = array_filter($choices, function ($choice) use ($takers) {
        return !isset($takers[$choice]);
    });
    return count($choices) ? reset($choices) : 0;
}

function buildLoop()
{
    global $trades, $takers, $givers, $output;
    $root = $giver = reset($givers);
    while (true) {
        $key = array_search($giver, $givers);
        $taker = findTaker($giver, $root);
        unset($givers[$key]);
//        echo "$giver -> $taker\n";
//        echo 'Can give : '.implode(',', ($givers))."\n";
//        echo 'Can take : '.implode(',', array_keys($takers))."\n\n";
        $output[$giver] = $taker;
        $takers[$taker] = true;
//        print_r($output);
//        sleep(1);
        if ($taker == 0 || $taker == $root) break;
        $giver = $taker;
    }
}

while (count($givers)) buildLoop();

echo implode(' ', $output);

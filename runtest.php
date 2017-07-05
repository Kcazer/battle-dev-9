<?php

// Test ID
$id = $_SERVER['argv'][1] ?? die('Invalid script ID');

// Script
file_exists($script = "{$id}.php") or die('Invalid script <{$script}>');

// Load input and output
$tests = [];
$inputs = glob("tests/{$id}/input*.txt");
$outputs = glob("tests/{$id}/output*.txt");
foreach ($inputs as $input) {
    $test = (int)substr($input, strlen("tests/{$id}/input"), -4);
    $input = "tests/{$id}/input{$test}.txt";
    $output = "tests/{$id}/output{$test}.txt";
    if (!in_array($input, $inputs)) continue;
    if (!in_array($output, $outputs)) continue;
    $tests[$input] = $output;
}

// Run each test
$cmd = escapeshellcmd(PHP_BINARY);
foreach ($tests as $input => $output) {
    $output = trim(file_get_contents($output));
    $return = trim(shell_exec("{$cmd} {$script} < {$input}"));
    $result = ($return === $output) ? 'PASSED' : 'FAILED';
    echo "{$input} : {$result}\n";
    echo "\t$return || $output\n";
}

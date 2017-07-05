<?php

$step = 0;
$maze = [];
$size = $loop = (int)trim(fgets(STDIN));
while ($loop--) $maze[] = str_split(trim(fgets(STDIN)));

// Find the pill
$queue = new SplQueue();
for ($y = 0; $y < $size; $y++) {
    for ($x = 0; $x < $size; $x++) {
        if ($maze[$y][$x] === 'O') {
            $queue->enqueue([$y, $x]);
            break 2;
        }
    }
}

// Process queue
//DrawImage();
while (count($queue)) {
    $win = false;
    $step = $step + 1;
    $next = new SplQueue();
    while (count($queue)) {
        // Dequeue position
        list($y, $x) = $queue->dequeue();
//        DrawImage([$y, $x]);
        // Compute neighbours
        $neighbours = [
            [$y, ($x + 1) % $size],
            [($y + 1) % $size, $x],
            [$y, ($x - 1 + $size) % $size],
            [($y - 1 + $size) % $size, $x],
        ];
        // Process neighbours
        foreach ($neighbours as $pos) {
            if ($maze[$pos[0]][$pos[1]] == 'M') die('0');
            if ($maze[$pos[0]][$pos[1]] == 'C') $win = true;
            if ($maze[$pos[0]][$pos[1]] == '.') {
                $maze[$pos[0]][$pos[1]] = 'O';
                $next->enqueue($pos);
            }
        }
    }
    if ($win) die("$step");
    $queue = $next;
}
die('0');


function DrawImage($pos = null, $color = 'O')
{
    global $size;
    global $maze;
    static $count = 0;
    static $image = null;
    static $colors = ['#' => 0x000000, 'C' => 0xEEEE00, 'M' => 0xDD0000, 'O' => 0x00AA00, '.' => 0xFFFFFF];
    // Initialize image
    if ($image === null) {
        $image = imagecreatetruecolor($size, $size);
        for ($y = 0; $y < $size; $y++) {
            for ($x = 0; $x < $size; $x++) {
                imagesetpixel($image, $x, $y, $colors[$maze[$y][$x]] ?? 0xAAAAFF);
            }
        }
    }
    // Draw the new dot
    if ($pos !== null) imagesetpixel($image, $pos[1], $pos[0], $colors[$color] ?? 0xAAAAFF);
    // Write picture on disk
    $output = 'out/' . str_pad($count, 8, '0', STR_PAD_LEFT) . '.png';
    if (!file_exists($output)) imagepng($image, $output);
    $count += 1;
}

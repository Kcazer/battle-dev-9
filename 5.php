<?php

$step = 0;
$maze = [];
$size = $loop = (int)trim(fgets(STDIN));
while ($loop--) $maze[] = str_split(trim(fgets(STDIN)));

while (true) {
    $step += 1;
    $temp = $maze;
    $fill = false;
    for ($y = 0; $y < $size; $y++) {
        $yd = ($y + 1) % $size;
        $yu = ($y - 1 + $size) % $size;
        for ($x = 0; $x < $size; $x++) {
            $xr = ($x + 1) % $size;
            $xl = ($x - 1 + $size) % $size;
            // Check win/lose
            if ($maze[$y][$x] == 'O') {
                if ($maze[$y][$xl] == 'M' || $maze[$y][$xr] == 'M' || $maze[$yu][$x] == 'M' || $maze[$yd][$x] == 'M') die("0");
                if ($maze[$y][$xl] == 'C' || $maze[$y][$xr] == 'C' || $maze[$yu][$x] == 'C' || $maze[$yd][$x] == 'C') die("$step");
            } // Fill empty space
            elseif ($maze[$y][$x] == '.') {
                if ($maze[$y][$xl] == 'M' || $maze[$y][$xr] == 'M' || $maze[$yu][$x] == 'M' || $maze[$yd][$x] == 'M') $temp[$y][$x] = 'M';
                elseif ($maze[$y][$xl] == 'C' || $maze[$y][$xr] == 'C' || $maze[$yu][$x] == 'C' || $maze[$yd][$x] == 'C') $temp[$y][$x] = 'C';
                $fill = $fill || $temp[$y][$x] == 'M' || $temp[$y][$x] == 'C';
            }
        }
    }
    // Impossible maze
    if (!$fill) die("0");
    $maze = $temp;
}

// Debug function to display the maze at any given step
function DumpAsText($maze, $step)
{
    echo "\nStep {$step}";
    foreach ($maze as $line) echo "\n\t" . implode('', $line);
    echo "\n";
}

// Same but dump as image on the disk
function DumpAsImage($maze, $step)
{
    $size = count($maze);
    $image = imagecreatetruecolor($size, $size);
    $output = 'img' . str_pad($step, 3, '0', STR_PAD_LEFT) . '.png';
    $colors = ['#' => 0x000000, 'C' => 0xEEEE00, 'M' => 0xDD0000, 'O' => 0x00AA00, '.' => 0xFFFFFF];
    for ($y = 0; $y < $size; $y++) {
        for ($x = 0; $x < $size; $x++) {
            $color = $colors[$maze[$y][$x]] ?? 0xFFFFFF;
            imagesetpixel($image, $x, $y, $color);
        }
    }
    imagepng($image, $output);
}

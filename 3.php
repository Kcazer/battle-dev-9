<?php

$size = (int)trim(fgets(STDIN));
$level = trim(fgets(STDIN));

echo 1 + max(array_map('strlen', explode('-', $level)));


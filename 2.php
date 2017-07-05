<?php

$years = [];
$count = (int)trim(fgets(STDIN));
while ($count--) $years[] = (int)trim(fgets(STDIN));

echo max($years) - min($years);

<?php

function benchmarkArrayTraversal($arr, $loopType)
{
    $start = 0;

    switch($loopType) {
        case 'for':
            $start = microtime(true);
            $len = count($arr);

            for ($i = 0; $i < $len; ++$i) {
                $arr[$i] == true;
            }

            break;
        case 'foreach':
            $start = microtime(true);

            foreach($arr as $key => $value) {
                $value == true;
            }

            break;
        case 'while':
            $start = microtime(true);
            $len = count($arr);
            $i = 0;

            while($i < $len) {
                $arr[$i++] == true;
            }

            break;
        default:
            throw new InvalidArgumentException();
    }

    $end = microtime(true);

    return $end - $start;
}

$nums = array_fill(0, '1e6', 'test');

echo 'For: ' . benchmarkArrayTraversal($nums, 'for') . '<br>';
echo 'ForEach: ' . benchmarkArrayTraversal($nums, 'foreach') . '<br>';
echo 'While: ' . benchmarkArrayTraversal($nums, 'while') . '<br>';
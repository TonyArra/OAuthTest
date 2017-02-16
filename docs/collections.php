<?php

/**
 * Analyze an array of numbers and return an associative array with the following key-value pairs:
 * mean, median, mode, & standardDeviation
 *
 * @param array $nums
 * @return array
 */
function analyzeArray(array $nums) : array
{
    $collection = collect($nums);

    return [
        'mean' => $collection->avg(),
        'median' => $collection->median(),
        'mode' => $collection->mode(),
        'standardDeviation' => standardDeviation($collection),
    ];
}

/**
 * Calculate the (population) standard deviation from a Collection of numbers
 *
 * @param \Illuminate\Support\Collection $nums
 * @return float
 */
function standardDeviation(Illuminate\Support\Collection $nums) : float
{
    $mean = $nums->avg();

    $squaredMean = $nums->map(function($num) use ($mean) {
        return pow($num - $mean, 2);
    })->avg();

    return sqrt($squaredMean);
}

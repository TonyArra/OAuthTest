###Invest Forward - API Developer Test Section 1

1. === vs ==  
  * ===
    * When applied to literals, === checks for both equal value and type.
      e.g., ('1' === 1) is false, but  (1 === 1) is true
    * When applied to an array, === checks that the key-value pairs of both arrays are of equal value and type.
    * When applied to an object, === checks that the object's handle is the same.  
      e.g., === applied to two cloned objects would be false, but == would be true  

  * == 
    * When applied to literals, == checks for equal value after type juggling.  
    e.g., both ('1' == 1) and (1 == 1) are true
    * When applied to an array, == checks that the key-value pairs of both arrays are of equal value after type juggling.
    * When applied to an object, == checks that the objects contents are the same (with type juggling).

  * As far as I'm concerned, there is very little justification in ever using ==. It's almost always going to be better to
test for both value AND type, even if it requires converting some input to another type. A common use case for == is
checking that a $_GET property is equal to a certain integer value. All $_GET properties are treated as strings.

2. In the code sample, `continue` skips over the `print` statement when `$i == 2`.
   The loop will print, `0 , 1 , 3 , 4 , `

3. The fastest way to perform array traversal is with a forEach loop. 
My benchmark tests showed it to be about 16% faster than for and while, even when count() was factored out.
This is most likely because foreach is optimized for array traversal in the C implementation of the function.


```php
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
```
> The benchmarks are as follows (in microseconds):  
>
> For: 0.02454400062561  
> ForEach: 0.020641088485718  
> While: 0.024683952331543  
> 
> For and While take about the same amount of time, but ForEach is about 16% faster.  

4. 
```php
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
```
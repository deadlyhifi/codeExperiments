<?php
/**
 * Remove promotions where the remove key matches AND any of the remove values match.
 */

function filterPromotions($promotions, $remove)
{
    $promotions = array_filter(
        $promotions,
        function ($promo) use ($remove) {
            foreach ($promo as $promoTagKey => $promoTagValues) {
                foreach ($remove as $removeTagKey => $removeTagValues) {
                    if ($removeTagKey === $promoTagKey
                        && !empty(array_intersect($removeTagValues, $promoTagValues))
                    ) {
                        return null;
                    }
                }
            }

            return $promo;
        }
    );

    return array_values($promotions);
}

////////////////////////////////////////////////////////////////////////////////
$promotions = [
    [ // 0
        'Farrakh' => ['house', 'car', 'fence'],
        'Bat' => ['man'],
    ],
    [ // 1
        'Non' => ['house'],
        'Bon' => ['man'],
    ],
    [ // 2
        'Farrakh' => ['bike'],
        'Bat' => ['man'],
    ],
    [ // 3
        'Tom' => ['mouse', 'hat'],
        'Bat' => ['man'],
    ],
    [ // 4
        'Tom' => ['louse'],
        'Bat' => ['man'],
    ],
    [ // 5
        'Bug' => ['louse'],
        'Bug' => ['man'],
    ],
];

// Tests ///////////////////////////////////////////////////////////////////////
function test($promotions, $expected, $remove)
{
    $result = filterPromotions($promotions, $remove);
    if ($expected === $result) {
        echo '✩ PASSED' . PHP_EOL;
    } else {
        echo '☠ BORKEN' . PHP_EOL;
        var_dump('RESULT~~~~~~~~~~~', $result, 'EXPECTED~~~~~~~~~~', $expected);
    }
}

// Test One
$remove1 = [
    'Farrakh' => ['house', 'car'],
    'Tom' => ['mouse', 'cat'],
    'Bug' => ['head'],
];
$expected1 = [$promotions[1], $promotions[2], $promotions[4], $promotions[5]];
test($promotions, $expected1, $remove1);

// Test Two
$remove2 = [
    'Farrakh' => ['house', 'car'],
    'Tom' => ['mouse', 'cat'],
    'Bug' => ['man'],
];
$expected2 = [$promotions[1], $promotions[2], $promotions[4]];
test($promotions, $expected2, $remove2);

// Test Three
$remove3 = [
    'Farrakh' => ['house', 'car'],
];
$expected3 = [$promotions[1], $promotions[2], $promotions[3], $promotions[4], $promotions[5]];
test($promotions, $expected3, $remove3);

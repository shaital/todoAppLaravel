<?php

echo "<h3>PART 1:</h3>";

/*
 * Instructions:
 * ------------------
 * Implement the unique_names function.
 * When passed two arrays of names, it will return an array containing the names that appear in either or both arrays.
 * The returned array should have no duplicates.
 * For example, calling unique_names(['Ava', 'Emma', 'Olivia'], ['Olivia', 'Sophia', 'Emma']) should return ['Emma', 'Olivia', 'Ava', 'Sophia'] in any order.
 */

function unique_names(array $array1, array $array2): array {
  return array_unique (array_merge ($array1, $array2));
}

$names = unique_names(['Ava', 'Emma', 'Olivia'], ['Olivia', 'Sophia', 'Emma']);
echo join(', ', $names); // should print Emma, Olivia, Ava, Sophia



/**************************************************************************/
/*****************************     part 2 *********************************/
/**************************************************************************/

echo "<hr/><h3>PART 2:</h3>";

/**
 * Instructions:
 * -----------------
 * Implement the function findRoots to find the roots of the quadratic equation: ax2 + bx + c = 0. 
 * The function should return an array containing both roots in any order. 
 * If the equation has only one solution, the function should return that solution as both elements of the array. 
 * The equation will always have at least one solution.
 * The roots of the quadratic equation can be found with formula in attached file: "formula.png" :
 *  
 * For example, findRoots(2, 10, 8) should return [-1, -4] or [-4, -1] as the roots of the equation 2x2 + 10x + 8 = 0 are -1 and -4.\n                "
 * @return array An array of two elements containing roots in any order
 */
function findRoots($a, $b, $c)
{
    $root = @sqrt(pow($b,2) - 4*$a*$c);
    $result_plus = @(-$b + $root)/ (2*$a);
    $result_minus = @(-$b - $root)/ (2*$a);
    return [$result_plus,$result_minus];
}

print_r(findRoots(2, 10, 8));


<?php
$array = [ array('a'=>1, 'b'=>2), array('c'=>3, 'd'=>4)];
echo json_encode($array)."\n";

$array2 = [
    'item_id1' => [ 10000, 3 ],
    'item_id2' => [ 11000, 4 ]
];

$orders = array(
    ['item_id1', 10000, 3 ],
    ['item_id2', 11000, 4 ]
);

echo current($array2)[1] . "\n";

echo $orders[0][0]."\n";

?>

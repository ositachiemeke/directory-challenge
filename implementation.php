<?php

$manager = new DirectoryManager();

$manager->create('fruits');
$manager->create('vegetables');
$manager->create('grains');
$manager->create('fruits/apples');
$manager->create('fruits/apples/fuji');

echo "LIST\n";
$manager->list();

$manager->create('grains/squash');
$manager->move('grains/squash', 'vegetables');
$manager->create('foods');
$manager->move('grains', 'foods');
$manager->move('fruits', 'foods');
$manager->move('vegetables', 'foods');

echo "LIST\n";
$manager->list();

$manager->delete('fruits/apples');
$manager->delete('foods/fruits/apples');

echo "LIST\n";
$manager->list();

?>
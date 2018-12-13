<?php
require('SubClasses.php');

$account1 = new ISA; // instance of ISA class

$account1->apr = 5.0;
$account1->sortCode = "20-20-20";
$account1->firstName = "Hannes";
$account1->lastName = "Empson";
$account1->additionalServices = "holiday package"; //"Holiday insurance"?

// echo serialize($account1);

$account1->deposit(1000);
$account1->withdraw(200);
$account1->withdraw(300);
$account1->deposit(1000);

// var_dump($account1);
echo json_encode($account1);



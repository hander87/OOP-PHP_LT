<?php
require('SubClasses.php');

// ISA ACCOUNT

$account1 = new ISA; // instance of ISA class

$account1->apr = 5.0;
$account1->sortCode = "20-20-20";
$account1->firstName = "Hannes";
$account1->lastName = "Empson";
$account1->additionalServices = "holiday package"; //"Holiday insurance"?

$account1->deposit(1000);
$account1->lock();
$account1->withdraw(200);
$account1->unlock();
array_push( $account1->audit, array( "(Manual) Withdraw accepted!", 200, 800, "2018-12-23T22:53:03+00:00" ) );
$account1->withdraw(300);

// SAVINGS ACCOUNT

$account2 = new SavingsAccount; // instance of ISA class

$account2->apr = 12.0;
$account2->sortCode = "20-50-20";
$account2->firstName = "Emma";
$account2->lastName = "Ansson";
$account2->package = "Cartoon insurance";

$account2->deposit(500);
$account2->lock();
$account2->withdraw(100);
$account2->unlock();
$account2->withdraw(300);
$account2->addedBonus();
$account2->orderNewDepositBook();
$account2->orderNewPocketBook();

// TODO: 5/24 - 15:50 - DEBIT ACCOUNT


// var_dump($account1);
// echo json_encode($account1);
echo json_encode($account2);



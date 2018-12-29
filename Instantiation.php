<?php
require('SubClasses.php');

// ISA ACCOUNT

// instance of ISA class
// Instanciation with call to SuperClass constructor
$account1 = new ISA( 35, 'Coool package', 5.0, '20-20-20', 'Hannes', 'Empson', 1337, true );

// PHP allows creation of unassigned properties in instanciation.
$account1->doesNotExist = 'Requives default value though';

// $account1->additionalServices = "holiday package"; //"Holiday insurance"?

$account1->deposit(1000);
$account1->lock();
$account1->withdraw(200);
$account1->unlock();
array_push( $account1->audit, array( "(Manual) Withdraw accepted!", 200, 800, "2018-12-23T22:53:03+00:00" ) );
$account1->withdraw(300);

// SAVINGS ACCOUNT
$account2 = new SavingsAccount( 50, 'Cartoon insurance', 12.0, "20-50-20", "Emma", "Ansson" );

$account2->deposit(500);
$account2->lock();
$account2->withdraw(100);
$account2->unlock();
$account2->withdraw(300);
// $account2->addedBonus();
$account2->orderNewDepositBook();
$account2->orderNewPocketBook();

// DEBIT ACCOUNT

$account3 = new DebitAccount( 30, '007 insurance', 1234, 1.0, "20-80-20", "James", "Bond" ); // instance of DEBIT class

$account3->deposit(1500);
$account3->lock();
$account3->withdraw(1200);
$account3->unlock();
$account3->withdraw(300);
// $account3->addedBonus();


// Echoing

// var_dump($account3); // var_dump/print_r allows to output private properties

// Accessing const/static values in Classes from instanciation with ::
echo $account1::INFO;
echo '<hr>';
echo $account1::$stat;
echo '<hr>';
echo $account1::stat();

// Array

$accountList = array( $account1, $account2, $account3  );

foreach( $accountList as $account ) {
    $print = $account->firstName;

    if ($account instanceof AccountPlus) {
        $print .= " has addedBonus()";
    }
    if ($account instanceof Savers) {
        $print .= "and has orderNewPocketBook() + orderNewDepositBook()";
    }

    // echo $print. '<br>';
}

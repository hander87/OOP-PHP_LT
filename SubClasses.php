<?php

require('BankAccount.php'); // require will shut down app if NOT FOUND - unlike include()

class ISA extends BankAccount {

    // Properties
    private $timePeriod;
    public $additionalServices;

    // Constructor
    // Invokes SuperClass constructor
    public function __construct ($time, $service, $apr, $sc, $fn, $ln, $bal = 0, $lock = false ) {
        
        $this->timePeriod = $time;
        $this->additionalServices = $service;
        // echo 'constructor fired!';

        // Passes values to SuperClass constructor
        parent::__construct( $apr, $sc, $fn, $ln, $bal, $lock );

    }

    // Methods
    public function withdraw( $amount ) {
        $transDate = new DateTime();
        $lastTransaction = null;
        $length = count($this->audit);

        for ( $i = $length; $i > 0; $i-- ) {
            
            $element = $this->audit[$i - 1];

            // Calculates latest transaction
            if ( $element[0] === "Withdraw accepted!" ) {
                $days = new DateTime( $element[3] );

                // diff() gets difference between values
                // format("%a") produces a number
                $lastTransaction = $days->diff($transDate)->format("%a");
                break;
            }
        }
        if ( 
            $lastTransaction === null && $this->locked === false || 
            $this->locked === false && $lastTransaction > $this->timePeriod
        ) {
            $this->balance -= $amount;
            array_push( $this->audit, 
                array( 
                    "Withdraw accepted!",
                    $amount,
                    $this->balance,
                    $transDate->format('c')
                ) 
            );
        } else {
            // Account not locked, but timeperiod was lapsed
            if ( $this->locked === false ) {
                $this->balance -= amount;
                array_push( $this->audit, 
                    array( 
                        "Withdraw accepted with PENALTY!",
                        $amount,
                        $this->balance,
                        $transDate->format('c')
                    ) 
                );
                $this->penalty();
            } else {
                array_push( $this->audit, 
                    array( 
                        "Withdraw denied!",
                        $amount,
                        $this->balance,
                        $transDate->format('c')
                    ) 
                );
            }
        }

        // Calling consts/static vars inside SubClass
        // Parent:: is used for looking at consts/static vars inside SuperClass
        echo parent::INFO;
        echo '<hr>';
        echo parent::$stat;
        echo '<hr>';
        echo parent::stat();
    }

    private function penalty ( ) { // private is secured to CURRENT CLASS
        $transDate = new DateTime();
        $penaltyAmount = 10;
        $this->balance -= $penaltyAmount;

        array_push( $this->audit, 
            array( 
                "Withdraw penalty!",
                $penaltyAmount,
                $this->balance,
                $transDate->format( 'c' )
            ) 
        );
    }

}

trait SavingsPlus {
    private $monthlyFee = 20;
    public $package = "Holiday insurance";

    // Methods
    public function addedBonus() {
        echo "Hello " . $this->firstName . " " . $this->lastName . 
            ", for SEK" . $this->monthlyFee . 
            " a month you get " . $this->package;
    }
}

// Interfaces, uses implements for usage
interface AccountPlus {
    public function addedBonus();
}

interface Savers {
    public function orderNewPocketBook();
    public function orderNewDepositBook();
}

class SavingsAccount extends BankAccount implements AccountPlus, Savers {

    // Add Trait
    use SavingsPlus;

    // Variables
    public $pocketBook = array();
    public $depositBook = array();

    // Constructor
    public function __construct( $fee, $package, $apr, $sc, $fn, $ln, $bal = 0, $lock = false ) {
        $this->monthlyFee = $fee;
        $this->package = $package;

        parent::__construct( $apr, $sc, $fn, $ln, $bal, $lock );
    }

    // Methods
    public function orderNewPocketBook() {
        $orderTime = new DateTime();
        array_push( $this->pocketBook, "Ordered new pocket book on: " . $orderTime->format('c') );
    }

    public function orderNewDepositBook() {
        $orderTime = new DateTime();
        array_push( $this->depositBook, "Ordered new deposit book on: " . $orderTime->format('c') );
    }
}

class DebitAccount extends BankAccount implements AccountPlus {

    // Add trait
    use SavingsPlus;

    // PRIVATE classes can only be called within the class. 
    // No public OR outside class calls allowed
    private $cardNumber;
    private $securityNumber;
    private $pinNumber;

    // Constructor
    public function __construct( $fee, $package, $pin, $apr, $sc, $fn, $ln, $bal = 0, $lock = false ) {
        $this->monthlyFee = $fee;
        $this->package = $package;
        $this->pinNumber = $pin;
        $this->validate();

        parent::__construct( $apr, $sc, $fn, $ln, $bal, $lock );
    }

    // Methods
    private function validate() {
        $valDate = new DateTime();
        $this->cardNumber = rand(1000, 9999).'-'.rand(1000, 9999).'-'.rand(1000, 9999).'-'.rand(1000, 9999);
        $this->securityNumber = rand(100, 999);
        array_push( $this->audit, 
            array( 
                "Validated card",
                $valDate->format('c'),
                $this->securityNumber,
                $this->pinNumber,
                $this->cardNumber,
            ) 
        );
    }

    public function changePin( $newPin ) {
        $pinChange = new DateTime();
        $this->pinNumber = $newPin;
        array_push( $this->audit, 
            array( 
                "PIN code changed",
                $pinChange->format('c'),
                $this->pinNumber
            ) 
        );
    }
}

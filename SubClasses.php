<?php

require('BankAccount.php'); // require will shut down app if NOT FOUND - unlike include()

class IndividualSavingsAccount extends BankAccount {

    // Properties
    public $timePeriod = 28;
    public $additionalServices;

    // Methods
    public function withdraw( $amount ) {
        $transDate = new DateTime();
        $lastTransaction = null;
        $length = count($this-audit);

        for ( $i = $length; $i > 0; $i-- ) {
            $element = $this->audit($i - 1);

            // Calculates latest transaction
            if ( $element[0] === "WITHDRAW ACCEPTED" ) {
                $days = new DateTime( $element[3] );

                // diff() gets difference between values
                // format("%a") produces a number
                $lastTransaction = $days->diff($transDate)->format("%a");
                break;
            }

            if ( 
                $this->locked === false && $lastTransaction === null || 
                $this->locked === false && $lastTransaction > $this->timePeriod  
            ) {
                $this->balance -= $amount;
                array_push( $this->audit, 
                    array( 
                        "Withdraw accepted!",
                        $amount,
                        $this->balance,
                        $transDate->format( 'c' )
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
                            $transDate->format( 'c' )
                        ) 
                    );
                    $this-penalty();
                } else {
                    array_push( $this->audit, 
                        array( 
                            "Withdraw denied!",
                            $amount,
                            $this->balance,
                            $transDate->format( 'c' )
                        ) 
                    );
                }
            }
        }
        
    }

    private function penalty ( $amount ) { // private is secured to CURRENT CLASS
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

class SavingsAccount extends BankAccount {

    // Variables
    public $pocketBook = array();
    public $depositBook = array();

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

class Debit extends BankAccount {

    // No public OR outside class calls allowed
    private $cardNumber;
    private $securityNumber;
    private $pinNumber;

    public function validate() {
        $valDate = new DateTime();
        $this->cardNumber = rand(1000, 9999).'-'.rand(1000, 9999).'-'.rand(1000, 9999).'-'.rand(1000, 9999);
        $this->securityNumber = rand(100, 999);
        array_push( $this->audit, 
            array( 
                "Validated card",
                $valDate->format('c'),
                $this->securityNumber,
                $this->pinNumber
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

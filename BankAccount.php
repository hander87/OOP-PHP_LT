<?php
// TODO: 28/5 
// ABSTRACT class cannot be invoked DIRECTLY. "new BankAccount" = Invalid.
// Invoked only through sub-classes
abstract class BankAccount {

    // Class Level Members:
    const INFO = "<p>Constant in BankAccount class</p>";
    static public $stat = "<p>Static property string</p>";
    

    // Properties
    protected $balance; // protected get black boxed/censored, but accessible by other classes
    public $apr;
    public $sortCode;
    public $firstName;
    public $lastName;
    public $audit = array();
    protected $locked;

    // Constructor
    // Optional args are at the END. They are optional if they have default values. Writes like:
    // $accountX = new ISA(5.0, "20-20-20", "Tom", "Phips")
    public function __construct( $apr, $sc, $fn, $ln, $bal = 0, $lock = false ) {

        $this->balance = $bal;
        $this->apr = $apr;
        $this->sortCode = $sc;
        $this->firstName = $fn;
        $this->lastName = $ln;
        $this->locked = $lock;

    }

    // Methods
    static public function stat() {
        echo "<p>This is the method static string</p>";
    }

    public function withdraw( $amount ) {
        $transDate = new DateTime();

        if ( $this->locked === false ) {
            $this->balance -= $amount;

            array_push( $this->audit, 
                array( 
                    "Withdraw accepted!",
                    $amount,
                    $this->balance,
                    $transDate->format( 'c') 
                ) 
            );
        } else {
            array_push( $this->audit, 
                array( 
                    "Withdraw denied!",
                    $amount,
                    $this->balance,
                    $transDate->format( 'c') 
                ) 
            );
        }
    }

    public function deposit( $amount ) {

        // Variable(s)
        $transDate = new DateTime();

        if ( $this->locked === false ) {
            $this->balance += $amount;

            array_push( $this->audit, 
                array( 
                    "Deposit accepted!",
                    $amount,
                    $this->balance,
                    $transDate->format( 'c') 
                ) 
            );
        } else {
            array_push( $this->audit, 
                array( 
                    "Deposit denied!",
                    $amount,
                    $this->balance,
                    $transDate->format( 'c') 
                ) 
            );
        }
    }

    public function lock () {
        $this->locked = true;
        $lockedDate = new DateTime();
        array_push( $this->audit, 
            array( 
                "Account locked!",
                $lockedDate->format( 'c') 
            ) 
        );
    }

    public function unlock () {
        $this->locked = false;
        $unlockedDate = new DateTime();
        array_push( $this->audit, 
            array( 
                "Account unlocked!",
                $unlockedDate->format( 'c') 
            ) 
        );
    }
}

// Displaying class levels info 
// (Note; NOT instanciation, because it's not allowed in abstract classes)
echo BankAccount::INFO;
echo BankAccount::$stat;
echo BankAccount::stat();

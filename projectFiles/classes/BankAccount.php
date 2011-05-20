<?php
class BankAccount {
  private $fBalance = 0;
 
  public function getBalance() {
    return $this->fBalance;
  }
 
  public function setBalance($balance) {
    if ($balance >= 0) {
      $this->fBalance = $balance;
    }
  }
 
  public function depositMoney($amount) {
    if (is_numeric($amount) &&
      $amount >= 0) {
      $this->fBalance += $amount;
    }
  }
 
  public function withdrawMoney($amount) {
    if (is_numeric($amount) &&
      $amount >= 0 &&
      $this->fBalance >= $amount) {
      $this->fBalance -= $amount;
    }
  }
}
?>
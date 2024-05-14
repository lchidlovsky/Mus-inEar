<?php
namespace Models\Classes\Input;
use Models\Classes\Input\Input;

class InputPassword extends Input {
    public function __construct($id,$name,$value,$label,$required, $intitule) {
        parent::__construct("password",$id,$name,$value,$label,$required, $intitule);
    }

    public function render(): string
    {
        return parent::render();
    }
}

?>

<?php
namespace Models\Classes\Input;
use Models\Classes\Input\Input;

class InputNumber extends Input {
    public function __construct($id,$name,$value,$label,$required,$intitule){
        parent::__construct("number",$id,$name,$value,$label,$required,$intitule);
    }

    public function render(): string
    {
        $label = "<label for='" . $this->getLabel() . "'>". $this->intitule ."</label>" . PHP_EOL;
        $input = "<input type='" . $this->getType() . "' id='". $this->getId() . "' name='". $this->getName() . "' value='" . $this->getValue() . "' min='0' " . $this->isRequired() . " >" . PHP_EOL; 
        return $label . $input;
    }
}

?>

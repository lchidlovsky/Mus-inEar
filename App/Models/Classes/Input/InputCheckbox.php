<?php
namespace Models\Classes\Input;
use Models\Classes\Input\Input;

class InputCheckbox extends Input {
    public function __construct($id,$name,$value,$label,$required, $intitule) {
        parent::__construct("checkbox",$id,$name,$value,$label,$required, $intitule);
    }

    public function render(): string
    {
        $input = "<input type='" . $this->getType() . "' id='". $this->getId() . "' name='". $this->getName() . "' placeholder='" . $this->getValue() . "' " . $this->isRequired() . " >" . PHP_EOL;
        $label = "<label for='" . $this->getLabel() . "'>". $this->intitule ."</label>" . PHP_EOL;
        return $input . $label;
    }
}

?>

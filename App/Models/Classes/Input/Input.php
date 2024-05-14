<?php
namespace Models\Classes\Input;

abstract class Input{
    protected string $type;
    protected string $id;
    protected string $name;
    protected string $value = " ";
    protected string $label;
    protected bool $required;
    protected string $intitule;

    public function __construct($type, $id, $name, $value, $label, $required, $intitule) {
        $this->type = $type;
        $this->id = $id;
        $this->name = $name;
        $this->value = $value;
        $this->label = $label;
        $this->required = $required;
        $this->intitule = $intitule;
    }

    public function getType() {
        return $this->type;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getValue() {
        return $this->value;
    }

    public function getLabel() {
        return $this->label;
    }

    protected function isRequired(): string
    {
        return $this->required ? "required = true" : "";
    }

    public function render(): string
    {
        $label = "<label for='" . $this->getLabel() . "'>". $this->intitule ."</label>" . PHP_EOL;
        $input = "<input type='" . $this->getType() . "' id='". $this->getId() . "' name='". $this->getName() . "' placeholder='" . $this->getValue() . "' " . $this->isRequired() . " >" . PHP_EOL; 
        return $label . $input;
    }
}

?>

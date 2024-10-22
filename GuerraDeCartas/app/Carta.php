<?php

class Carta {
    private $palo;
    private $numero;
    private $imagen;
    private const PALOS=['o', 'c', 'e', 'b']; 

    public function __construct($palo,$numero){
        $this->palo= self::PALOS[$palo];
        $this->numero=$numero;
        $this->imagen="imagen/c_". self::PALOS[$palo]."_".$this->getNumero(). ".png";
    }
    public function getNumero(){
        return $this->numero;
    }

    public function getPalo(){
        return $this->palo;
    }
    public function setNumero($valor){
        $this->numero = $valor;
    }
    public function setPalo($valor){
        $this->palo=$valor;
    }

    public function getImagen(){
        return $this->imagen;
    }

    public function __toString(){
        return "<img src='".$this->imagen."'>";
    }

    /*public function getTodo(){
        
        foreach (self::PALOS[$palo] as $palos){
            foreach($numero as $numeros){
                return $carta->__toString($numeros, $palos);
            }

        }
    }*/
}
?>
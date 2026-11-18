<?php

require_once 'InvalidPropertyNameException.php';
require_once 'InvalidPropertyValueException.php';


class AudioTrack {

    protected string $titre;
    protected string $artiste; 
    protected int $annee;     
    protected string $genre;
    protected int $duree;
    protected string $chemin;

    public function __construct(string $titre, string $chemin) {
        $this->titre = $titre;
        $this->chemin = $chemin;
    }

    public function __toString(): string {
        return json_encode(get_object_vars($this));
    }
	
	
	public function __get(string $att): mixed {
		if (property_exists($this,$att)) return $this->$att;
		throw new Exception("propriété invalide $att");
	}
	
	public function __set(string $att, mixed $val) {
		if (property_exists($this,$att)) {
			if ($att === "titre" || $att === "chemin") {
				throw new Exception("La propriété $att n'est pas modifiable");
				return;
			}
			if ($att === 'duree' && $att < 0) {
				trow new InvalidPropertyValueException('On ne peut pas attribuer une durée négative');
				return;
			}
			$this->$att = $val;
			return;
		}
		throw new InvalidPropertyNameException("propriété invalide: $att");
	}
	
	
	
	
	
	
}
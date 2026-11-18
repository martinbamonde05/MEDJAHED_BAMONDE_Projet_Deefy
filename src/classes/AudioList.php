<?php

require_once 'InvalidPropertyNameException';
require_once 'InvalidPropertyValueException';

class AudioList {
	
	protected string $nom;
	protected int $nbpistes;
	protected int $dureeTotale;
	protected array $pistes;
	
	public function __construct(string $n, array $p = []) {
		$this->nom = $n;
		if(!(empty($p))) {
			$this->pistes = $p;
			$i = 0;
			foreach($p as $pis) {
				$i += $i 1;
				$this->dureeTotale += $pis->duree;
			}
			$this->nbpistes = $i;
		}
	}
	
	public function __get(string $att): mixed {
		if (property_exists($this, $att) {
			return $this->$att;
		}
		throw new InvalidPropertyNameException('propriété invalide: $att');
	}
	
}
	
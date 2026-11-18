<?php

require_once 'src/classes/AlbumTrack.php';

try {

	$p1 = new AlbumTrack("piste1", "Td/Td_4/", "album1", 2);
	$p2 = new AlbumTrack("piste2", "Td/Td_4/p2", "album2", 5);
	echo "Avec echo  " . $p1->titre . "\n";
	print "Avec print  " . $p1->titre . "\n";
	printf("Avec printf \n", $p1->titre, $p1->album);
	echo "Avec __toString() : " . $p1->__toString() . "\n";
	echo $p1 . "\n"; 

	echo " print r \n";
	print_r($p1);

	echo " var dump \n";
	var_dump($p1);
	
}
catch (InvalidPropertyValueException $e) {
    echo "Erreur de valeur : " . $e->getMessage() . "\n";
} catch (InvalidPropertyNameException $e) {
    echo "Erreur de nom de propriété : " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}


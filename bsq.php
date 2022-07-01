<?php
//Modifier cette ligne pour gérer la taille
shell_exec("perl script.pl 10 10 3 > example_file");
//x, y et la densité des obstacles juste apres "script.pl"

function solver ( $file ) {

    $erreur = 0;
    //MISE EN PLACE
    $filestr = file_get_contents( $file );
    $filearray = explode( "\n", $filestr );
    // print_r($filearray);
    $nbrelement = $filearray[0];
    $lastelementarray = count( $filearray )-1;
    unset ( $filearray[0] );
    unset ( $filearray[$lastelementarray] );

    for ( $i = 1; $i <= count( $filearray ); $i++ ) { //Premiere boucle pour mettre les 1
        $filearraydeux[$i] = str_split( $filearray[$i] );
    }
    for ( $i = 1; $i <= count( $filearraydeux ); $i++) {
        for ( $j = 0; $j < count( $filearraydeux[$i] ); $j++ ) {
            if ( $filearraydeux[$i][$j] == "." ) {
                $filearraydeux[$i][$j] = 1;
                $erreur++;
            } else {
                $filearraydeux[$i][$j] = 0;
            }
        }
    }
    if($erreur == 0){
        $string = implode( "\n", $filearray );
        echo $string . "\n";
        return;
    }
    //Appel des fonctions
    $filearraydeux = number( $filearraydeux );
    $filearraydeux = create( $filearraydeux );
    $filearraydeux = reinitialize( $filearraydeux );
    
    for ( $i = 1; $i <= count( $filearraydeux ); $i++) {
        $filearray[$i] = implode( $filearraydeux[$i] );
    }
    
    //Mise en place de la sortie
    $string = implode( "\n", $filearray );
    echo $string . "\n";
    return;
}
function number ( $filearraydeux ) { // Metttre les nombres aux bons endroits pour trouver le plus grand carré possible
    foreach ( $filearraydeux as $lignes => $lignearray ) {
        if ( $lignes != 1 ) {
            foreach ( $lignearray as $colonne => $casevalue ) {
                if ( $colonne != 0 ) {
                    $actualcase = $filearraydeux[$lignes][$colonne];
                    $un = $filearraydeux[$lignes-1][$colonne-1];
                    $deux = $filearraydeux[$lignes-1][$colonne];
                    $trois = $filearraydeux[$lignes][$colonne-1];
                    if ( $actualcase != 0 && $un != 0 && $deux != 0 && $trois != 0 ) {
                        if ( $un == $deux && $deux == $trois ) {
                            $filearraydeux[$lignes][$colonne] = $un+1;
                        } elseif ($un != $deux && $deux != $trois && $trois != $un){
                            $filearraydeux[$lignes][$colonne] = min( $un, $deux, $trois )+1;
                        } else {
                            $filearraydeux[$lignes][$colonne] = min( $un, $deux, $trois )+1;
                        }
                    }
                }
            }
        }
    }
    return $filearraydeux;
}
function create ( $filearraydeux ) { // trouver l'endroit du plus grand carré
    $max = [];
    foreach ( $filearraydeux as $lignes => $lignearray ) {
            array_push( $max, max( $lignearray ));
    }
    $max = max( $max );
    foreach ( $filearraydeux as $lignes => $lignearray ) {
        foreach ( $lignearray as $colonne => $casevalue ) {
            if ( $casevalue == $max ) {
                $filearraydeux = croix( $filearraydeux, $lignes, $colonne, $max );
                return $filearraydeux;
            }
        }
    }
    return $filearraydeux;
}
function croix ( $filearraydeux, $lignes, $colonne, $max ) { // Création de la croix
    $i = $max;
    $j = $max;
    $filearraydeux[$lignes][$colonne] = "X";
    while ( $i !=1 || $j !=1 ) {
        for ( $z=0; $z != $max; $z++ ) {
            $filearraydeux[$lignes-$z][$colonne] = "X";
            for ( $x = 1; $x != $max; $x++) {
                $filearraydeux[$lignes-$z][$colonne-$x] = "X";
            }
        }
        $i--;
        $j--;
    }
    return $filearraydeux;
}
function reinitialize ( $filearraydeux ) { //Remettre tout à zéro sauf la croix

    foreach ( $filearraydeux as $lignes => $lignearray ) {
        foreach ( $lignearray as $colonne => $casevalue ) {
            if ( $filearraydeux[$lignes][$colonne] == 0 ) {
                $filearraydeux[$lignes][$colonne] = "o";
            } elseif ( $filearraydeux[$lignes][$colonne] != "X" ) {
                $filearraydeux[$lignes][$colonne] = ".";
            }
        }
    }
    return $filearraydeux;
}
solver ( $argv[1] );
<?php

namespace Lpmr\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
        public function importAction()
    {    
        
        $users = array(); // Tableau qui va contenir les éléments extraits du fichier CSV
        $row = 0; // Représente la ligne
        // Import du fichier CSV 
        if (($handle = fopen(__DIR__ . "/../../../../Ressources/liste_eleves.csv", "r")) !== FALSE) { // Lecture du fichier, à adapter
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) { // Eléments séparés par un point-virgule, à modifier si necessaire
                $num = count($data); // Nombre d'éléments sur la ligne traitée
                $row++;
                for ($c = 0; $c < $num; $c++) {
                    $users[$row] = array(
                            "nom" => $data[0],
                            "prenom" => $data[1],
                            "promotion" => $data[2]
                            
                    );
                }
            }
            fclose($handle); 
            
        }        
        
        $em = $this->getDoctrine()->getManager(); // EntityManager pour la base de données
        
        // Lecture du tableau contenant les utilisateurs et ajout dans la base de données
        foreach ($users as $utilisateur) {
            
            // On crée un objet utilisateur
            $user = new User();
            
            // Hydrate l'objet avec les informations provenants du fichier CSV
            $user->setNom($utilisateur["nom"]);
            $user->setPrenom($utilisateur["prenom"]);
            $user->setPromo($utilisateur["promotion"]);
                
            // Enregistrement de l'objet en vu de son écriture dans la base de données
            $em->persist($user);
            
        }
        
        // Ecriture dans la base de données
        $em->flush();
        
        // Renvoi la réponse (ici affiche un simple OK pour l'exemple)
        return new Response('OK');
    }
}
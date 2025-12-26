<?php

// controllers/ChapterController.php
session_start();
require_once '../../Modele/Chapter.php';

class ChapterController
{
    private $chapters = [];

    public function __construct()
    {
        // Exemple de chapitres avec des images
        include '../../php/Database.php';
        $res = $db->query("Select * FROM Chapter");
        
        
        while($chapter = $res->fetch()){
            $liens = [];
            $linksRes = $db->query("Select * FROM Links where chapter_id = " . (String)$chapter["id"]);
            while($lien = $linksRes->fetch()){
                array_push($liens, ["text" => $lien["description"], "chapter" => $lien["next_chapter_id"]]);
            }
            $this->chapters[] = new Chapter(
                $chapter["id"],
                $chapter["titre"],
                $chapter["content"],
                $chapter["image"], // Chemin vers l'image
                $liens
            );
        }
    }

    public function show($id)
    {
        $chapter = $this->getChapter($id);

        if ($chapter) {
            include '../View/chapitre/'; // Charge la vue pour le chapitre
        } else {
            // Si le chapitre n'existe pas, redirige vers un chapitre par défaut ou affiche une erreur
            header('HTTP/1.0 404 Not Found');
            echo "Chapitre non trouvé!";
        }
    }

    public function getChapter($id)
    {
        foreach ($this->chapters as $chapter) {
            if ($chapter->getId() == $id) {
                return $chapter;
            }
        }
        return null; // Chapitre non trouvé
    }
}

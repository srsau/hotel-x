<?php

namespace App\Controllers;

class PublicController
{
    public function projectDescription()
    {
        $title = 'Project Description';
        require __DIR__ . '/../views/project_description.php';
    }
}
?>

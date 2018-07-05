<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ContainerInterface;
 

class FileUploader
{
    private $directory;

    public function __construct( ContainerInterface $container)
    {
        $this->directory = $container->getParameter('upload_directory');
    }

    public function upload(UploadedFile $file)
    {
        // génération unique en md5
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        // déplacement vers le repertoir et son nom
        $file->move($this->directory, $fileName);

        // retourne le filename
        return $fileName;
    }

}
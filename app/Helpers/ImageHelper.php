<?php

namespace App\Helpers;

class ImageHelper
{
    const PATH_NAME = 'uploads' . DIRECTORY_SEPARATOR;
    const THUMB_PREFIX = 'thumb_';
    public $path;
    public $destinationPath;
    public $filename;
    public $thumb_height = 440;
    public $thumb_qualidade = 95;

    public function __construct()
    {
        $this->path = public_path(self::PATH_NAME);
    }

    static public function getFullThumbPath($folder)
    {
        return self::getFullPath($folder) . self::THUMB_PREFIX;
    }

    static public function getFullPath($folder)
    {
        return asset(self::PATH_NAME . $folder . DIRECTORY_SEPARATOR);
    }

    static public function GenerateThumbStatic($data)
    {
        //['src' => $path, 'filename' => $fileName, 'height' => 200, 'qualidade' => 65]
        $filename = $data['src'] . $data['filename'];
        $new_filename = $data['src'] . self::THUMB_PREFIX . $data['filename'];

        $tipo = getimagesize($filename);
        $tipo = $tipo[2];

        # Pega onde está a imagem e carrega
        switch ($tipo) {
            case 1:
                $image = imagecreatefromgif($filename);
                break;// 1 é o GIF
            case 2:
                $image = imagecreatefromjpeg($filename);
                break;// 2 é o JPG
            case 3:
                $image = imagecreatefrompng($filename);
                break;// 3 é PNG
        }

        #pegando as dimensoes reais da imagem, largura e altura
        $width = imagesx($image);
        $height = imagesy($image);
        //list($width, $height) = getimagesize($filename);

        #Nova a largura da miniatura
        $new_width = ($data['height'] * $width) / $height;

        #gerando a a miniatura da imagem
        $image_p = imagecreatetruecolor($new_width, $data['height']);

        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $data['height'], $width, $height);

        #o 3º argumento é a qualidade da miniatura de 0 a 100
        //imagejpeg($image_p, null, 50);
        imagejpeg($image_p, $new_filename, $data['qualidade']);
        imagedestroy($image_p);
        return true;
    }

    public function update($new_file, $folder, $old_filename)
    {
        // checking file is valid.
        if ($old_filename == NULL || $old_filename == '') {
            return $this->store($new_file, $folder);
        } else if ($new_file->isValid()) {
            $this->remove($old_filename, $folder);
            return $this->upload($new_file, $folder);
        } else {
            return false;
        }
    }

    public function store($file, $folder)
    {
        if ($file->isValid()) { // checking file is valid.
            return $this->upload($file, $folder);
        } else {
            return false;
        }
    }

    public function upload($file, $folder)
    {
        $this->setDestinationPath($folder);
        $this->setFilename($file);
        // uploading file to given path
        if ($file->move($this->destinationPath, $this->filename)) { // Faz o upload da imagem para seu respectivo caminho
            $this->GenerateThumb();
        } else { // Erro no envio
            return false;
        }
        return $this->filename;
    }

    public function setDestinationPath($folder)
    {
        $this->destinationPath = $this->path . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR; // upload path
    }

    public function setFilename($file)
    {
        $this->filename = md5(uniqid(time())) . '.' . $file->getClientOriginalExtension(); // renameing image
    }

    function GenerateThumb()
    {
        $filename = $this->destinationPath . $this->filename;
        $new_filename = $this->destinationPath . self::THUMB_PREFIX . $this->filename;

        $tipo = getimagesize($filename);
        $tipo = $tipo[2];

        # Pega onde está a imagem e carrega
        switch ($tipo) {
            case 1:
                $image = imagecreatefromgif($filename);
                break;// 1 é o GIF
            case 2:
                $image = imagecreatefromjpeg($filename);
                break;// 2 é o JPG
            case 3:
                $image = imagecreatefrompng($filename);
                break;// 3 é PNG
        }

        #pegando as dimensoes reais da imagem, largura e altura
        $width = imagesx($image);
        $height = imagesy($image);
        //list($width, $height) = getimagesize($filename);

        #Nova a largura da miniatura
        $new_width = ($this->thumb_height * $width) / $height;

        #gerando a a miniatura da imagem
        $image_p = imagecreatetruecolor($new_width, $this->thumb_height);

        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $this->thumb_height, $width, $height);

        #o 3º argumento é a qualidade da miniatura de 0 a 100
        //imagejpeg($image_p, null, 50);
        imagejpeg($image_p, $new_filename, $this->thumb_qualidade);
        imagedestroy($image_p);
        return true;
    }

    public function remove($filename, $folder)
    {
        //Removendo as fotos do diretório
        $this->setDestinationPath($folder);
        array_map('unlink', glob($this->destinationPath . DIRECTORY_SEPARATOR . $filename));
        array_map('unlink', glob($this->destinationPath . DIRECTORY_SEPARATOR . self::THUMB_PREFIX . $filename));
    }


}
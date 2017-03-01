<?php

namespace App\Http\Controllers;

class ImageController extends Controller
{
    const PATH_NAME = 'uploads';
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
//    public $path;
//    public function __construct()
//    {
////        $this->middleware('auth');
//        $this->path = public_path('uploads');
//
//    }
//
//    public function update($new_file, $folder, $old_filename)
//    {
//        // checking file is valid.
//        if ($old_filename == NULL || $old_filename == '') {
//            return $this->store($new_file, $folder);
//        } else if ($new_file->isValid()) {
//            $this->remove($old_filename, $folder);
//            $destinationPath = $this->path . '/'. $folder . '/'; // upload path
//            $extension = $new_file->getClientOriginalExtension(); // getting image extension
//            $fileName = md5(uniqid(time())).'.'.$extension; // renameing image
//            // uploading file to given path
//            // Faz o upload da imagem para seu respectivo caminho
//            if ($new_file->move($destinationPath, $fileName))
//            {
//                $thumb['src'] = $destinationPath;
//                $thumb['filename'] = $fileName;
//                $thumb['height'] = 440;
//                $thumb['qualidade'] = 95;
//
//                $this->GenerateThumb($thumb['src'], $thumb['filename'], $thumb['height'], $thumb['qualidade']);
//            }
//            else { // Erro no envio
//                return false;
//            }
//            return $thumb['filename'];
//        }
//        else {
//            // sending back with error message.
//            //Session::flash('error', 'uploaded file is not valid');
//            return false;
//        }
//    }
//
//    public function store($file, $folder)
//    {
//        // checking file is valid.
//        if ($file->isValid()) {
//
////            $destinationPath = storage_path('uploads/'.$folder.'/'); // upload path
//            $destinationPath = $this->path . '/' . $folder . '/'; // upload path
//            $extension = $file->getClientOriginalExtension(); // getting image extension
//            $fileName = md5(uniqid(time())).'.'.$extension; // renameing image
//            // uploading file to given path
//            // Faz o upload da imagem para seu respectivo caminho
//            if ($file->move($destinationPath, $fileName))
//            {
//                $thumb['src'] = $destinationPath;
//                $thumb['filename'] = $fileName;
//                $thumb['height'] = 440;
//                $thumb['qualidade'] = 95;
//
//                $this->GenerateThumb($thumb['src'], $thumb['filename'], $thumb['height'], $thumb['qualidade']);
//            }
//            else { // Erro no envio
//                return false;
//            }
//            return $thumb['filename'];
//        }
//        else {
//            // sending back with error message.
//            //Session::flash('error', 'uploaded file is not valid');
//            return false;
//        }
//    }
//
//    function GenerateThumb($location, $name, $new_height, $qualidade)
//    {
//        $filename = $location . $name;
//        $tipo = getimagesize($filename);
//        $tipo = $tipo[2];
//
//        # Pega onde está a imagem e carrega
//        if ($tipo == 2) { // 2 é o JPG
//            $image = imagecreatefromjpeg($filename);
//        }
//        if ($tipo == 1) { // 1 é o GIF
//            $image = imagecreatefromgif($filename);
//        }
//        if ($tipo == 3) { // 3 é PNG
//            $image = imagecreatefrompng($filename);
//        }
//
//        $new_filename = $location . 'thumb_' . $name;
//
//        #pegando as dimensoes reais da imagem, largura e altura
//        $width = imagesx($image);
//        $height = imagesy($image);
//        //list($width, $height) = getimagesize($filename);
//
//        #Nova a largura da miniatura
//        $new_width = ($new_height * $width) / $height;
//
//        #gerando a a miniatura da imagem
//        $image_p = imagecreatetruecolor($new_width, $new_height);
//
//        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
//
//        #o 3º argumento é a qualidade da miniatura de 0 a 100
//        //imagejpeg($image_p, null, 50);
//        imagejpeg($image_p, $new_filename, $qualidade);
//        imagedestroy($image_p);
//        return true;
//    }
//
//    public function remove($filename, $folder)
//    {
//        //Removendo as fotos do diretório
//        array_map('unlink', glob(public_path('uploads/' . $folder . '/' . $filename)));
//        array_map('unlink', glob(public_path('uploads/' . $folder . '/thumb_' . $filename)));
//    }


}
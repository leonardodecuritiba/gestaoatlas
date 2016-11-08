<?php

namespace App\Http\Controllers;

class ImageController extends Controller
{
    public $path;
    public function __construct()
    {
//        $this->middleware('auth');
        $this->path = storage_path('uploads/');

    }

    function GenerateThumb($location, $name, $new_height, $qualidade)
    {
        $filename = $location.$name;
        $tipo = getimagesize($filename);
        $tipo = $tipo[2];

        # Pega onde está a imagem e carrega
        if($tipo == 2){ // 2 é o JPG
            $image = imagecreatefromjpeg($filename);
        } if($tipo == 1){ // 1 é o GIF
        $image = imagecreatefromgif($filename);
    } if($tipo == 3){ // 3 é PNG
        $image = imagecreatefrompng($filename);
    }

        $new_filename = $location.'thumb_'.$name;

        #pegando as dimensoes reais da imagem, largura e altura
        $width = imagesx($image);
        $height = imagesy($image);
        //list($width, $height) = getimagesize($filename);

        #Nova a largura da miniatura
        $new_width = ($new_height*$width)/$height;

        #gerando a a miniatura da imagem
        $image_p = imagecreatetruecolor($new_width, $new_height);

        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        #o 3º argumento é a qualidade da miniatura de 0 a 100
        //imagejpeg($image_p, null, 50);
        imagejpeg($image_p, $new_filename, $qualidade);
        imagedestroy($image_p);
        return true;
    }

    public function store($file,$folder)
    {
        // checking file is valid.
        if ($file->isValid()) {

//            $destinationPath = storage_path('uploads/'.$folder.'/'); // upload path
            $destinationPath = $this->path.'/'.$folder.'/'; // upload path
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $fileName = md5(uniqid(time())).'.'.$extension; // renameing image
            // uploading file to given path
            // Faz o upload da imagem para seu respectivo caminho
            if($file->move($destinationPath, $fileName))
            {
                $thumb['src'] = $destinationPath;
                $thumb['filename'] = $fileName;
                $thumb['height'] = 440;
                $thumb['qualidade'] = 95;

                $this->GenerateThumb($thumb['src'], $thumb['filename'], $thumb['height'], $thumb['qualidade']);
            }
            else { // Erro no envio
                return false;
            }
            return $thumb['filename'];
        }
        else {
            // sending back with error message.
            //Session::flash('error', 'uploaded file is not valid');
            return false;
        }
    }

    public function remove($filename,$folder)
    {
        //Removendo as fotos do diretório
        array_map('unlink', glob(storage_path('uploads/'.$folder.'/'.$filename)));
        array_map('unlink', glob(storage_path('uploads/'.$folder.'/thumb_'.$filename)));
    }

    public function update($new_file,$folder,$old_filename)
    {
        // checking file is valid.
        if($old_filename == NULL || $old_filename == ''){
            return $this->store($new_file,$folder);
        } else if ($new_file->isValid()) {
            $this->remove($old_filename, $folder);
            $destinationPath = storage_path('uploads/'.$folder.'/'); // upload path
            $extension = $new_file->getClientOriginalExtension(); // getting image extension
            $fileName = md5(uniqid(time())).'.'.$extension; // renameing image
            // uploading file to given path
            // Faz o upload da imagem para seu respectivo caminho
            if($new_file->move($destinationPath, $fileName))
            {
                $thumb['src'] = $destinationPath;
                $thumb['filename'] = $fileName;
                $thumb['height'] = 440;
                $thumb['qualidade'] = 95;

                $this->GenerateThumb($thumb['src'], $thumb['filename'], $thumb['height'], $thumb['qualidade']);
            }
            else { // Erro no envio
                return false;
            }
            return $thumb['filename'];
        }
        else {
            // sending back with error message.
            //Session::flash('error', 'uploaded file is not valid');
            return false;
        }
    }


}
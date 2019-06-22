<?php

namespace App\Http\Controllers;

use Image;
use File;
use Carbon\Carbon;
use Mockery\Exception;

class Upload extends Controller {

    private $uploadPath;

    public function __construct($uploadPath) {
        $this->uploadPath = public_path('uploads/'.$uploadPath);
    }

    public function delete($file) {
        return File::delete($this->uploadPath.'/'.$file);
    }

    public function image($reqImage, $dimension = 500) {
        try {
            $filename = Carbon::now()
                ->timestamp
                .'_'
                .uniqid()
                .'.'
                .$reqImage->getClientOriginalExtension();

            if (!File::isDirectory($this->uploadPath))
                File::makeDirectory($this->uploadPath);

            $canvas = Image::canvas($dimension, $dimension);
            $resizeImage  = Image::make($reqImage)->resize($dimension, $dimension, function($constraint) {
                $constraint->aspectRatio();
            });

            $canvas->insert($resizeImage, 'center');
            $canvas->save($this->uploadPath . '/' . $filename);

            return $filename;
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
        return false;
    }

}

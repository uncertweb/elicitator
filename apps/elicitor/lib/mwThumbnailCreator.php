<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mwThumbnailCreator
 *
 * @author Matthew
 */
class mwThumbnailCreator {

    private $__width = 150, $__height = 150, $__quality = 95, $__extension, $__input_file;

    public function __construct($max_width = 150, $max_height=150, $quality=95) {
        $this->__width = $max_width;
        $this->__height = $max_height;
        $this->__quality = $quality;
    }

    //TODO find a better way of telling whether this is a PDF file (i.e. by checking
    // the mime type)
    public function loadData($filePath) {
        // Locate the file and check the mime type
        $extension = substr($filePath, -4, 4);
        $this->__extension = $extension;
        $this->__input_file = $filePath;
    }

    /*
     *
     */

    public function create($thumbFile, $format='jpg') {
        /*
         * TODO this checks whether the thumbnail directory has been created
         * and creates if not. Seems a bit 'hacky', try to find a better way.
         */
        $thumbnail_directory = substr($thumbFile, 0, strrpos($thumbFile, '/'));
        
        if (!file_exists($thumbnail_directory)) {
            mkdir($thumbnail_directory, 0755, true);
        }
        if ($this->__extension == '.pdf') {
            // This is supposedly a PDF file, run it through ghostscript first
            $cmd = "gs -q -dQuiet -r200x200 -dPARANOIDSAFER -dBATCH -dNOPAUSE -dNOPROMPT -dMaxBitmap=500000000 -dFirstPage=1 -dLastPage=1 -dAlignToPixels=0 -dGridFitTT=0 -sDEVICE=jpeg -dTextAlphaBits=4 -dGraphicsAlphaBits=4 -sOutputFile=%stdout $this->__input_file | convert -resize " . $this->__width . "x" . $this->__height . " - $thumbFile";
        } else {
            // Try running this through IM
            $cmd = "convert -resize " . $this->__width . "x" . $this->__height . " " . $this->__input_file . " " . $thumbFile;
        }

        $output = exec($cmd);
        $result = file_exists($thumbFile);
        return $result;
    }

}

?>

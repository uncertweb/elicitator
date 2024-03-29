<?php

/**
 * ElicitationProblem
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    elicitor
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class ElicitationProblem extends BaseElicitationProblem {

    public function generateAttached_fileFileName($file) {
        return ElicitationProblem::slugify($file->getOriginalName());
    }

    public function getTruncatedFilename($length = 30) {
        if (strlen($this->getAttachedFile()) > $length) {
            $tail_length = floor($length / 3);
            $num_dots = 3;
            $str = substr($this->getAttachedFile(), 0, $length - $tail_length - $num_dots);
            $str .= '...';
            $str .= substr($this->getAttachedFile(), -$tail_length, $tail_length);

            return $str;
        } else {
            return $this->getAttachedFile();
        }
    }

    public function getThumbnailWidth() {
        if ($this->getThumbnail() != null) {
            $thumb_path = sfContext::getInstance()->getUser()->getAttribute('upload_dir') . sfConfig::get('app_thumbnail_upload_dir') . $this->getThumbnail();
            //sfContext::getInstance()->getLogger()->info('thumb: ' . $thumb_path);//log_message('hello' . $thumb_path);
            $size = getimagesize($thumb_path);

            return $size[0];
        }
        return null;
    }

    public function getProgress() {
        //TODO update this with a proper progress value!
        $variables = $this->getVariables();
        $total = 0;
        foreach($variables as $variable) {
            $total += $variable->getProgress();
        }
        return round($total/sizeof($variables));
    }

    // code derived from http://php.vrana.cz/vytvoreni-pratelskeho-url.php
    static public function slugify($text) {
        // replace non letter or digits by -
        $text = preg_replace('#[^\\pL\d.]+#u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        if (function_exists('iconv')) {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('#[^-\w.]+#', '', $text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

}
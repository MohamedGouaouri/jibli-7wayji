<?php


class Uploader
{
    private static string $base = "resources/upload/";

    public static function upload(string $file_name): string {
        $image_path = "";
        if(isset($_FILES[$file_name]['name'])){

            /* Getting file name */
            $filename = $_FILES[$file_name]['name'];
            /* Location */
            $location = self::$base . $filename;
            $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
            $imageFileType = strtolower($imageFileType);
            /* Valid extensions */
            $valid_extensions = array("jpg","jpeg","png");



            /* Check file extension */
            if(in_array(strtolower($imageFileType), $valid_extensions)) {
                /* Upload file */
                $location = self::$base . base64_encode($filename) . "." . $imageFileType;
                if(move_uploaded_file($_FILES[$file_name]['tmp_name'],$location)){
                    $image_path = $location;
                }
            }
        }
        return $image_path;
    }

}
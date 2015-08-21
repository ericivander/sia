<?php

class Image {

    const IMAGE_ORIGINAL = 'ORIGINAL';
    const IMAGE_LARGE = 'LARGE';
    const IMAGE_MEDIUM = 'MEDIUM';
    const IMAGE_SMALL = 'SMALL';
    const IMAGE_THUMB = 'THUMB';

    public static function getMime($name) {
        $mimes = & get_mimes();
        $exp = explode('.', $name);
        $extension = end($exp);

        if (isset($mimes[$extension])) {
            $mime = is_array($mimes[$extension]) ? $mimes[$extension][0] : $mimes[$extension];
        } else {
            $mime = 'application/octet-stream';
        }
        return $mime;
    }

    public static function getFileSize($id, $size, $name, $folder) {
        $CI = & get_instance();
        $config = $CI->config->item('utils');

        $location = $config['upload_dir'] . $folder . '/' . self::getFileName($id, $size, $name);
        return file_exists($location) ? filesize($location) : 0;
    }

    public static function getProperty($id, $size, $name, $folder) {
        $CI = & get_instance();
        $config = $CI->config->item('utils');

        $location = $config['upload_dir'] . $folder . '/' . self::getFileName($id, $size, $name);
        return file_exists($location) ? getimagesize($location) : FALSE;
    }

    public static function getImage($id, $name, $folder) {
        if (empty($name)) {
            return NULL;
        }

        $images = array();
        foreach (array(self::IMAGE_ORIGINAL, self::IMAGE_LARGE, self::IMAGE_MEDIUM, self::IMAGE_SMALL, self::IMAGE_THUMB) as $size) {
            $filesize = self::getFileSize($id, $size, $name, $folder);
            if ($filesize > 0) {
                list($width, $height, $mime) = self::getProperty($id, $size, $name, $folder);
                $sizeName = camelize(strtolower($size));
                $images[$sizeName] = array(
                    'name' => self::getFileName($id, $size, $name),
                    'mime' => self::getMime($name),
                    'size' => $filesize,
                    'width' => $width,
                    'height' => $height,
                    'type' => $sizeName,
                    'link' => self::createLink($id, $size, $name, $folder)
                );
            }
        }
        return (!empty($images)) ? $images : NULL;
    }

    public static function generateLink($id, $name, $folder) {
        $images = array();
        foreach (array(self::IMAGE_ORIGINAL, self::IMAGE_LARGE, self::IMAGE_MEDIUM, self::IMAGE_SMALL, self::IMAGE_THUMB) as $size) {
            $images[camelize(strtolower($size))] = self::createLink($id, $size, $name, $folder);
        }
        return $images;
    }

    public static function setFileName($name) {
        return str_replace('_', '-', underscore($name));
    }

    public static function getFileName($id, $size, $name) {
        $name = self::setFileName($name);
        return strtolower($size) . '-' . md5($id . $size) . md5($id . $name) . '-' . $name;
    }

    public static function getName($link) {
        return end(explode('-', $link));
    }

    public static function createLink($id, $size, $name, $folder) {
        $CI = & get_instance();
        $config = $CI->config->item('utils');

        $image = $config['upload_dir'] . $folder . '/' . self::getFileName($id, $size, $name);
        return (file_exists($image)) ? base_url() . $image : null;
    }

    /**
     * 
     * @param string|integer $id (Primary Key)
     * @param string $name
     * @param string $folder (posts/images)
     * @return boolean
     */
    public static function upload($field, $id, $name, $folder, $config = array()) {
        $CI = & get_instance();
        $ciConfig = $CI->config->item('utils');
        $config['upload_path'] = $path = $ciConfig['upload_dir'] . $folder . '/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['file_name'] = $fileName = self::getFileName($id, self::IMAGE_ORIGINAL, $name);
        $CI->load->library('upload');
        $CI->load->library('image_lib');

        $upload = new CI_Upload($config);
        if (!$upload->do_upload($field)) {
            return $upload->display_errors('', '');
        } else {
            $imageConfig = array('source_image' => $path . $fileName);

            foreach (array(self::IMAGE_LARGE, self::IMAGE_MEDIUM, self::IMAGE_SMALL) as $size) {
                $imageConfig['new_image'] = $path . self::getFileName($id, $size, $name);
//                list($imageConfig['width'], $imageConfig['height']) = explode('x', $ciConfig['image'][strtolower($size)]);
                list($imageConfig['width']) = explode('x', $ciConfig['image'][strtolower($size)]);
                $image = new CI_Image_lib();
                $image->initialize($imageConfig);
                $image->resize();
                $image->clear();
            }

            $imageConfig['new_image'] = $path . self::getFileName($id, self::IMAGE_THUMB, $name);
            list($imageConfig['width'], $imageConfig['height']) = explode('x', $ciConfig['image'][strtolower(self::IMAGE_THUMB)]);
            $imageConfig['maintain_ratio'] = FALSE;
            $image = new CI_Image_lib();
            $image->initialize($imageConfig);
            $image->resize();
            $image->clear();

            return TRUE;
        }
    }

    public static function remove($id, $name, $folder) {
        $CI = & get_instance();
        $config = $CI->config->item('utils');

        foreach (array(self::IMAGE_ORIGINAL, self::IMAGE_LARGE, self::IMAGE_MEDIUM, self::IMAGE_SMALL, self::IMAGE_THUMB) as $size) {
            $image = $config['upload_dir'] . $folder . '/' . self::getFileName($id, $size, $name);
            if (file_exists($image)) {
                unlink($image);
            }
        }
    }

}

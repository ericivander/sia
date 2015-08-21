<?php

class File {

    const NOT_FOUND = 'NFD';
    const ERROR = 'ERR';
    const UNAUTHORIZED = 'UNT';
    const INVALID_TOKEN = 'INT';

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

    public static function getFileSize($id, $path) {
        $CI = & get_instance();
        $config = $CI->config->item('utils');

        $location = APPPATH . $config['file_dir'] . $path . DIRECTORY_SEPARATOR . self::getFileName($id, $path);
        return file_exists($location) ? filesize($location) : 0;
    }

    public static function getFile($id, $name, $filepath) {
        if (empty($name)) {
            return NULL;
        }

        $image = array(
            'name' => $name,
            'mime' => self::getMime($name),
            'size' => self::getFileSize($id, $filepath),
            'link' => self::getLink($id, $filepath)
        );
        return $image;
    }

    public static function getUuid($id, $filepath) {
        $name = self::getFileName($id, $filepath) . '---' . $id . '---' . $filepath;
        return base64_encode($name);
    }

    public static function getData($uuid) {
        return explode('---', base64_decode($uuid));
    }

    public static function getFileName($id, $filepath) {
        return md5($id . $filepath);
    }

    public static function getLink($id, $filepath) {
        return site_url('download') . '?file=' . self::getUuid($id, $filepath);
    }

    public static function download($uuid, $filename, $set_mime = TRUE) {
        $CI = & get_instance();
        $config = $CI->config->item('utils');

        list($secureId, $id, $path) = self::getData($uuid);

        $filepath = $config['file_dir'] . $path . '/' . self::getFileName($id, $path) . '.backup';

        $filesize = filesize($filepath);

        $mime = 'application/octet-stream';

        $x = explode('.', $filename);
        $extension = end($x);

        if ($set_mime === TRUE) {
            if (count($x) === 1 OR $extension === '') {
                return;
            }
            $mimes = & get_mimes();
            if (isset($mimes[$extension])) {
                $mime = is_array($mimes[$extension]) ? $mimes[$extension][0] : $mimes[$extension];
            }
        }

        if (count($x) !== 1 && isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/Android\s(1|2\.[01])/', $_SERVER['HTTP_USER_AGENT'])) {
            $x[count($x) - 1] = strtoupper($extension);
            $filename = implode('.', $x);
        }

        if (($fp = @fopen($filepath, 'rb')) === FALSE) {
            return self::NOT_FOUND;
        }

        if (ob_get_level() !== 0 && @ob_end_clean() === FALSE) {
            @ob_clean();
        }

        header('Content-Type: ' . $mime);
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Expires: 0');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . $filesize);

        if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE) {
            header('Cache-Control: no-cache, no-store, must-revalidate');
        }

        header('Pragma: no-cache');

        while (!feof($fp) && ($data = fread($fp, 1048576)) !== FALSE) {
            echo $data;
        }

        fclose($fp);
        exit;
    }

    public static function upload($field, $id, $path, $config = array()) {
        $CI = & get_instance();
        $ciConfig = $CI->config->item('utils');
        $config['upload_path'] = $ciConfig['file_dir'] . $path . '/';
        $config['allowed_types'] = '*';
        $config['file_name'] = self::getFileName($id, $path) . '.backup';
        $CI->load->library('upload');

        $upload = new CI_Upload($config);
        if (!$upload->do_upload($field)) {
            return $upload->display_errors('', '');
        } else {
            return TRUE;
        }
    }

    public static function remove($id, $path) {
        $CI = & get_instance();
        $config = $CI->config->item('utils');

        $file = $config['file_dir'] . $path . '/' . self::getFileName($id, $path);
        if (file_exists($file)) {
            unlink($file);
        }
    }

}

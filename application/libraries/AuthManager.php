<?php

class AuthManager {

    const STATUS_LOGIN = 'I';
    const STATUS_LOGOUT = 'O';
    const STATUS_EXPIRED = 'E';
    const STATUS_DESTROY = 'D';
    const STATUS_FORCE_CLOSE = 'F';

    public static function generateToken($userId = NULL) {
        return md5(Util::timeNow()) . md5($userId) . md5(SecurityManager::generateAuthKey());
    }

    public static function login($username, $password, $device = NULL) {
        $CI = &get_instance();
        $CI->load->model('User_model');
        $CI->db->trans_start();
        $user = $CI->User_model->is_role(TRUE)->is_mapper(FALSE)->get_by('username', $username);
        if (!empty($user)) {
            if (SecurityManager::validate($password, $user['password'], $user['password_salt'])) {
                if ($user['status'] == Status::ACTIVE) {
                    //GANTI DENGAN DEVICE BUKAN IP ADDRESS
                    $CI->load->model('User_token_model');
                    $userToken = $CI->User_token_model->is_mapper(FALSE)->get_by(array('user_id' => $user['id'], 'ip_address' => RequestManager::getIpAddress(), 'status' => self::STATUS_LOGIN));
                    $return = $CI->User_model->is_mapper(TRUE)->mapper($user);
                    $return['photos'] = $user['photos'];
                    if (empty($userToken)) {
                        $regToken = self::registerToken($user['id'], $device, $CI);
                        if ($regToken !== FALSE) {
                            $CI->db->trans_complete();
                            return array('Login berhasil', $regToken, $return);
                        } else {
                            return 'Token gagal dibuat.';
                        }
                    } else {
                        return array('Anda telah login', $userToken['token'], $return);
                    }
                } else {
                    return 'User sudah tidak aktif.';
                }
            } else {
                return 'Username dan password tidak sesuai.';
            }
        } else {
            return 'User tidak terdaftar';
        }
    }

    public static function logout($token) {
        $CI = &get_instance();
        $CI->load->model('User_token_model');
        $userToken = $CI->User_token_model->get_by('token', $token);
        $time = Util::timeNow();
        $data = array(
            'ip_address' => RequestManager::getIpAddress(),
            'last_activity' => $time,
            'token_expired_time' => Util::timeAdd('+6 hours'),
            'count_request' => $userToken['count_request'] + 1,
            'status' => self::STATUS_LOGOUT
        );
        $update = $CI->User_token_model->update($userToken['id'], $data);
        if ($update) {
            return TRUE;
        } else {
            return 'Logout gagal.';
        }
    }

    public static function validateToken($token) {
        $CI = &get_instance();
        $CI->load->model('User_token_model');
        $CI->db->trans_start();
        $userToken = $CI->User_token_model->with('Device')->is_mapper(FALSE)->get_by('token', $token);
        $time = Util::timeNow();
        if (!empty($userToken)) {
            $CI->load->model('User_model');
            $userModel = new User_model();
            $user = $userModel->is_mapper(FALSE)->is_role(TRUE)->get($userToken['user_id']);
            if ($userToken['status'] == self::STATUS_LOGIN && strtotime($userToken['token_expired_time']) >= strtotime($time)) {
                $data = array(
                    'ip_address' => RequestManager::getIpAddress(),
                    'last_activity' => $time,
                    'token_expired_time' => Util::timeAdd('+6 hours'),
                    'count_request' => $userToken['count_request'] + 1,
                );
                $update = $CI->User_token_model->update($userToken['id'], $data);
                if ($update) {
                    $CI->db->trans_complete();
                    return array($userToken['token'], $user);
                } else {
                    return 'Update status user gagal.';
                }
            } elseif ($userToken['status'] == self::STATUS_LOGIN && strtotime($userToken['token_expired_time']) < strtotime($time)) {
                $device = (!empty($userToken['device'])) ? json_encode(array('secureId' => $userToken['device']['secure_id'])) : NULL;
                $regToken = self::registerToken($userToken['user_id'], $device, $CI);
                if ($regToken !== FALSE) {
                    $update = $CI->User_token_model->update($userToken['id'], array('status' => self::STATUS_EXPIRED));
                    $CI->db->trans_complete();
                    return array($regToken, $user);
                } else {
                    return 'Token gagal diganti.';
                }
            }
            return 'Token sudah kadaluarsa.';
        } else {
            return 'Token tidak terdaftar.';
        }
    }

    public static function registerToken($userId, $device = NULL, $CI = NULL) {
        if (is_null($CI))
            $CI = &get_instance();

        if (!empty($device)) {
            $deviceData = (Array) json_decode($device);
            $CI->load->model('Device_model');
            $existDevice = $CI->Device_model->get_by(array('secure_id' => $deviceData['secureId']));
            if (empty($existDevice)) {
                $insertDevice = $CI->Device_model->create($deviceData);
                if ($insertDevice) {
                    $existDevice = $CI->Device_model->get($insertDevice);
                } else {
                    return FALSE;
                }
            }
        }

        $CI->load->model('User_token_model');
        $time = Util::timeNow();
        $token = self::generateToken($userId);
        $data = array(
            'user_id' => $userId,
            'device_id' => !empty($existDevice) ? $existDevice['id'] : NULL,
            'ip_address' => RequestManager::getIpAddress(),
            'login_time' => $time,
            'last_activity' => $time,
            'token' => $token,
            'token_expired_time' => Util::timeAdd('+6 hours'),
            'count_request' => 1,
            'status' => self::STATUS_LOGIN
        );
        $insert = $CI->User_token_model->insert($data);
        if ($insert) {
            return $token;
        } else {
            return FALSE;
        }
    }

    public static function checkpass($id, $password) {
        $CI = &get_instance();
        $CI->load->model('User_model');
        $user = $CI->User_model->is_role(TRUE)->is_mapper(FALSE)->get($id);
        return SecurityManager::validate($password, $user['password'], $user['password_salt']);
    }

}

<?php

namespace App\Services;

use App\Models\Users;

class UsersService
{
    public function FbAuther($code)
    {
        if (!empty($code)){
            $result = false;

            $params = ['client_id'     => CLIENT_FB_ID,
                       'redirect_uri'  => REDIRECT_FB_URI,
                       'client_secret' => CLIENT_FB_SECRET,
                       'code'          => $code,
                       'scope'		   => 'email,user_birthday'
                      ];

            $url = 'https://graph.facebook.com/oauth/access_token';

            $tokenInfo = null;
            parse_str(file_get_contents($url . '?' . http_build_query($params)), $tokenInfo);

            if (count($tokenInfo) > 0 && isset($tokenInfo['access_token'])) {
                $params = array('fields'=>'id,name,email,first_name,last_name,hometown,birthday,gender','access_token' => $tokenInfo['access_token']);

                $userInfo = json_decode(file_get_contents('https://graph.facebook.com/me' . '?' . urldecode(http_build_query($params))), true);

                if (isset($userInfo['id'])) {
                    $userInfo = $userInfo;
                    $result = true;
                }
            }

            if ($result) {

                if ($userInfo['email'])
                    $email = $userInfo['email'];
                else
                    $email = $userInfo['id'] . '@facebook.com';

                if ($this->checkExistUser($email)){
                    return $this->getUserId($email);
                } else {
                    $fields = array();
                    $fields['id'] = 0;
                    $fields['email'] = $email;
                    $fields['password'] = '';
                    $fields['firstname'] = $userInfo['first_name'];
                    $fields['lastname'] = $userInfo['last_name'];

                    $insert_id = $this->addUser($fields);

                    if ($insert_id){
                        return $insert_id;
                    }
                }
            }
        }
    }

    public function VkAuther($code)
    {
        $result = false;
        $params = ['client_id' => CLIENT_VK_ID,
                   'client_secret' => CLIENT_VK_SECRET,
                   'code' => $_GET['code'],
                   'redirect_uri' => REDIRECT_VK_URI
                  ];

        $token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);

        if (isset($token['access_token'])) {
            $params = [
                'uids' => $token['user_id'],
                'fields' => 'uid,emeil,first_name,last_name,screen_name,sex,bdate,photo_big,photo_200,city,home_town',
                'access_token' => $token['access_token']
            ];

            $userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);
            if (isset($userInfo['response'][0]['uid'])) {
                $userInfo = $userInfo['response'][0];
                $result = true;
            }
        }

        if ($result) {
            if ($token['email']){

                if ($this->checkExistUser($token['email'])){
                    return $this->getUserId($token['email']);
                } else {
                    $fields = array();
                    $fields['id'] = 0;
                    $fields['email'] = $token['email'];
                    $fields['password'] = '';
                    $fields['firstname'] = $userInfo['first_name'];
                    $fields['lastname'] = $userInfo['last_name'];

                    $insert_id = $this->addUser($fields);

                    if ($insert_id){
                       return $insert_id;
                    }
                }
            }
        }
    }

    public function checkExistUser($email)
    {
        $count = Users::where('email', $email)->count();

        if ($count > 0)
            return true;
        else
            return false;
    }

    public function getUserId($email)
    {
        $row = Users::where('email', $email)->first();
        return $row->id;
    }

    public function addUser($fields)
    {
        return Users::insert($fields);
    }
}
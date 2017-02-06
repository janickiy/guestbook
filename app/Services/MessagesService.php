<?php

namespace App\Services;

use App\Models\Messages;

class MessagesService
{
    public function addMessage($user_id, $msg) {
        if (is_numeric($user_id) && !empty($msg)) {
            $fields = ['id' => 0,
                       'msg' => $msg,
                       'user_id' => $user_id
                      ];

            return Messages::insert($fields);
        }
    }
}
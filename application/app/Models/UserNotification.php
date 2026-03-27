<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function statusBadge($status)
    {
        $html = '';
        if ($this->read_status == 1) {
            $html = '<span class="badge badge--warning">' . trans('Unread') . '</span>';
        } else {
            $html = '<span class="badge badge--success">' . trans('Read') . '</span>';
        }
        return $html;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Constants\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Subscription extends Model
{
    protected $table = 'subscriptions';

    public function deposit()
    {
        return $this->hasOne(Deposit::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function statusBadge(): Attribute
    {
        return new Attribute(
            get: fn() => $this->badgeData(),
        );
    }

    public function badgeData()
    {
        $html = '';
        if ($this->status == Status::PLAN_SUBSCRIPTION_INITIAL) {
            $html = '<span class="badge badge--info">' . trans('Initiated') . '</span>';
        } elseif ($this->status == Status::PLAN_SUBSCRIPTION_APPROVED) {
            $html = '<span><span class="badge badge--success">' . trans('Approved') . '</span></span>';
        } elseif ($this->status == Status::PLAN_SUBSCRIPTION_PENDING) {
            $html = '<span><span class="badge badge--warning">' . trans('Pending') . '</span></span>';
        } elseif ($this->status == Status::PLAN_SUBSCRIPTION_REJECT) {
            $html = '<span><span class="badge badge--danger">' . trans('Reject') . '</span></span>';
        } else {
            $html = '<span><span class="badge badge--danger">' . trans('Disable') . '</span></span>';
        }
        return $html;
    }
}

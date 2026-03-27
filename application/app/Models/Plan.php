<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Constants\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Plan extends Model
{
    protected $casts = ['features' => 'object'];

    public function user()
    {
      return $this->belongsTo(User::class);
    }

    public function statusBadge(): Attribute
    {
        return new Attribute(
            get:fn () => $this->badgeData(),
        );
    }

    public function badgeData(){
        $html = '';
        if($this->status == Status::ENABLE){
            $html = '<span class="badge badge--success">'.trans('Active').'</span>';
        }else{
            $html = '<span class="badge badge--warning">'.trans('Inactive').'</span>';
        }
        return $html;
    }

    public function isPopular(){
        $html = '';
        if($this->is_popular == Status::PLAN_POPULAR_ENABLE){
            $html = '<span class="badge badge--success">'.trans('Yes').'</span>';
        }else{
            $html = '<span class="badge badge--warning">'.trans('No').'</span>';
        }
        return $html;
    }

}

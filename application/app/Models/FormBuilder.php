<?php

namespace App\Models;

use App\Constants\Status;
use App\Models\FormBuilderAnswer;
use Illuminate\Database\Eloquent\Model;

class FormBuilder extends Model
{
    protected $casts = [
        'form_data' => 'array'
    ];

    public function form_builder_answers()
    {
        return $this->hasMany(FormBuilderAnswer::class);
    }

    public function deposit()
    {
        return $this->hasOne(Deposit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function statusBadge($status)
    {
        $html = '';
        if ($this->status == 0) {
            $html = '<span class="badge badge--warning">' . trans('Inactive') . '</span>';
        } else {
            $html = '<span class="badge badge--success">' . trans('Active') . '</span>';
        }
        return $html;
    }

    public function pendingCount()
    {
        return $this->form_builder_answers()
            ->where('status', Status::FORM_BUILDER_ANSWER_PENDING)
            ->count();
    }
}

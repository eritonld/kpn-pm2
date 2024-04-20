<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalRequest extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id', 'employee_id');
    }
    public function goal()
    {
        return $this->hasOne(Goal::class, 'employee_id', 'employee_id');
    }
    public function approvalLayer()
    {
        return $this->hasMany(ApprovalLayer::class, 'employee_id', 'employee_id');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'current_approval_layer_id', 'employee_id');
    }
    public function approval()
    {
        return $this->hasMany(Approval::class, 'request_id');
    }

}
<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GovernmentDepartment extends Model
{
   
 protected $table = 'government_department';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['department_name','department_short_name','department_type','country_id','state_id','other_type_name','department_icon','status','is_deleted'];

}
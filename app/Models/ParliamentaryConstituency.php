<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParliamentaryConstituency extends Model
{
   
 protected $table = 'parliamentary_constituency';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['constituency_name','state_id','district_list','status','is_deleted'];

}
<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMembers extends Model
{
   
 protected $table = 'team_members';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_id','subscriber_id','designation_id','country_tm','state_tm','district_tm','lac_tm','pc_tm','mc1_tm','mc2_tm','cc_tm','block_tm','ward_tm','sub_district_tm','village_tm','status','is_deleted'];

}
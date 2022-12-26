<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewOfficial extends Model
{
   
 protected $table = 'review_official';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_id','subscriber_id','subscriber_review_id','country_ro','state_ro','district_ro','lac_ro','pc_ro','mc1_ro','mc2_ro','cc_ro','block_ro','ward_ro','sub_district_ro','village_ro','status','is_deleted'];

}
<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackagesPrice extends Model
{
   
 protected $table = 'packages_price';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['one_to_one_message_call','group_message_call','bulk_message','country_range','state_range','district_range','pc_range','ac_range','discount_6_month','discount_1_year','discount_2_year','status','is_deleted'];

}
<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriberPackage extends Model
{
   
 protected $table = 'subscriber_package';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['subscriber_id','package_id','submission_range','country','state','district','pc','ac','total_price','discounted_price','status','is_deleted'];

}
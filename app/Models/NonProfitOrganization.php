<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NonProfitOrganization extends Model
{
   
 protected $table = 'non_profit_organization';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['organization_name','organization_short_name','organization_type','working_area','organization_icon','status','is_deleted'];

}
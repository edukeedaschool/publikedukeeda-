<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriberList extends Model
{
   
 protected $table = 'subscriber_list';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['subscriber_name','office_belongs_to','political_party','gender','dob','off_pos_pol_party','rep_area_off_party_pos','elec_off_pos_name','rep_area_elec_off_pos',
    'key_identity1','key_identity2','org_name','auth_person_name','auth_person_designation','email_address','mobile_no','image','address_line1','postal_code','country','state','district','sub_district','village',
    'country_pp','state_pp','district_pp','lac_pp','pc_pp','mc1_pp','mc2_pp','cc_pp','block_pp','ward_pp','sub_district_pp','village_pp',
    'country_eo','state_eo','district_eo','lac_eo','pc_eo','mc1_eo','mc2_eo','cc_eo','block_eo','ward_eo','sub_district_eo','village_eo','status','is_deleted'];

}
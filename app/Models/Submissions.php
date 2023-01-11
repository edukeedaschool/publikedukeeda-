<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submissions extends Model
{
   
 protected $table = 'submissions';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['sub_group_id','place','subscriber_id','submission_type','purpose','nature','subject','summary','file','user_id','submission_status','current_review_level','current_review_range','submission_date','reviewer_id','close_comments','review_completed_comments','close_date','status','is_deleted'];

}
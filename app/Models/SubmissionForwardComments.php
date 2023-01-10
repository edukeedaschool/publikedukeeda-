<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmissionForwardComments extends Model
{
   
 protected $table = 'submission_forward_comments';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['review_level_id_from','review_level_id_to','reviewer_id_from','reviewer_id_to','forward_date','comments','is_deleted'];

}
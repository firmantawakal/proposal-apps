<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;
    protected $table = 'proposal';
    protected $fillable = [
        'user_id',
        'nomor_surat',
        'title',
        'cost',
        'category_id',
        'document',
        'review_id',
        'review_comment',
        'review_status',
        'approve1_id',
        'approve1_comment',
        'approve1_status',
        'approve2_id',
        'approve2_comment',
        'approve2_status',
        'status',
    ];
}

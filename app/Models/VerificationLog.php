<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VerificationLog extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'verification_logs';

    protected $fillable = ['user_id', 'file_type', 'verification_result'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmtpSetting extends Model
{
    use HasFactory;
    protected $table = "smtp_setting";
    public $timestamps = false;
    protected $fillable = [
        'email',
        'password',
        'host',
        'port'
    ];
}

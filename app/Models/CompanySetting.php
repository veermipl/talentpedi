<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    use HasFactory;
    protected $table = "company_setting";
    public $timestamps = false;
    protected $fillable = [
        'logo', 'title', 'address', 'help_center', 'whatspp_num', 'email_support', 'facebook', 'twitter', 'instagram'
    ];
}

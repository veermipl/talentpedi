<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    use HasFactory;
    protected $table = 'competitions';
    public $timestamps = false;

    public function competitionDetails()
    { 
        return $this->hasMany(CompetitionDetail::class, 'comp_id');
    }
}

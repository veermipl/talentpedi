<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitionDetail extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'competition_details';
    protected $primaryKey = 'c_id';
    protected $fillable = [
        'comp_id',
        'title',
        'description',
        'image',
        'comp_date'
    ];
    public function competition()
    {
        return $this->belongsTo(Competition::class, 'comp_id');
    }
}

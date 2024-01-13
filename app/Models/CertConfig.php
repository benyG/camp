<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Course;

class CertConfig extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'mods','quest','timer','course'
      ];
      protected $casts = [
        'mods' => 'array',
    ];
    public function courseRel(): BelongsTo
      {
          return $this->belongsTo(Course::class,'course','id');
      }
}

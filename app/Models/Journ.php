<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Journ extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
      'text','ac','fen'
    ];
    public function userRel(): BelongsTo
    {
   //    return $this->belongsTo(Post::class, 'foreign_key', 'owner_key');
        return $this->belongsTo(User::class,'user','id');
    }
    public static function add($us=null,$fen,$ac,$txt){
        $fl = new Journ;
        $fl->user = isset($us)?$us->id:null;$fl->fen = $fen;$fl->ac = $ac;$fl->text = $txt;
        $fl->save();
    }
}

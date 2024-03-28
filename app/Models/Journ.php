<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stevebauman\Location\Facades\Location;

class Journ extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'text', 'ac', 'fen', 'ip', 'ua', 'loc',

    ];

    public function userRel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user', 'id');
    }

    public static function add($us, $fen, $ac, $txt, $ip = [])
    {
        // dd(Location::get('66.102.0.0'));
        $fl = new Journ;
        $fl->user = isset($us) ? $us->id : null;
        $fl->fen = $fen;
        $fl->ac = $ac;
        $fl->text = $txt;
        $fl->ua = $_SERVER['HTTP_USER_AGENT'];
        if (! empty($ip)) {
            //  && $position = Location::get($ip)
            $fl->loc = $ip['city'].' / '.$ip['country_name'];
            $fl->ip = $ip['ip'];
        } else {
        }
        $fl->save();
    }
}

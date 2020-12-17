<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\SoftDeletes;
class Message extends Model
{
    use SoftDeletes;
    protected $fillable = ['message','from_id','to_id','is_read'];

    public function fromMessage()
    {
        return $this->belongsTo('App\User','from_id');
    }
    public function toMessage()
    {
        return $this->belongsTo('App\User','to_id');
    }
}

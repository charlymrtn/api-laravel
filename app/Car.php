<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    //
    use SoftDeletes;

    protected $table = 'cars';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'title', 'description','price',
    ];

    protected $dates = ['deleted_at'];

    public function user()
    {
      // code...
      return $this->belongsTo('App\User','user_id');
    }
}

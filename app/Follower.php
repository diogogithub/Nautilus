<?php
namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Follower extends Pivot {

  protected $table = 'followers';

  public function profile() {
    return $this->belongsTo('\App\Profile');
  }

  public function user() {
    return $this->belongsTo('\App\User');
  }

}

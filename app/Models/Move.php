<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Move extends Model
{
    //

  public function game() {
    return $this->belongsTo("App\Game");
  }

}

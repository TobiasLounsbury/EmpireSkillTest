<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
  //

  public function moves(){
    return $this->hasMany("App\Move");
  }

  public function playerX(){
    return $this->hasOne("App\User", "id", "player_x_id");
  }

  public function playerY(){
    return $this->hasOne("App\User", "id", "player_y_id");
  }

  public function players(){
    return $this->playerX->merge($this.$this->playerY());
  }


}

<?php

namespace App\Models\Auth;

use App\Models\Auth\Traits\Scope\UserScope;
use App\Models\Auth\Traits\Method\UserMethod;
use App\Models\Auth\Traits\Attribute\UserAttribute;
use App\Models\Auth\Traits\Relationship\UserRelationship;

/**
 * Class User.
 */
class User extends BaseUser
{
    use UserAttribute,
        UserMethod,
        UserRelationship,
        UserScope;

    public function gamesAsX() {
      return $this->hasMany('App\Game', 'player_x_id');
    }

  public function gamesAsY() {
    return $this->hasMany('App\Game', 'player_y_id');
  }

  public function games() {
    return $this->gamesAsX->merge($this->gamesAsY);
  }

  public function winsAsX() {
      return $this->gamesAsX->where("result", "=", "x");
  }

  public function winsAsY() {
    return $this->gamesAsY->where("result", "=", "y");
  }

  public function wins() {
    return $this->winsAsX->merge($this->winsAsY);
  }


  public function draws() {
    return $this->games->where("result", "=", "d");
  }

}

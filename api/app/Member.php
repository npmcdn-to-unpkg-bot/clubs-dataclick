<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    /**
     * Get the clubs of the member
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function clubs()
    {
        return $this->belongsToMany(Club::class)
            ->withTimestamps();
    }

    protected $hidden = ['pivot', 'created_at', 'updated_at'];
}

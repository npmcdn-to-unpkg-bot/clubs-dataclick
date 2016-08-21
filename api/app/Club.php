<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    /**
     * Get the members of the club
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function members()
    {
        return $this->belongsToMany(Member::class)
            ->withTimestamps();
    }

    protected $hidden = ['pivot', 'created_at', 'updated_at'];
}

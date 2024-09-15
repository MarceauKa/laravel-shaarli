<?php

namespace App\Observers;


use App\Models\Wall;

class WallObserver
{
    public function saved(Wall $wall)
    {
        if ($wall->is_default) {
            Wall::where('is_default', true)
                ->where('id', '!=', $wall->id)
                ->update([
                    'is_default' => false,
                ]);
        }
    }
}

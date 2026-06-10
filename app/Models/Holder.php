<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name'])]
class Holder extends Model
{
    /** @use HasFactory<\Database\Factories\HolderFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $table = 'holder';

    public function tracks(): HasMany
    {
        return $this->hasMany(Track::class, 'copyright_holder_id');
    }
}

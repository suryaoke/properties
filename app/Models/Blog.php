<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Blog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'info',
        'photo',
        'slug',
    ];

    // Mutator Laravel lama
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = ucwords($value);
        $this->attributes['slug']  = Str::slug($value);
    }
}

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'photo',
        'property_type_id',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords($value);
        $this->attributes['slug'] = Str::slug($value);
    }


    public function propertyType()
    {
        return $this->belongsTo(PropertyType::class, 'property_type_id');
    }

     public function properties()
    {
        return $this->hasMany(Property::class, 'category_id');
    }
}

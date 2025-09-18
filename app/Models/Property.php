<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class Property extends Model
{
    use SoftDeletes;
    protected $fillable = [
            'name',
            'slug',
            'thumbnail',
            'certificate',
            'about',
            'price',
            'bedrooms',
            'bathrooms',
            'electric',
            'land_area',
            'building_area',
            'address',
            'property_type_id',
            'city_id',
            'category_id',
            'map',
            'status_listing',
            'status_active'
        ];
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords($value);
        $this->attributes['slug'] = Str::slug($value);
    }

    public function photos()
    {
        return $this->hasMany(PropertyPhoto::class);
    }

    public function facilities()
    {
        return $this->hasMany(PropertyFacility::class, 'property_id');
    }
    public function propertyType()
    {
        return $this->belongsTo(PropertyType::class, 'property_type_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
}

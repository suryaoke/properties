<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyFacility extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    protected $fillable = ['property_id', 'facility_id'];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class, 'facility_id');
    }
}

<?php
namespace App\Services;

use App\Models\About;
use App\Models\Category;
use App\Models\City;
use App\Models\Property;
use App\Models\PropertyType;

class PropertyService {


    public function getCategoriesAndCities(){

        return [
            'categories' => Category::latest()->get(),
            'cities' => City::latest()->get(),
            'types' => PropertyType::latest()->get(),
            'about' =>About::first(),
        ];
    }

    public function searchProperties($filters){
        $query = Property::query();

        if(!empty($filters['category'])){
            $query->where('category_id',$filters['category']);
        }
        if(!empty($filters['city'])){
            $query->where('city_id',$filters['city']);
        }
        if(!empty($filters['type'])){
            $query->where('property_type_id',$filters['type']);
        }

        $property = $query->get();
        $category = Category::findOrFail($filters['category'] ?? null);
        $city = City::findOrFail($filters['city'] ?? null);
        $type = PropertyType::findOrFail($filters['type'] ?? null);
        $about = About::first();

        return compact('property','category','city','type','about');
    }
    public function getPropertyDetails($property){
        $property->load(['photos','facilities','category','city','propertyType', 'facilities.facility']);
        return $property;
    }
}

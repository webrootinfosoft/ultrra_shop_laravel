<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    //
    use SoftDeletes;

    protected $fillable = ['name', 'name_spanish', 'name_chinese', 'name_japanese', 'description', 'description_spanish', 'description_chinese', 'description_japanese', 'default_price', 'distributor_price', 'preferred_customer_price', 'retail_customer_price', 'autoship_price', 'available_on', 'sku', 'qv', 'image', 'secondary_image', 'banner_image', 'product_type_id', 'tax_category', 'is_shipping', 'shipping_type', 'is_autoship', 'pusd', 'is_only_admin', 'display_for', 'height', 'width', 'depth', 'weight', 'size', 'seo_keyword', 'seo_description', 'stock', 'quantity', 'minimum_quantity', 'maximum_quantity', 'sort_order', 'background_color', 'product_layout', 'category', 'category_spanish', 'category_chinese', 'category_japanese', 'country_of_origin', 'country_of_origin_spanish', 'country_of_origin_japanese', 'country_of_origin_chinese', 'collection_method', 'collection_method_spanish', 'collection_method_japanese', 'collection_method_chinese', 'plant_part', 'plant_part_spanish', 'plant_part_japanese', 'plant_part_chinese', 'main_constituents', 'main_constituents_spanish', 'main_constituents_japanese', 'main_constituents_chinese', 'aromatic_description', 'aromatic_description_spanish', 'aromatic_description_japanese', 'aromatic_description_chinese', 'product_tag_images', 'tick_points', 'tick_points_spanish', 'tick_points_japanese', 'tick_points_chinese', 'disclaimer', 'disclaimer_spanish', 'disclaimer_japanese', 'disclaimer_chinese', 'status'];

    protected $cloneable_relations = ['productCountries', 'productImages', 'productCategories', 'productAdditionalFields', 'productComponents'];

    protected $clone_exempt_attributes = ['sku'];

    public function getShippingTypeAttribute($value)
    {
        return explode(',', $value);
    }

    public function getDisplayForAttribute($value)
    {
        return explode(',', $value);
    }

    public function getProductTagImagesAttribute($value)
    {
        return !is_null($value) && !empty($value) ? explode(',', $value) : [];
    }

    public function getTickPointsAttribute($value)
    {
        return explode('~', $value);
    }

    public function getTickPointsSpanishAttribute($value)
    {
        return explode('~', $value);
    }

    public function getTickPointsJapaneseAttribute($value)
    {
        return explode('~', $value);
    }

    public function getTickPointsChineseAttribute($value)
    {
        return explode('~', $value);
    }

    public function productType()
    {
        return $this->belongsTo('App\ProductType');
    }

    public function productCountries()
    {
        return $this->hasMany('App\ProductCountry');
    }

    public function productImages()
    {
        return $this->hasMany('App\ProductImage');
    }

    public function productCategories()
    {
        return $this->belongsToMany('App\ProductCategory', 'product_category', 'product_id', 'product_category_id');
    }

    public function productAdditionalFields()
    {
        return $this->hasMany('App\ProductAdditionalField');
    }

    public function productComponents()
    {
        return $this->belongsToMany('App\Product', 'product_component', 'product_id', 'component_id')->withPivot('quantity');
    }

    public function orderProducts()
    {
        return $this->hasMany('App\OrderProduct');
    }
}

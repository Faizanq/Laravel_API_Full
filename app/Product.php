<?php

namespace App;

use App\Category;
use App\Seller;
use App\Transaction;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use SoftDeletes;

	const AVAILABLE_PRODUCT = 1;
	const UNAVAILABLE_PRODUCT = 0;
    protected $fillable =[
    		'name',
    		'description',
    		'quantity',
    		'status',
    		'image',
    		'seller_id'
    ];

    protected $dates = ['deleted_at'];

    public function isAvailable(){
    	$this->status == Product::AVAILABLE_PRODUCT;
    }
    public function categories(){
    	return $this->belongsToMany(Category::class);
    }
    public function seller(){
    	return $this->belongsTo(Seller::class);
    }
    public function transactions(){
    	return $this->hasMany(Transaction::class);
    }
}

<?php
namespace app\Facade;

use App\Reposotires\Cart\CartRepository;
use Facade;

class Cart extends \Illuminate\Support\Facades\Facade
{ 
    protected static function getFacadeAccessor()
    {
      return CartRepository::class;
    }
}
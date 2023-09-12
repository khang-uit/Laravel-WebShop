<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\Product\ProductService;
use App\Http\Services\Menu\MenuService;

class ProductController extends Controller
{
    protected $productService;
    protected $menuService;

    public function __construct(ProductService $productService, MenuService $menuService)
    {
        $this->productService = $productService;
        $this->menuService = $menuService;
    }

    public function index($id = '', $slug = '')
    {
        $product = $this->productService->show($id);

        $reviews = $product->review()->get();

        $productsMore = $this->productService->more($id);

        return view('products.content', [
            'title' => $product->name,
            'product' => $product,
            'menus' => $this->menuService->show(),
            'products' => $productsMore,
            'reviews' => $reviews
        ]);
    }
}

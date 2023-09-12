<?php


namespace App\Http\Services\Product;


use App\Models\Product;

class ProductService
{
    const LIMIT = 8;

    public function get($page = null)
    {
        return Product::select('id', 'name', 'price', 'price_sale', 'thumb')
            ->orderByDesc('id')
            ->when($page != null, function ($query) use ($page) {
                $query->offset($page * self::LIMIT);
            })
            ->where('active', 1)
            ->limit(self::LIMIT)
            ->get();
    }

    public function show($id)
    {
        $product = Product::where('id', $id)
        ->where('active', 1)
        ->with('menu')
        ->with('review')
        ->firstOrFail();

        $product->views = $product->views + 1;
        $product->save();

        return $product;
    }

    public function more($id)
    {
        return Product::select('id', 'name', 'price', 'price_sale', 'thumb')
            ->where('active', 1)
            ->where('id', '!=', $id)
            ->orderByDesc('id')
            ->limit(8)
            ->get();
    }

    public function getSearch($keywords)
    {
        return Product::select('id', 'name', 'price', 'price_sale', 'thumb')
            ->where('active', 1)
            ->where('name', 'like', '%' .$keywords. '%')
            ->get();
    }
}

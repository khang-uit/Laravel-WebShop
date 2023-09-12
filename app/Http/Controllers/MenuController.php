<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\Menu\MenuService;
use App\Models\Product;

class MenuController extends Controller
{
    protected $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    public function index(Request $request, $id, $slug = '')
    {
        $menu = $this->menuService->getId($id);

        $products = $this->menuService->getProduct($menu, $request);

        return view('menu', [
            'title' => $menu->name,
            'products' => $products,
            'menus' => $this->menuService->show(),
            'menu' => $menu
        ]);
    }

    public function autoComplete(Request $request)
    {
        $data = $request->all();

        if($data['query']){
            $products = Product::where('active', 1)->where('name', 'LIKE', '%'.$data['query'].'%')->get();
            $output = '
                <ul class="dropdown-menu" style="display:block; position:relative">
            ';

            foreach($products as $key => $product){
                $output .= '
                    <li class="li-search-ajax" ><a href="#">'.$product->name.'</a></li>
                ';
            }
            $output .= '
                </ul>
            ';

            echo $output;
        }
        
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Item;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $tableNumber = $request->query('meja');
        if($tableNumber){
            session()->put('table_number', $tableNumber);
        }

        $items = Item::where('is_active', 1)->orderBy('name', 'asc')->get();
        return view('customer.menu', compact('items', 'tableNumber'));
    }

    public function cart(Request $request)
    {
        // $tableNumber = session()->get('table_number');
        // if(!$tableNumber){
        //     return redirect()->route('customer.menu')->with('error', 'Silahkan masukkan nomor meja terlebih dahulu');
        // }

        // $item = Item::find($request->item_id);
        // if(!$item){
        //     return redirect()->route('customer.menu')->with('error', 'Item tidak ditemukan');
        // }

        // $cart = session()->get('cart', []);
        // $cart[$item->id] = [
        //     'name' => $item->name,
        //     'price' => $item->price,
        //     'quantity' => 1,
        // ];
        // session()->put('cart', $cart);
        // return redirect()->route('customer.menu')->with('success', 'Item berhasil ditambahkan ke keranjang');
        $cart = session()->get('cart', []);
        return view('customer.cart', compact('cart'));
    }

    public function addToCart(Request $request)
    {
        $menuId = $request->input('id');
        $menu = Item::find($menuId);

        if(!$menu){
            return response()->json([
                'status' => 'error',
                'message' => 'Menu tidak ditemukan'
            ]);
        }

        $cart = session()->get('cart', []);

        if(isset($cart[$menu->id])){
            $cart[$menu->id]['quantity']++;
        }else{
            $cart[$menu->id] = [
                'id' => $menu->id,
                'name' => $menu->name,
                'price' => $menu->price,
                'image' => (str_starts_with($menu->image, 'http') ? $menu->image : asset('img_item_upload/' . $menu->image)),
                'quantity' => 1,
            ];
        }
        session()->put('cart', $cart);
        return response()->json([
            'status' => 'success',
            'message' => 'Item berhasil ditambahkan ke keranjang',
            'cart' => $cart
        ]);
    }
}

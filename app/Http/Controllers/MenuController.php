<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
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
            'success' => true,
            'status' => 'success',
            'message' => 'Item berhasil ditambahkan ke keranjang',
            'cart' => $cart
        ]);
    }

    public function removeFromCart(Request $request)
    {   
        $itemId = $request->input('id');

        $cart = session()->get('cart', []);
        if(isset($cart[$itemId])){
            unset($cart[$itemId]);
            session()->put('cart', $cart);
            return response()->json([
                'success' => true,
                'message' => 'Item berhasil dihapus dari keranjang',
                'cart' => $cart
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Item tidak ditemukan'
        ]);
    }

    public function updateCart(Request $request)
    {
        $itemId = $request->input('id');
        $qty = $request->input('qty');

        $cart = session()->get('cart', []);
        if(isset($cart[$itemId])){
            $cart[$itemId]['quantity'] = $qty;
            session()->put('cart', $cart);
            return response()->json([
                'success' => true,
                'message' => 'Keranjang berhasil diupdate',
                'cart' => $cart
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Item tidak ditemukan'
        ]);
    }

    public function clearCart()
    {
        session()->forget('cart');
        return redirect()->route('cart')->with('success', 'Keranjang berhasil dikosongkan');
    }
    
    public function processCheckout(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:20',
            'table_number' => 'required|integer|min:1',
            'payment_method' => 'required|in:tunai,qris',
            'notes' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }
        
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Keranjang kosong'
            ], 400);
        }
        
        // Calculate totals
        $subtotal = 0;
        foreach ($cart as $item) {
            $itemTotal = $item['price'] * $item['quantity'];
            $subtotal += $itemTotal;
        }
        
        $tax = $subtotal * 0.1;
        $grandTotal = $subtotal + $tax;
        
        // Generate order code
        $orderCode = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(md5(rand()), 0, 6));
        
        try {
            // Create order (DBTransaction: order + order_items must be atomic)
            DB::transaction(function () use ($request, $cart, $subtotal, $tax, $grandTotal, $orderCode) {
                $order = Order::create([
                    'order_code' => $orderCode,
                    'user_id' => auth()->id() ?? 1,
                    'subtotal' => $subtotal,
                    'tax' => $tax,
                    'grand_total' => $grandTotal,
                    'status' => 'pending',
                    'table_number' => $request->table_number,
                    'payment_method' => $request->payment_method,
                    'notes' => $request->notes,
                ]);

                foreach ($cart as $item) {
                    $itemTotal = $item['price'] * $item['quantity'];

                    OrderItem::create([
                        'order_id' => $order->id,
                        'item_id' => $item['id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'tax' => (int) round($itemTotal * 0.1),
                        'total_price' => $itemTotal + (int) round($itemTotal * 0.1),
                    ]);
                }
            });

            // Clear cart
            session()->forget('cart');

            return response()->json([
                'success' => true,
                'order_code' => $orderCode,
                'message' => 'Pesanan berhasil dibuat'
            ]);
        } catch (\Exception $e) {
            \Log::error('Checkout error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkoutSuccess($orderId)
    {
        $order = Order::where('order_code', $orderId)->first();

        if (!$order) {
            return redirect()->route('menu')->with('error', 'Pesanan tidak ditemukan');
        }
        $orderItems = OrderItem::where('order_id', $order->id)->get();

        return view('customer.success', compact('order', 'orderItems'));
    }

}

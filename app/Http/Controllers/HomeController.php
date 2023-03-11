<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;

class HomeController extends Controller
{
    //second
    public function index()
    {
       // $product = Product::all();
        $product = Product::paginate(6);
        return view('home.userpage',compact('product'));
    }

    //first
    public function redirect()
    {
        $usertype = Auth::user()->usertype;  //here usertype from database

        if($usertype == '1')
        {
            return view('admin.home');
        }
        else
        {
           // return view('dashboard'); //dashboard comes resources->view->dashboard
           // return view('home.userpage'); //dashboard comes resources->view->dashboard
           $product = Product::paginate(6);
           return view('home.userpage',compact('product'));
        }
    }

    /**
     * product details
     */

     public function product_details($id)
     {
        $product = Product::find($id);
        return view('home.product_details',compact('product'));
     }

     /**
      * add cart
      */
     public function add_cart(Request $request,$id)
     {
        if(Auth::id())
        {
            //return redirect()->back();
            $user = Auth::user();
            //dd($user);
            $product = Product::find($id);
            //dd($product);
            $cart = new Cart;
            $cart->name = $user->name;
            $cart->email = $user->email;
            $cart->phone = $user->phone;
            $cart->address = $user->address;
            $cart->user_id = $user->id;

            $cart->product_title = $product->title;
            if($product->discount_price!=null){
                $cart->price = $product->discount_price *  $request->quantity;
            }
            else 
            {
                $cart->price = $product->price *  $request->quantity;
            }
            $cart->image = $product->image;
            $cart->Product_id = $product->id;
            $cart->quantity = $request->quantity;

            $cart->save();
            return redirect()->back();
        }

        else
        {
            return redirect('login');
        }
     }
   
}

<?php
namespace App\Http\Controllers;

use Request;
use App\Product;
use App\Order;
use App\MeasureUnit;
use App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use View;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return Response
    */
   public function index()
   {
      $products = Product::all();
      return view('products.index',compact('products'));
   }
   /**
    * Show the form for creating a new resource.
    *
    * @return Response
    */
   public function create()
   {
     //$measure_units = MeasureUnit::all(['id', 'name']);
	 $measure_units = MeasureUnit::lists('name', 'id');
	 $measure_units = $measure_units->toArray();
	
	
	  return view('products.create',compact('measure_units'));
   }
   /**
    * Store a newly created resource in storage.
    *
    * @return Response
    */
   public function store(Request $request)
   {
       
	   // validate
        // read more on validation at http://laravel.com/docs/validation
			$rules = array(
				'name'       => 'required',
				'id_uom'      => 'required',
				'price_per_unit' => 'required',
				'qty_in_stock' => 'required'
			);
        
		   $products = Request::all();	 
		   Product::create($products);
		   return redirect('products');
		
	   
	   
   }
   /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return Response
    */
   public function show($id)
   {
      //
   }

   /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return Response
    */
  public function edit($id)
	{
	   $products = Product::findOrFail($id);
	   $measure_units = MeasureUnit::lists('name', 'id');
	   $measure_units = $measure_units->toArray();
	   return view('products.edit',compact(['products', 'measure_units']));
	   
	}
   /**
    * Update the specified resource in storage.
    *
    * @param  int  $id
    * @return Response
    */
   public function update(Request $request, $id)
   {
      $productUpdate = $request::all();
	  $product = Product::find($id);
      $product->update($productUpdate);
	  return redirect('products');
   }
   /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return Response
    */
   public function destroy($id)
   {
      Product::find($id)->delete();
	  return redirect('products');
   }
   

   public function order(){
		
	   $products = Product::all();
	   $measure_units = MeasureUnit::lists('name', 'id');
	   $measure_units = $measure_units->toArray();
	   return view('products.order',compact(['products', 'measure_units']));
   }

   /**
    * ajax request to get credit info
    */
	public function creditcheck(Request $request){
		$products = Request::all();	 
		$available = 1;
		$amt=1;
		$amount	= 1000;
		$sum_amt= 0;
		$id_user = Auth::user()->id;
		$tax = 0;
        foreach($products['pid'] as $val){
			$productArr	= Product::find($val)->toArray();
	
			if($products['qty_in_stock_'.$val] > $productArr['qty_in_stock']){
				$available=0;
				break;
			}else{
				$sum_amt = $sum_amt + ($products['qty_in_stock_'.$val] * $productArr['price_per_unit']);
			}
		}
			
		if($sum_amt > $amount){
			$amt=0;	
		}
		
		//insert into order table
		$order_total = $tax + $sum_amt;		
		$orders = array('id_user'=>$id_user,'id_customer'=>$id_user,'total_cost'=>$sum_amt,'tax'=>$tax,'order_total'=>$order_total);
		
		Order::create($orders);
		$id_order = 10;
		//INSERT into order_line table
		if($amt ==1 && $available == 1){
			foreach($products['pid'] as $val){
				$order_line = array('id_order'=>$products['pid'],'id_product'=>$id_user,'qty'=>$products['qty_in_stock_'.$val,'sale_price_per_unit'=>$tax)
			}
		}
		
		//reduce quantity from product table
		//$request->session()->flash('alert-success', 'Order has been done successfully!');
		//return Redirect::to('products/order');
		return redirect('products/order');
		//if($pid && $uid){
			
			
			//if()
			//$userArr	= User::find($pid);
			//return response()->json($productArr);
		//}	
   	}

}

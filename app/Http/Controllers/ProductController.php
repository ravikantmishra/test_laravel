<?php
namespace App\Http\Controllers;

use Request;
use App\Product;
use App\MeasureUnit;
use App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
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
		 //$products = DB::table('user')->select('user.id','user.name','profile.photo')->join('profile','profile.id','=','user.id')->where('something','=','something')->where('oherthing','=','otherthing')->get();
	   $products = Product::all();
	   $measure_units = MeasureUnit::lists('name', 'id');
	   $measure_units = $measure_units->toArray();
	   return view('products.order',compact(['products', 'measure_units']));
   }
}

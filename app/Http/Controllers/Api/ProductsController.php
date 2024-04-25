<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Products;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    
    public function index() {
        $products = Products::all();

        if($products->isEmpty()) {
           $data = [
            'message' => 'No se encontraron productos',
            'status' => 200
           ];

           return response()->json($data, 200);
        }

        return response()->json($products, 200);
    }

    public function store(Request $request) {
       $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'marca' => 'required',
            'categoria' => 'required',
            'cantidad' => 'required',
            'precio' => 'required',
            'vencimiento' => 'required',
            'provedor' => 'required'
       ]);

       if($validator->fails()) {

            $data = [
                'message' => 'Error en la validación de datos',
                'error' => $validator->errors(),
                'status' => 400
            ];

            return response()->json($data, 400);
        
       }

       $product = Products::create([
            'nombre' => $request->nombre,
            'marca' => $request->marca,
            'categoria' => $request->categoria,
            'cantidad' => $request->cantidad,
            'precio' => $request->precio,
            'vencimiento' => $request->vencimiento,
            'provedor' => $request->provedor
       ]);

       if(!$product) {
            $data = [
                'message' => 'No se pudo crear el producto',
                'status' => 500
            ];
            return response()->json($data, 500);
       }

       $data = [
        'producto' => $product,
        'status' => 201
       ];

       return response()->json($data, 201);
    }

    public function show($id) {
        $product = Products::find($id);

        if(!$product) {
            $data = [
                'message' => 'Producto no encontrado!',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $data = [
            'product' => $product,
            'status' => 200 
        ];

        return response()->json($data, 200);
    }

    public function destroy($id) {
        $product = Products::find($id);

        if(!$product) {
            $data = [
                'message' => 'Producto no encontrado!',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $product->delete();

        $data = [
            'message' => 'Producto Eliminado',
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function update(Request $request, $id) {
        $product = Products::find($id);

        if(!$product) {
            $data = [
                'message' => 'Producto no encontrado!',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'marca' => 'required',
            'categoria' => 'required',
            'cantidad' => 'required',
            'precio' => 'required',
            'vencimiento' => 'required',
            'provedor' => 'required'
       ]);

       if($validator->fails()) {

            $data = [
                'message' => 'Error en la validación de datos',
                'error' => $validator->errors(),
                'status' => 400
            ];

            return response()->json($data, 400);
       }

       $product->nombre = $request->nombre;
       $product->marca = $request->marca;
       $product->categoria = $request->categoria;
       $product->cantidad = $request->cantidad;
       $product->precio = $request->precio;
       $product->vencimiento = $request->vencimiento;
       $product->provedor = $request->provedor;

       $product->save();

       $data = [
        'message' => 'Producto Actualizado',
        'producto' => $product,
        'status' => 200
       ];

       return response()->json($data, 200);
    }

}

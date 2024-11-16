<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Category;

class RestaurantController extends Controller
{
    /*店舗一覧ページ*/
    public function index(Request $request) {

        $keyword = $request->input('keyword');

        if ($keyword !== null) {
            $restaurants=Restaurant::where('name','LIKE',"%{$keyword}%")
                       ->paginate(15);
            $total=$restaurants->total();
        }else{
            $restaurants=Restaurant::paginate(15);
            $total=0;
            $keyword=null;
        }   
        // ビューに変数を渡して返します
        return view('admin.restaurants.index', compact('restaurants', 'keyword', 'total'));
    }

    /*店舗詳細ページ*/
    public function show(Restaurant $restaurant) {

        return view('admin.restaurants.show', compact('restaurant'));
    }

    /*店舗登録ページ*/
    public function create() {
        $categories = Category::all();
        return view('admin.restaurants.create',compact('categories'));

    }

    /*店舗登録機能*/
    public function store(Request $request) {
        $validatedData = $request->validate([
            // 入力必須
            'name' => 'required',
            // 画像ファイル（jpg、jpeg、png、bmp、gif、svg、webp）のみ許可、最大値2048キロバイト
            'image' => 'image|max:2048',
            'description' => 'required',
            // 入力必須、数値のみ許可、最小値0、highest_price以下
            'lowest_price' => 'required|numeric|min:0|lte:highest_price',
            // 入力必須、数値のみ許可、最小値0、lowest_price以上
            'highest_price' => 'required|numeric|min:0|gte:lowest_price',
            // 入力必須、数値かつ桁数7
            'postal_code' => 'required|numeric|digits:7',
            'address' => 'required',
            // 入力必須、closing_timeより前の時間
            'opening_time' => 'required|before:closing_time',
            // 入力必須、opening_timeより後の時間
            'closing_time' => 'required|after:opening_time',
            // 入力必須、数値のみ許可、最小値0
            'seating_capacity' => 'required|numeric|min:0',
        ]);

        $restaurant = new Restaurant();

        $restaurant->name = $validatedData['name'];
        $restaurant->description = $validatedData['description'];
        $restaurant->lowest_price = $validatedData['lowest_price'];
        $restaurant->highest_price = $validatedData['highest_price'];
        $restaurant->postal_code = $validatedData['postal_code'];
        $restaurant->address = $validatedData['address'];
        $restaurant->opening_time = $validatedData['opening_time'];
        $restaurant->closing_time = $validatedData['closing_time'];
        $restaurant->seating_capacity = $validatedData['seating_capacity'];

        if($request->hasFile('image')) {
            $image_path = $request->file('image')->store('public/restaurants');
            $restaurant->image = basename($image_path);
        } else {
            $restaurant->image = '';
        }

        $restaurant->save();

        $category_ids = array_filter($request->input('category_ids', []));
        $restaurant->categories()->sync($category_ids);

        return redirect()->route('admin.restaurants.index')->with('flash_message', '店舗を登録しました。');
    }

    /*店舗編集*/
    public function edit(Restaurant $restaurant) {
        $categories = Category::all();
        $category_ids = $restaurant->categories->pluck('id')->toArray();
        return view('admin.restaurants.edit', compact('restaurant', 'categories', 'category_ids'));
    }

    /*店舗更新機能*/
    public function update(Request $request, Restaurant $restaurant)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'image' => 'nullable|image|max:2048',
            'description' => 'required',
            'lowest_price' => 'required|numeric|min:0|lte:highest_price',
            'highest_price' => 'required|numeric|min:0|gte:lowest_price',
            'postal_code' => 'required|numeric|digits:7',
            'address' => 'required',
            'opening_time' => 'required|before:closing_time',
            'closing_time' => 'required|after:opening_time',
            'seating_capacity' => 'required|numeric|min:0',
        ]);

        $restaurant->name = $validatedData['name'];
        $restaurant->description = $validatedData['description'];
        $restaurant->lowest_price = $validatedData['lowest_price'];
        $restaurant->highest_price = $validatedData['highest_price'];
        $restaurant->postal_code = $validatedData['postal_code'];
        $restaurant->address = $validatedData['address'];
        $restaurant->opening_time = $validatedData['opening_time'];
        $restaurant->closing_time = $validatedData['closing_time'];
        $restaurant->seating_capacity =$validatedData['seating_capacity'];

        if($request->hasFile('image')) {
            $image_path = $request->file('image')->store('public/restaurants');
            $restaurant->image = basename($image_path);
        }

        $restaurant->save();

        $category_ids = array_filter($request->input('category_ids', []));
        $restaurant->categories()->sync($category_ids);

        return redirect()->route('admin.restaurants.show', $restaurant)->with('flash_message', '店舗を編集しました。');

    }

    /*店舗削除機能*/
    public function destroy(Restaurant $restaurant)
    {
        $restaurant->delete();

        return redirect()->route('admin.restaurants.index')->with('flash_message', '店舗を削除しました。');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{


    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [

            'title' => 'required',
            'title_en' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'sort' => 'required|numeric',
            'cat_id' => 'required|numeric',
            'pic' => ['required', 'max:10240'],
        ]);
        if ($validate->fails()) {
            return Utils::errorResponse(1, $validate->errors()->messages());
        }
        $item = new Item();
        $file = $request->file('pic');
        $rand = rand();
        $item->pic = $rand . '-' . $file->getClientOriginalName();
        $path = '../../img';
        $file->move($path, $rand . '-' . $file->getClientOriginalName());
        $item->title = $request->title;
        $item->title_en = $request->title_en;
        $item->description = $request->description;
        $item->price = $request->price;
        $item->cat_id = $request->cat_id;
        $item->sort = $request->sort;
        try {
            $saved = $item->save();
        } catch (\Exception $exception) {
            if ($exception->getCode() == 23000) {
                return Utils::errorResponse(3, 'Cat Not Found');
            }
        }
        if (!$saved) {
            return Utils::DatabaseError();
        }
        return Utils::successResponse($item);
    }


    /**
     * @urlParam id int آیدی تبلیغ Example: 1
     * @bodyParam title string عنوان تبلیغ Example: Task One
     * @bodyParam link string آدرس تبلیغ Example: low
     * @bodyParam pic int تصویر تبلیغ Example: 1
     *
     * @response {
     * "pic": "photo_2020-08-19_21-57-12.jpg",
     * "title": "add one",
     * "link": "google",
     * "time_updated": "1603197408",
     * "time_created": "1603197408",
     * "id": 1
     * }
     *
     * @response {
     * "id": "int آیدی تبلیغ با نوع",
     * "title": "string عنوان تبلیغ با نوع",
     * "link": "string آدرس تبلیغ با نوع",
     * "pic": "string تصویر تبلیغ با نوع",
     * "time_created": "int زمان ساخت تبلیغ با نوع",
     * "time_updated": "int زمان بروز رسانی تبلیغ با نوع",
     * "deleted_at": "timestamp زمان حذف تبلیغ با نوع"
     * {
     */

    public function update(Request $request, $id)
    {
        $item = Item::whereId($id)->first();
        if (is_null($item)) {
            return Utils::errorResponse(4, 'Item not found');
        }

        $validate = Validator::make($request->all(), [

            'price' => 'numeric',
            'sort' => 'numeric',
            'cat_id' => 'numeric',
            'pic' => ['max:10240'],
        ]);
        if ($validate->fails()) {
            return Utils::errorResponse(1, $validate->errors()->messages());
        }

        $itmes = ['title', 'title_en', 'description', 'price', 'cat_id', 'sort'];
        foreach ($request->all() as $param => $val) {
            if (in_array($param, $itmes)) {
                $item->{$param} = $val;
            }
        }
        if ($request->hasFile('pic')) {
            unlink('../../img/' . $item->pic);
            $file = $request->file('pic');
            $rand = rand();
            $item->pic = $rand . '-' . $file->getClientOriginalName();
            $path = '../../img';
            $file->move($path, $rand . '-' . $file->getClientOriginalName());
        }
        try {
            $saved = $item->save();
        } catch (\Exception $exception) {
            if ($exception->getCode() == 23000) {
                return Utils::errorResponse(3, 'Cat Not Found');
            }
        }
        if (!$saved) {
            return Utils::DatabaseError();
        }
        return Utils::successResponse($item);
    }

    /**
     * @urlParam id آیدی تبلیغ
     *
     * @response {
     *  "message": "successful"
     * }
     *
     */

    public function destroy($id)
    {
        $item = Item::whereId($id)->first();
        if (is_null($item)) {

            return Utils::errorResponse(4, 'Item not found');
        }
        $deleted = $item->delete();
        if (!$deleted) {
            return Utils::DatabaseError();
        }
        return Utils::successResponse('successful');
    }

    /**
     * @urlParam id int آیدی تبلیغ Example: 1
     *
     * @response {
     * "pic": "photo_2020-08-19_21-57-12.jpg",
     * "title": "add one",
     * "link": "google",
     * "time_updated": "1603197408",
     * "time_created": "1603197408",
     * "id": 1
     * }
     *
     * @response {
     * "id": "int آیدی تبلیغ با نوع",
     * "title": "string عنوان تبلیغ با نوع",
     * "link": "string آدرس تبلیغ با نوع",
     * "pic": "string تصویر تبلیغ با نوع",
     * "time_created": "int زمان ساخت تبلیغ با نوع",
     * "time_updated": "int زمان بروز رسانی تبلیغ با نوع",
     * "deleted_at": "timestamp زمان حذف تبلیغ با نوع"
     * {
     */

    public function get($id)
    {
        $item = Item::whereId($id)->with('cat')->first();
        if (is_null($item)) {
            return Utils::errorResponse(4, 'Item not found');
        }
        return Utils::successResponse($item);
    }

    /**
     * @response {
     * {
     * "id": 1,
     * "title": "add one",
     * "link": "iren.ir",
     * "pic": "photo_2020-08-19_21-57-12.jpg",
     * "time_created": "1603197408",
     * "time_updated": "1603198188",
     * "deleted_at": null
     * },
     * {
     * "id": 2,
     * "title": "add two",
     * "link": "aparat",
     * "pic": "photo_2020-08-19_21-57-12.jpg",
     * "time_created": "1603197637",
     * "time_updated": "1603197637",
     * "deleted_at": null
     * }
     * }
     *
     * @response {
     * "id": "int آیدی تبلیغ با نوع",
     * "title": "string عنوان تبلیغ با نوع",
     * "link": "string آدرس تبلیغ با نوع",
     * "pic": "string تصویر تبلیغ با نوع",
     * "time_created": "int زمان ساخت تبلیغ با نوع",
     * "time_updated": "int زمان بروز رسانی تبلیغ با نوع",
     * "deleted_at": "timestamp زمان حذف تبلیغ با نوع"
     * {
     */

    public function list()
    {
        $item = Item::orderBy('sort', 'asc')->orderBy('time_created', 'asc')->get();
        return Utils::successResponse($item);
    }
}

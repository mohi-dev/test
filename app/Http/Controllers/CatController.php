<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use App\Models\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CatController extends Controller
{


    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [

            'sort' => ['required', 'numeric'],
            'title' => 'required',
            'description' => 'required',
            'pic' => ['required', 'max:10240'],
        ]);
        if ($validate->fails()) {
            return Utils::errorResponse(1, $validate->errors()->messages());
        }
        $cat = new Cat();
        $file = $request->file('pic');
        $rand = rand();
        $cat->pic = $rand . '-' . $file->getClientOriginalName();
        $path = '../../img';
        $file->move($path, $rand . '-' . $file->getClientOriginalName());
        $cat->title = $request->title;
        $cat->description = $request->description;
        $cat->sort = $request->sort;
        $saved = $cat->save();
        if (!$saved) {
            return Utils::DatabaseError();
        }
        return Utils::successResponse($cat);
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
        $cat = Cat::whereId($id)->first();
        if (is_null($cat)) {
            return Utils::errorResponse(3, 'Cat not found');
        }

        $validate = Validator::make($request->all(), [

            'sort' => 'numeric',
            'pic' => ['max:10240'],
        ]);
        if ($validate->fails()) {
            return Utils::errorResponse(1, $validate->errors()->messages());
        }

        $itmes = ['title', 'sort', 'description'];
        foreach ($request->all() as $param => $val) {
            if (in_array($param, $itmes)) {
                $cat->{$param} = $val;
            }
        }
        if ($request->hasFile('pic')) {
            unlink('../../img/' . $cat->pic);
            $file = $request->file('pic');
            $rand = rand();
            $cat->pic = $rand . '-' . $file->getClientOriginalName();
            $path = '../../img';
            $file->move($path, $rand . '-' . $file->getClientOriginalName());
        }
        $saved = $cat->save();
        if (!$saved) {
            return Utils::DatabaseError();
        }
        return Utils::successResponse($cat);
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
        $cat = Cat::whereId($id)->first();
        if (is_null($cat)) {

            return Utils::errorResponse(2, 'Cat not found');
        }
        $deleted = $cat->delete();
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
        $cat = Cat::whereId($id)->first();
        if (is_null($cat)) {
            return Utils::errorResponse(2, 'Cat not found');
        }
        $item = $cat->items()->orderBy('sort', 'asc')->orderBy('time_created', 'asc')->get();
        $res = [$cat, 'items' => $item];
        return Utils::successResponse($res);
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
        $cat = Cat::orderBy('sort', 'asc')->orderBy('time_created', 'asc')->get();
        return Utils::successResponse($cat);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Advertise;
use App\Models\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdvertiseController extends Controller
{

    /**
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

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [

            'link' => 'required',
            'pic' => ['required', 'max:10240'],
        ]);
        if ($validate->fails()) {
            return Utils::errorResponse(1, $validate->errors()->messages());
        }
        $advertise = new Advertise();
        $file = $request->file('pic');
        $rand = rand();
        $advertise->pic = $rand . '-' . $file->getClientOriginalName();
        $path = '../../img';
        $file->move($path, $rand . '-' . $file->getClientOriginalName());
        $advertise->link = $request->link;
        $saved = $advertise->save();
        if (!$saved) {
            return Utils::DatabaseError();
        }
        return Utils::successResponse($advertise);
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
        $advertise = Advertise::whereId($id)->first();
        if (is_null($advertise)) {
            return Utils::errorResponse(2, 'advertise not found');
        }

        $validate = Validator::make($request->all(), [

            'pic' => ['max:10240'],
        ]);
        if ($validate->fails()) {
            return Utils::errorResponse(1, $validate->errors()->messages());
        }

        $itmes = ['link'];
        foreach ($request->all() as $param => $val) {
            if (in_array($param, $itmes)) {
                $advertise->{$param} = $val;
            }
        }
        if ($request->hasFile('pic')) {
            unlink('../../img/' . $advertise->pic);
            $file = $request->file('pic');
            $rand = rand();
            $advertise->pic = $rand . '-' . $file->getClientOriginalName();
            $path = '../../img';
            $file->move($path, $rand . '-' . $file->getClientOriginalName());
        }
        $saved = $advertise->save();
        if (!$saved) {
            return Utils::DatabaseError();
        }
        return Utils::successResponse($advertise);
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
        $advertise = Advertise::whereId($id)->first();
        if (is_null($advertise)) {

            return Utils::errorResponse(2, 'advertise not found');
        }
        $deleted = $advertise->delete();
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
        $advertise = Advertise::whereId($id)->first();
        if (is_null($advertise)) {
            return Utils::errorResponse(2, 'advertise not found');
        }
        return Utils::successResponse($advertise);
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
        $advertise = Advertise::get();
        return Utils::successResponse($advertise);
    }
}

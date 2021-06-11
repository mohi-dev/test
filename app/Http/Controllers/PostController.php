<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function create(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'data' => 'required',
        ]);
        if ($validate->fails()) {
            return Utils::errorResponse(406, $validate->errors()->messages());
        }
        $data = $request->get('data');
        $post = new Post();
        $post->data = $data;
        try {
            $saved = $post->save();
        } catch (\Exception $exception) {
            return Utils::errorResponse(500, $exception->getMessage());
        }
        if (!$saved) {
            return Utils::DatabaseError();
        }
        return Utils::successResponse(['post_id' => $post->id]);
    }

    public function update(Request $request, $id)
    {
        $post = Post::whereId($id)->first();
        if (is_null($post)) {
            return Utils::errorResponse(404, 'post not found');
        }

        $validate = Validator::make($request->all(), [
            'data' => 'string'
        ]);
        if ($validate->fails()) {
            return Utils::errorResponse(406, $validate->errors()->messages());
        }

        if (!is_null($request->get('data'))) {
            $post->data = $request->get('data');
        }
        try {
            $saved = $post->save();
        } catch (\Exception $exception) {
            return Utils::errorResponse(500, $exception->getMessage());
        }
        if (!$saved) {
            return Utils::DatabaseError();
        }
        return Utils::successResponse($post);
    }

    public function remove($id)
    {
        $post = Post::whereId($id)->first();
        if (is_null($post)) {
            return Utils::errorResponse(404, 'post not found');
        }
        $deleted = $post->delete();
        if (!$deleted) {
            return Utils::DatabaseError();
        }
        return Utils::successResponse('successful');
    }

    public function get($id)
    {
        $post = Post::whereId($id)->first();
        if (is_null($post)) {
            return Utils::errorResponse(404, 'post not found');
        }
        return Utils::successResponse($post);
    }

    public function list(Request $request)
    {
        $per_page = $request->get('per_page', 5);
        $data = $request->get('data');
        $order_by = $request->get('order_by', 'time_created');
        $sort_by = strtoupper($request->get('sort_by', 'desc'));
        $time_created_min = $request->get('time_created_min');
        $time_created_max = $request->get('time_created_max');

        $validate = Validator::make($request->all(), [
            'order_by' => 'in:time_created,time_updated',
            'sort_by' => 'in:asc,desc',
            'per_page' => 'numeric',
        ]);

        if ($validate->errors()->has('order_by')) {
            $order_by = 'time_created';
        }

        if ($validate->errors()->has('sort_by')) {
            $sort_by = 'DESC';
        }

        if ($validate->errors()->has('per_page')) {
            $per_page = 5;
        }

        $posts = new Post();

        if (!is_null($data)) {
            $posts = $posts->where('data', 'like', '%' . $data . '%');
        }

        if (!is_null($time_created_min)) {
            $posts = $posts->where('time_created', '>', $time_created_min);
        }

        if (!is_null($time_created_max)) {
            $posts = $posts->where('time_created', '<', $time_created_max);
        }

        $posts = $posts->orderBy($order_by, $sort_by);
        return Utils::successResponse($posts->paginate($per_page));
    }
}

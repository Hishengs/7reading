<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EssayController extends Controller
{
  function index(Request $req){
    $essay = DB::table('essay')
                ->inRandomOrder()
                ->first();
    return view('random', ['essay' => $essay]);
  }

  // 获取文章列表
  function getList(Request $req){
    $essays = DB::table('essay')
    ->select('id', 'title', 'author', 'created_at', 'updated_at', 'content', 'type'/*, DB::raw('left(content, 50) as content')*/)
    ->offset(($req->input('currentPage') - 1) * $req->input('numPerPage'))
    ->limit($req->input('numPerPage'))
    ->orderBy('created_at')
    ->get()->toArray();
    $total = DB::table('essay')->count();
    return response()->json([
      'err' => [
        'level' => 0,
        'msg' => ''
      ],
      'data' => [
        'list' => $essays,
        'total' => $total,
      ]
    ]); 
  }

  // 获取随机文章
  function random(Request $req){
  	$essay = DB::table('essay')
                ->inRandomOrder()
                ->first();
    return response()->json([
      'err' => [
        'level' => 0,
        'msg' => ''
      ],
      'data' => $essay
    ]);
  }

  // 添加文章
  function add(Request $req){
    $id = DB::table('essay')->insertGetId([
      'title' => $req->input('title'),
      'author' => $req->input('author'),
      'content' => $req->input('content'),
      'type' => $req->input('type'),
    ]);
    return response()->json([
      'err' => [
        'level' => 0,
        'msg' => ''
      ],
      'data' => $id
    ]);
  }

  // 修改文章
  function save(Request $req){
    $res = DB::table('essay')->where('id', $req->input('id'))
    ->update([
      'title' => $req->input('title'),
      'author' => $req->input('author'),
      'content' => $req->input('content'),
      'type' => $req->input('type'),
    ]);
    return response()->json([
      'err' => [
        'level' => 0,
        'msg' => ''
      ],
      'data' => $res
    ]);
  }

  // 删除文章
  function del(Request $req){
    $res = DB::table('essay')->where('id', $req->input('id'))
    ->delete();
    return response()->json([
      'err' => [
        'level' => 0,
        'msg' => ''
      ],
      'data' => $res
    ]);
  }

  // 查询文章
  function query(Request $req){
    $essays = DB::table('essay')
    ->where('title', 'like', '%'.$req->input('keyword').'%')
    ->orWhere('author', 'like', '%'.$req->input('keyword').'%')
    // ->orWhere('content', 'like', '%'.$req->input('keyword').'%')
    // ->select('id', 'title', 'author')
    ->get()->toArray();
    return response()->json([
      'err' => [
        'level' => 0,
        'msg' => ''
      ],
      'data' => $essays
    ]);
  }

  // 爬取文章
  function fetch(Request $req){
    $cmd = "python ".base_path('crawler')."/fetch_essay.py --author=".$req->input('author')." --bookSerie=".$req->input('bookSerie')." --bookId=".$req->input('bookId')." --startId=".$req->input('startId')." --endId=".$req->input('endId');
    $output = [];
    $return_var = "";
    exec($cmd, $output, $return_var);
    // return $output;
    return response()->json([
      'err' => [
        'level' => 0,
        'msg' => ''
      ],
      'data' => implode('<br>', $output)
    ]);
  }

  // 添加文章
  function post(Request $req){
    $id = DB::table('essay')->insertGetId([
      'title' => $req->input('title'),
      'author' => $req->input('author'),
      'content' => $req->input('content'),
    ]);
    return redirect('essay/post');
  }

  // 文章编辑
  function edit(Request $req, $id){
    $essay = DB::table('essay')->where([
      'id' => $id
    ])->first();
    // echo var_dump($essay);
    return view('edit', ['essay' => $essay]);
  }

  function update(Request $req){
    $res = DB::table('essay')->where('id', $req->input('id'))
    ->update([
      'title' => $req->input('title'),
      'author' => $req->input('author'),
      'content' => $req->input('content'),
    ]);
    return redirect('/essay/'.$req->input('id').'/edit');
  }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CorpusController extends Controller
{
  // 获取文集列表
  function getList(Request $req){
    $corpuss = DB::table('corpus')
    ->select('id', 'title', 'author', 'publish_date', 'created_at', 'updated_at', 'intro')
    ->offset(($req->input('currentPage') - 1) * $req->input('numPerPage'))
    ->limit($req->input('numPerPage'))
    ->orderBy('created_at')
    ->get()->toArray();
    $total = DB::table('corpus')->count();
    return response()->json([
      'err' => [
        'level' => 0,
        'msg' => ''
      ],
      'data' => [
        'list' => $corpuss,
        'total' => $total,
      ]
    ]); 
  }

  // 添加文集
  function add(Request $req){
    $id = DB::table('corpus')->insertGetId([
      'title' => $req->input('title'),
      'author' => $req->input('author'),
      'publish_date' => $req->input('publish_date'),
      'intro' => $req->input('intro'),
      'cover' => $req->input('cover'),
    ]);
    return response()->json([
      'err' => [
        'level' => 0,
        'msg' => ''
      ],
      'data' => $id
    ]);
  }

  // 修改文集
  function save(Request $req){
    $res = DB::table('corpus')->where('id', $req->input('id'))
    ->update([
      'title' => $req->input('title'),
      'author' => $req->input('author'),
      'publish_date' => $req->input('publish_date'),
      'intro' => $req->input('intro'),
      'cover' => $req->input('cover'),
    ]);
    return response()->json([
      'err' => [
        'level' => 0,
        'msg' => ''
      ],
      'data' => $res
    ]);
  }

  // 删除文集
  function del(Request $req){
    $res = DB::table('corpus')->where('id', $req->input('id'))
    ->delete();
    return response()->json([
      'err' => [
        'level' => 0,
        'msg' => ''
      ],
      'data' => $res
    ]);
  }

  // 查询文集
  function query(Request $req){
    $corpus = DB::table('corpus')
    ->where('title', 'like', '%'.$req->input('keyword').'%')
    ->orWhere('author', 'like', '%'.$req->input('keyword').'%')
    // ->orWhere('intro', 'like', '%'.$req->input('keyword').'%')
    ->get()->toArray();
    return response()->json([
      'err' => [
        'level' => 0,
        'msg' => ''
      ],
      'data' => $corpus
    ]);
  }
}

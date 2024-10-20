<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Page;


class PageController extends Controller
{
    public function index(Request $request){
        $pages = Page::latest();

        if ($request->keyword != ''){
            $pages = $pages->where('name','like','%'.$request->keyword.'%');
        }
        $pages = $pages->paginate(10);
        return view('admin.pages.list',[
            'pages' => $pages
        ]);
    }

    public function create(){
        return view('admin.pages.create');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required'
        ]);

        if ($validator->passes()){
            $page = New Page;
            $page->name = $request->name;
            $page->slug = $request->slug;
            $page->content = $request->content;
            $page->save();

            $message = 'Tạo trang thành công';
            session()->flash('success', $message);
            return response()->json([
                'status' => true,
                'message' => $message
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function edit($id){
        $page = Page::find($id);
        if ($page == null){
            session()->flash('error', 'Trang không tồn tại');
            return redirect()->route('pages.index');
        }
        return view('admin.pages.edit',[
            'page' => $page
        ]);
    }
    public function update(Request $request, $id){
        $page = Page::find($id);

        if ($page == null){
            session()->flash('error', 'Trang không tồn tại');
            return response()->json([
                'status' => true
            ]);
        }
        
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required'
        ]);

        if ($validator->passes()){
            
            $page->name = $request->name;
            $page->slug = $request->slug;
            $page->content = $request->content;
            $page->save();

            $message = 'Chỉnh sửa trang thành công';
            session()->flash('success', $message);
            return response()->json([
                'status' => true,
                'message' => $message
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function destroy($id){
        $page = Page::find($id);

        if ($page == null){
            session()->flash('error', 'Trang không tồn tại');
            return response()->json([
                'status' => true
            ]);
        }
        $page->delete();
        $message = 'Xóa trang thành công';
            session()->flash('success', $message);
            return response()->json([
                'status' => true,
                'message' => $message
            ]);
    }
}

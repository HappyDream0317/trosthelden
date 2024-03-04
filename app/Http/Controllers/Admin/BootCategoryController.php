<?php

namespace App\Http\Controllers\Admin;

use App\GroupCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BootCategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = GroupCategory::all();
        //$user = Auth::guard('api')->user();
        return view('admin.boot_categories.index')->with('categories', $categories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.boot_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\GroupCategory  $bootCategory
     * @return \Illuminate\Http\Response
     */
    public function show(GroupCategory $bootCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\GroupCategory  $bootCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(GroupCategory $bootCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GroupCategory  $bootCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GroupCategory $bootCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GroupCategory  $bootCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(GroupCategory $bootCategory)
    {
        //
    }
}

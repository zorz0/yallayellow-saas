<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductLabel;

class ProductLabelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // if (auth()->user()->isAbleTo('Manage Product Label')) {
            $productLabels = ProductLabel::where('theme_id', APP_THEME())->where('store_id',getCurrentStore())->get();

            return view('product_label.index',compact('productLabels'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('product_label.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // if (auth()->user()->isAbleTo('Create Product Label')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required'
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $slug = ProductLabel::slugs($request->name);

            $productLabel                      = new ProductLabel();
            $productLabel->name                = $request->name;
            $productLabel->slug                = $slug;
            $productLabel->status              = $request->status;
            $productLabel->theme_id            = APP_THEME();
            $productLabel->store_id            = getCurrentStore();
            $productLabel->created_by          = auth()->user()->id;
            $productLabel->save();
            return redirect()->back()->with('success', __('Product Label successfully created.'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $productLabel = ProductLabel::find($id);
        return view('product_label.edit', compact('productLabel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // if (auth()->user()->isAbleTo('Edit Product Label')) {
            $productLabel = ProductLabel::find($id);
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

           

            $productLabel->name                = $request->name;
            if ($request->status) {
                $productLabel->status              = $request->status;
            }
            $productLabel->theme_id            = APP_THEME();
            $productLabel->store_id            = getCurrentStore();
            $productLabel->save();

            return redirect()->back()->with('success', __('Product Label successfully updated.'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // if (auth()->user()->isAbleTo('Delete Product Label')) {
            $productLabel = ProductLabel::find($id);
            $productLabel->delete();
            return redirect()->back()->with('success', __('Product Label delete successfully.'));

        // } else {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    /**
     * Responds with a welcome message with instructions
     *
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request)
    {
        $productLabel = ProductLabel::find($request->id);
        
        if ($productLabel) {
            $productLabel->status = $request->status;
            $productLabel->save();
            $return['status'] = 'success';
            $return['message'] = __('Status change successfully.');
            return response()->json($return);
            // return success_res('Install successfully.');
        } else {
            $return['status'] = 'error';
            $return['message'] = __('Something went wrong!!');
            return response()->json($return);
        }
    }
}

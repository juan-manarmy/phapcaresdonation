<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Form;
use App\Nod;
use App\Company;
use App\CfsPost;
use Illuminate\Support\Facades\DB;

class NodPostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $forms = DB::table('forms')
        ->select('forms.*','cfs_posts.title','users.first_name','users.last_name','members.member_name')
        ->join('cfs_posts', 'forms.cfs_post_id', '=', 'cfs_posts.id')
        ->join('users', 'forms.user_id', '=', 'users.id')
        ->join('members', 'users.member_id', '=', 'members.id')
        ->get();

        $forApprovalCount = 0;

        foreach ($forms as $item) {
            if($item->approval_status == 3) {
                $forApprovalCount += 1;
            }
        }

        // echo $approvalCount;

        // return $forms;
        return view('nods')->with('forms', $forms)->with('forApprovalCount', $forApprovalCount);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
        $form = DB::table('forms')
        ->select('forms.id','forms.approval_status','cfs_posts.title','users.first_name','users.last_name','members.member_name','forms.created_at')
        ->join('cfs_posts', 'forms.cfs_post_id', '=', 'cfs_posts.id')
        ->join('users', 'forms.user_id', '=', 'users.id')
        ->join('members', 'users.member_id', '=', 'members.id')
        ->where('forms.id',$id)
        ->get();
        // return $form;
        $nods = Nod::where('form_id',$id)->get();
        
        // return $form;
        return view('nods-view')->with('nods', $nods)->with('form', $form);
    }

    public function showItem($id, $itemId)
    {   
        // $nods = Nod::where('formid_id',$itemId)->get();
        $nodItem = Nod::findOrFail($itemId);
        // return $nodItem;
        return view('nods-view-item')->with('nodItem', $nodItem);
    }

    public function updateItem($id, Request $request)
    {   
        $nodItem = Nod::findOrFail($id);

        $nodItem->brand_name = $request->brand_name;
        $nodItem->generic_name = $request->generic_name;
        $nodItem->strength = $request->strength;
        $nodItem->dosage_form = $request->dosage_form;
        $nodItem->package_size = $request->package_size;
        $nodItem->quantity = $request->quantity;
        $nodItem->lotbatch_no = $request->lotbatch_no;
        $nodItem->expiry_date = $request->expiry_date;
        $nodItem->trade_price = $request->trade_price;
        $nodItem->total = $request->total;
        
        if($nodItem->save()) {
            return redirect()->route('nod-form-view', ['id' => $nodItem->form_id])->with('nods-item-updated','Item Successfully Updated!');
        }
    }

    public function updateApproval($id, $approvalCode)
    {   

        $form = Form::findOrFail($id);

        $form->approval_status = $approvalCode;

        $form->save();
        

        if($form->save()) {
            return redirect()->route('nod')->with('nods-item-updated','Item Successfully Updated!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

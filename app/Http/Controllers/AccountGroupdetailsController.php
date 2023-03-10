<?php

namespace App\Http\Controllers;

use App\AccountGroupdetails;
use Illuminate\Http\Request;

class AccountGroupdetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $result = AccountGroupdetails::with('account')->paginate(10);
        //dd($result);
        return view('ivrmenu/list',compact('result'));        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('ivrmenu/create');
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
     * @param  \App\AccountGroupdetails  $accountGroupdetails
     * @return \Illuminate\Http\Response
     */
    public function show(AccountGroupdetails $accountGroupdetails)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AccountGroupdetails  $accountGroupdetails
     * @return \Illuminate\Http\Response
     */
    public function edit(AccountGroupdetails $accountGroupdetails)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AccountGroupdetails  $accountGroupdetails
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AccountGroupdetails $accountGroupdetails)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AccountGroupdetails  $accountGroupdetails
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccountGroupdetails $accountGroupdetails)
    {
        //
    }
}

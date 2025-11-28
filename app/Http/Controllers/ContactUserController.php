<?php

namespace App\Http\Controllers;

use App\Models\ContactUser;
use App\Http\Requests\StoreContactUserRequest;
use App\Http\Requests\UpdateContactUserRequest;

class ContactUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreContactUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreContactUserRequest $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ContactUser  $contactUser
     * @return \Illuminate\Http\Response
     */
    public function show(ContactUser $contactUser)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ContactUser  $contactUser
     * @return \Illuminate\Http\Response
     */
    public function edit(ContactUser $contactUser)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateContactUserRequest  $request
     * @param  \App\Models\ContactUser  $contactUser
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateContactUserRequest $request, ContactUser $contactUser)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ContactUser  $contactUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContactUser $contactUser)
    {
    }
}

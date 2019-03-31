<?php

namespace App\Http\Controllers;

use App\Http\Models\Vendor;
use Illuminate\Http\Request;
use App\Http\Requests\StoreVendor;
use App\Http\Requests\UpdateVendor;
use App\Http\Requests\DeleteVendor;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreVendor  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVendor $request)
    {
        // Retrieve the validated input data...
        $validated = $request->validated();

        // The incoming request is valid...
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Http\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function show(Vendor $vendor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Http\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function edit(Vendor $vendor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateVendor  $request
     * @param  \App\Http\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVendor $request, Vendor $vendor)
    {
        // Retrieve the validated input data...
        $validated = $request->validated();

        // The incoming request is valid...
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\DeleteVendor  $request
     * @param  \App\Http\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteVendor $request, Vendor $vendor)
    {
        // Retrieve the validated input data...
        $validated = $request->validated();

        // The incoming request is valid...
    }
}

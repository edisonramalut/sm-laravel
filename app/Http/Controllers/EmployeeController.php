<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Employee;

class EmployeeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $tableColumns = Employee::TABLE_COLUMNS;
        $columns = Employee::FIELDS;
        $records = Employee::all();
        return view('employee.index',compact('tableColumns','columns','records'));
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
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $data = $request->all();
        $record = Employee::create($data);
        return redirect()->back()->with('success','Employee created successfully.');
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
    //
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function edit($id)
    {
        $record = Employee::find($id);
        $columns = Employee::FIELDS;
        return view('employee/update', compact('record','columns'));
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
        $record = Employee::find($id);
        $data = $request->all();
        foreach (Employee::FIELDS as $key => $val) {
            $record->$key = $data[$key];
        }
        $record->save();
        return redirect()->back()->with('success','Employee updated successfully.');
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        $record = Employee::find($id);
        $record->delete();
        return redirect()->back()->with('success','Employee deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\TransferBill;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransferBillController extends Controller
{
    public function index() {
    	$bills = TransferBill::with('from', 'to')->get()->toArray();
    	return view('admin.transfer', compact('bills'));
    }
}

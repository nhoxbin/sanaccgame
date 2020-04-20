<?php

namespace App\Http\Controllers\Admin;

use App\Sim;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SimController extends Controller
{
    public function index() {
        return view('admin.sim');
    }

    public function store(Request $request) {
        if (!$request->ajax()) {
            return response(null, 400);
        }
        $request->validate([
            'name' => 'required|string',
            'discount' => 'required|string'
        ]);
        Sim::create([
            'name' => $request->name,
            'discount' => $request->discount
        ]);
        return response('Lưu thành công!', 201);
    }

    public function edit(Request $request, Sim $sim) {
        if (!$request->ajax()) {
            return response(null, 400);
        }
        return response(['name' => $sim->name, 'discount' => $sim->discount], 200);
    }

    public function update(Request $request, Sim $sim) {
        if (!$request->ajax()) {
            return response(null, 400);
        }
        $sim->name = $request->name;
        $sim->discount = $request->discount;
        $sim->save();
        return response('Cập nhật thành công!', 200);
    }

    public function destroy(Request $request, Sim $sim) {
        if (!$request->ajax()) {
            return response(null, 400);
        }
        $sim->delete();
        return response('Xóa thành công!', 200);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Game;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('admin.game');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        if (!$request->ajax()) {
            return response(null, 400);
        }
        if (!$request->hasFile('picture') && !$request->picture->isValid()) {
            return response('file ko đúng định dạng!', 500);
        }
        try {
            $url = $request->picture->getClientOriginalName();
            $request->picture->move('uploads/game', $url);
            Game::create([
                'name' => $request->name,
                'sort_name' => $request->sort_name,
                'fee' => $request->fee,
                'picture' => 'uploads/game/' . $url,
                'info' => $request->info
            ]);
            return response('Thêm thành công!', 201);
        } catch(\Exception $e) {
            return response($e->getMessage(), 422);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (!$request->ajax()) {
            return response(null, 400);
        }
        $game = Game::select(['name', 'sort_name', 'fee', 'info'])->find($id);
        if ($game === null) {
            return response(null, 204);
        }
        return response($game, 200);
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
        if (!$request->ajax()) {
            return response(null, 400);
        }
        $game = Game::find($id);
        if ($game === null) {
            return response(null, 204);
        }
        if ($request->picture !== 'null' && $request->hasFile('picture') && $request->picture->isValid()) {
            $url = $request->picture->getClientOriginalName();
            $request->picture->move('uploads/game', $url);
            $game->picture = 'uploads/game/' . $url;
        }
        $game->name = $request->name;
        $game->sort_name = $request->sort_name;
        $game->fee = $request->fee;
        $game->info = $request->info;
        $game->save();
        return response('Cập nhật game thành công!', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {
        if (!$request->ajax()) {
            return response(null, 400);
        }
        Game::find($id)->delete();
        return response('Xóa thành công!', 200);
    }
}

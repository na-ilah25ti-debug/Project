<?php

namespace App\Http\Controllers;

use App\Models\GalonOrder;
use Illuminate\Http\Request;

class GalonController extends Controller
{
    public function index()
    {
        return view('galon.index', [
            'orders' => GalonOrder::with('tenant.room.house')->orderByRaw("status in ('selesai','batal')")->latest()->get(),
        ]);
    }

    public function update(Request $request, GalonOrder $order)
    {
        $order->update($request->validate(['status' => ['required', 'in:dipesan,diantar,selesai,batal']]));

        return back()->with('ok', 'Status pesanan galon diperbarui.');
    }
}

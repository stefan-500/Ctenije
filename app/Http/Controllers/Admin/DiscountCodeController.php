<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDiscountCodeRequest;
use App\Http\Requests\Admin\UpdateDiscountCodeRequest;
use App\Models\DiscountCode;

class DiscountCodeController extends Controller
{
    public function index()
    {
        $discountCodes = DiscountCode::withCount('redemptions')
            ->latest()
            ->get();

        return view('admin.popusti.index', compact('discountCodes'));
    }

    public function create()
    {
        return view('admin.popusti.dodaj', [
            'discountCode' => new DiscountCode(['is_active' => true, 'type' => 'percent']),
        ]);
    }

    public function store(StoreDiscountCodeRequest $request)
    {
        DiscountCode::create($request->discountCodeData());

        return redirect('/admin/popusti/index')->with('success', __('Kod za popust je uspiješno kreiran.'));
    }

    public function show(DiscountCode $discountCode)
    {
        $discountCode->loadCount('redemptions');

        $recentRedemptions = $discountCode->redemptions()
            ->with(['porudzbina', 'user'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.popusti.popust', compact('discountCode', 'recentRedemptions'));
    }

    public function edit(DiscountCode $discountCode)
    {
        $discountCode->loadCount('redemptions');

        return view('admin.popusti.izmijeni', compact('discountCode'));
    }

    public function update(UpdateDiscountCodeRequest $request, DiscountCode $discountCode)
    {
        $discountCode->update($request->discountCodeData());

        return redirect('/admin/popusti/index')->with('success', __('Kod za popust je uspiješno ažuriran.'));
    }

    public function toggle(DiscountCode $discountCode)
    {
        // Admin "delete" is intentionally separate from this status switch:
        // toggling only changes whether the code can be applied going forward.
        $discountCode->update(['is_active' => ! $discountCode->is_active]);

        $message = $discountCode->is_active
            ? __('Kod za popust je aktiviran.')
            : __('Kod za popust je deaktiviran.');

        return back()->with('success', $message);
    }

    public function destroy(DiscountCode $discountCode)
    {
        $discountCode->update(['is_active' => false]);

        return redirect('/admin/popusti/index')->with('success', __('Kod za popust je deaktiviran.'));
    }
}

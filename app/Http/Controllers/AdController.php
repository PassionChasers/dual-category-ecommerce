<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Http\Request;

class AdController extends Controller
{
    public function index()
    {
        $ads = Ad::latest()->paginate(10);
        return view('admin.ads.index', compact('ads'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'image' => 'required|image',
            'position' => 'required'
        ]);

        $data['image'] = $request->file('image')->store('ads', 'public');

        Ad::create($data);

        return back()->with('success', 'Ad created successfully');
    }

    public function toggle(Ad $ad)
    {
        // $ad->update(['is_active' => !$ad->is_active]);
        // return back();

        $ad->IsActive = !$ad->IsActive;
        $ad->save();

    return back()->with('success', 'Ad status updated successfully.');
    }

    public function destroy(Ad $ad)
    {
        $ad->delete();
        return back()->with('success', 'Ad deleted');
    }
}

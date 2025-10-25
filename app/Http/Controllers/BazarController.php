<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bazar;
use Illuminate\Http\Request;

class BazarController extends Controller
{
    // বাজার লিস্ট দেখানো
    public function adminManageBazar ()
    {
        $data['bazars'] = Bazar::latest()->get();
         
        return view('backend.admin-dashboard.manage_bazar', $data);
    }

    // বাজার সংরক্ষণ (Add/Edit)
    public function store(Request $request)
    {
       
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:Active,Inactive',
        ]);

        $bazar = Bazar::updateOrCreate(
            ['id' => $request->id],
            ['name' => $request->name, 'status' => $request->status]
        );

        return response()->json([
            'success' => true,
            'message' => $request->id ? 'বাজার তথ্য আপডেট হয়েছে ✅' : 'নতুন বাজার যোগ করা হয়েছে ✅',
            'bazar' => $bazar
        ]);
    }

    // বাজার সম্পাদনা ডেটা
    public function edit($id)
    {
        $bazar = Bazar::findOrFail($id);
        return response()->json($bazar);
    }

    // বাজার মুছে ফেলা
public function destroy($id)
{
    $bazar = Bazar::find($id);

    if (!$bazar) {
        return response()->json(['message' => 'বাজার পাওয়া যায়নি!'], 404);
    }

    $bazar->delete();

    return response()->json(['message' => '✅ বাজার সফলভাবে মুছে ফেলা হয়েছে']);
}








}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CatController extends Controller
{
    public function index(Request $request)
    {
        $query = Cat::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $cats = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        return view('admin.cats.index', compact('cats'));
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:50'],
            'gender' => ['required','in:male,female'],
            'age' => ['required','integer','min:0'],
            'personality' => ['nullable','string','max:255'],
            'price' => ['required','numeric','min:0'],
            'availability' => ['boolean'],
            'image' => ['nullable','image','max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/cats'), $filename);
            $data['image'] = 'storage/cats/' . $filename;
        }

        Cat::create($data);

        return redirect()->route('admin.cats.index')->with('success', 'Đã thêm mèo thành công.');
    }


    public function update(Request $request, Cat $cat)
    {
        $data = $request->validate([
            'name' => ['required','string','max:50'],
            'gender' => ['required','in:male,female'],
            'age' => ['required','integer','min:0'],
            'personality' => ['nullable','string','max:255'],
            'price' => ['required','numeric','min:0'],
            'availability' => ['boolean'],
            'image' => ['nullable','image','max:2048'],
        ]);

        if ($request->hasFile('image')) {
            if ($cat->image && file_exists(public_path($cat->image))) {
                unlink(public_path($cat->image));
            }
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/cats'), $filename);
            $data['image'] = 'storage/cats/' . $filename;
        }

        $cat->update($data);

        return redirect()->route('admin.cats.index')->with('success', 'Đã cập nhật mèo.');
    }

    public function destroy(Cat $cat)
    {
        if ($cat->image && file_exists(public_path($cat->image))) {
            unlink(public_path($cat->image));
        }
        $cat->delete();
        return redirect()->route('admin.cats.index')->with('success', 'Đã xóa mèo.');
    }
}


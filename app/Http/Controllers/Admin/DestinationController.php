<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DestinationController extends Controller
{
    public function index(Request $request)
    {
        $query = Destination::query();
        
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->location) {
            $query->where('location', $request->location);
        }

        $destinations = $query->latest()->paginate(10);
        $locations = Destination::select('location')->distinct()->pluck('location');

        if ($request->export == 'pdf') {
            return view('admin.destinations.print', compact('destinations'));
        }

        return view('admin.destinations.index', compact('destinations', 'locations'));
    }

    public function show(Destination $destination)
    {
        return view('admin.destinations.show', compact('destination'));
    }

    public function create()
    {
        return view('admin.destinations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'location' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('destinations', 'public');
            $data['image_path'] = $path;
        }

        Destination::create($data);

        return redirect()->route('admin.destinations.index')->with('success', 'Thêm điểm đến mới thành công.');
    }

    public function edit(Destination $destination)
    {
        return view('admin.destinations.edit', compact('destination'));
    }

    public function update(Request $request, Destination $destination)
    {
        $request->validate([
            'name' => 'required',
            'location' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            // Delete old image
            if ($destination->image_path) {
                Storage::disk('public')->delete($destination->image_path);
            }
            $path = $request->file('image')->store('destinations', 'public');
            $data['image_path'] = $path;
        }

        $destination->update($data);

        $returnUrl = $request->input('return_url');
        if ($returnUrl && str_starts_with($returnUrl, url('/'))) {
            return redirect()->to($returnUrl)->with('success', 'Cập nhật điểm đến thành công.');
        }

        return redirect()->route('admin.destinations.index')->with('success', 'Cập nhật điểm đến thành công.');
    }

    public function destroy(Destination $destination)
    {
        if ($destination->image_path) {
            Storage::disk('public')->delete($destination->image_path);
        }
        $destination->delete();
        return redirect()->route('admin.destinations.index')->with('success', 'Xóa điểm đến thành công.');
    }
}

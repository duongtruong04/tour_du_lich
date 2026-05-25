<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\Booking;
use App\Models\Destination;
use App\Models\TourImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TourController extends Controller
{
    public function index(Request $request)
    {
        $query = Tour::query();
        
        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->min_price) {
            $query->where('base_price', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $query->where('base_price', '<=', $request->max_price);
        }

        if ($request->is_active !== null && $request->is_active !== '') {
            $query->where('is_active', $request->is_active);
        }

        $tours = $query->latest()->paginate(10);

        if ($request->export == 'pdf') {
            $tours = $query->get(); // Get all for PDF
            return view('admin.tours.print', compact('tours'));
        }

        return view('admin.tours.index', compact('tours'));
    }

    public function show(Tour $tour)
    {
        $tour->load(['destinations', 'departures', 'images']);
        return view('admin.tours.show', compact('tour'));
    }

    public function create()
    {
        $destinations = Destination::all();
        return view('admin.tours.create', compact('destinations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'base_price' => ['required', 'numeric', 'gt:0'],
            'duration' => 'required',
            'transportation' => 'required|string|in:Máy bay,Du thuyền,Xe du lịch chất lượng cao',
            'destinations' => 'required|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ], [
            'base_price.required' => 'Vui lòng nhập giá tour.',
            'base_price.numeric' => 'Giá tour phải là số hợp lệ.',
            'base_price.gt' => 'Giá tour phải lớn hơn 0.',
            'transportation.required' => 'Vui lòng chọn phương tiện di chuyển.',
            'transportation.in' => 'Phương tiện di chuyển không hợp lệ.',
        ]);

        $slug = Str::slug($request->title);
        // Ensure unique slug by appending suffix if needed
        $originalSlug = $slug;
        $counter = 1;
        while (Tour::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . (++$counter);
        }

        $tour = Tour::create([
            'title' => $request->title,
            'slug' => $slug,
            'summary' => $request->summary,
            'itinerary' => $request->itinerary,
            'base_price' => $request->base_price,
            'duration' => $request->duration,
            'transportation' => $request->transportation,
            'service_includes' => $request->service_includes,
            'service_excludes' => $request->service_excludes,
            'is_active' => $request->is_active ?? 1,
        ]);

        $tour->destinations()->attach($request->destinations);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('tours', 'public');
                $tour->images()->create([
                    'image_path' => $path,
                    'is_primary' => false,
                ]);
            }
        }

        if ($request->departures) {
            foreach ($request->departures as $dep) {
                if (!empty($dep['start_date'])) {
                    $tour->departures()->create([
                        'start_date' => $dep['start_date'],
                        'max_seats' => $dep['max_seats'] ?? 30,
                        'available_seats' => $dep['max_seats'] ?? 30,
                        'price_override' => $dep['price_override'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('admin.tours.index')->with('success', 'Tạo tour thành công.');
    }

    public function edit(Tour $tour)
    {
        $destinations = Destination::all();
        $tour->load(['destinations', 'departures', 'images']);
        return view('admin.tours.edit', compact('tour', 'destinations'));
    }

    public function update(Request $request, Tour $tour)
    {
        $request->validate([
            'title' => 'required',
            'base_price' => ['required', 'numeric', 'gt:0'],
            'duration' => 'required',
            'transportation' => 'required|string|in:Máy bay,Du thuyền,Xe du lịch chất lượng cao',
            'destinations' => 'required|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ], [
            'base_price.required' => 'Vui lòng nhập giá tour.',
            'base_price.numeric' => 'Giá tour phải là số hợp lệ.',
            'base_price.gt' => 'Giá tour phải lớn hơn 0.',
            'transportation.required' => 'Vui lòng chọn phương tiện di chuyển.',
            'transportation.in' => 'Phương tiện di chuyển không hợp lệ.',
        ]);

        // Only regenerate slug if title actually changed
        $slug = $tour->slug;
        if ($tour->title !== $request->title) {
            $slug = Str::slug($request->title);
            $originalSlug = $slug;
            $counter = 1;
            while (Tour::where('slug', $slug)->where('id', '!=', $tour->id)->exists()) {
                $slug = $originalSlug . '-' . (++$counter);
            }
        }

        $tour->update([
            'title' => $request->title,
            'slug' => $slug,
            'summary' => $request->summary,
            'itinerary' => $request->itinerary,
            'base_price' => $request->base_price,
            'duration' => $request->duration,
            'transportation' => $request->transportation,
            'service_includes' => $request->service_includes,
            'service_excludes' => $request->service_excludes,
            'is_active' => $request->is_active ?? 1,
        ]);

        $tour->destinations()->sync($request->destinations);

        // Handle adding new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('tours', 'public');
                $tour->images()->create([
                    'image_path' => $path,
                    'is_primary' => false,
                ]);
            }
        }

        // Handle departures - smart sync without deleting booked ones
        $submittedDepartureIds = [];
        if ($request->departures) {
            foreach ($request->departures as $dep) {
                if (!empty($dep['start_date'])) {
                    if (!empty($dep['id'])) {
                        // Update existing departure
                        $existing = $tour->departures()->find($dep['id']);
                        if ($existing) {
                            $existing->update([
                                'start_date' => $dep['start_date'],
                                'max_seats' => $dep['max_seats'] ?? 30,
                                'price_override' => $dep['price_override'] ?? null,
                            ]);
                            $submittedDepartureIds[] = $existing->id;
                        }
                    } else {
                        // Create new departure
                        $newDep = $tour->departures()->create([
                            'start_date' => $dep['start_date'],
                            'max_seats' => $dep['max_seats'] ?? 30,
                            'available_seats' => $dep['max_seats'] ?? 30,
                            'price_override' => $dep['price_override'] ?? null,
                        ]);
                        $submittedDepartureIds[] = $newDep->id;
                    }
                }
            }
        }

        // Remove departures not in form submission, but only if they have no bookings
        $departuresToRemove = $tour->departures()->whereNotIn('id', $submittedDepartureIds)->get();
        $skippedCount = 0;
        foreach ($departuresToRemove as $dep) {
            if ($dep->bookings()->count() === 0) {
                $dep->delete();
            } else {
                $skippedCount++;
            }
        }

        $message = 'Cập nhật tour thành công.';
        if ($skippedCount > 0) {
            $message .= " Lưu ý: {$skippedCount} ngày khởi hành đã có khách đặt nên không thể xóa.";
        }

        return redirect()->route('admin.tours.index')->with('success', $message);
    }

    public function destroy(Tour $tour)
    {
        try {
            // Check if tour has any bookings via departures
            $hasBookings = Booking::whereHas('departure', function ($query) use ($tour) {
                $query->where('tour_id', $tour->id);
            })->exists();

            if ($hasBookings) {
                // Tour has bookings - hide it instead of deleting
                $tour->update(['is_active' => 0]);

                return redirect()->route('admin.tours.index')->with('warning',
                    'Tour đã có đơn đặt, không thể xóa cứng. Hệ thống đã ẩn tour để giữ lịch sử đặt tour.'
                );
            }

            // Tour has no bookings - safe to hard delete
            DB::transaction(function () use ($tour) {
                // Delete tour images from storage and database
                foreach ($tour->images as $image) {
                    Storage::disk('public')->delete($image->image_path);
                }
                $tour->images()->delete();

                // Detach destinations (pivot table)
                $tour->destinations()->detach();

                // Delete departures (no bookings, verified above)
                $tour->departures()->delete();

                // Delete the tour
                $tour->delete();
            });

            return redirect()->route('admin.tours.index')->with('success', 'Xóa tour thành công.');

        } catch (\Exception $e) {
            return redirect()->route('admin.tours.index')->with('error',
                'Có lỗi xảy ra khi xóa tour. Vui lòng thử lại sau.'
            );
        }
    }

    public function deleteImage(TourImage $image)
    {
        Storage::disk('public')->delete($image->image_path);
        $image->delete();
        return redirect()->back()->with('success', 'Xóa ảnh thành công.');
    }
}

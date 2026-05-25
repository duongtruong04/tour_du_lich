<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        $query = Promotion::query();
        
        if ($request->search) {
            $query->where('code', 'like', '%' . $request->search . '%');
        }

        if ($request->discount_type) {
            $query->where('discount_type', $request->discount_type);
        }

        if ($request->status) {
            $today = date('Y-m-d');
            if ($request->status == 'active') {
                $query->where('expiry_date', '>=', $today);
            } elseif ($request->status == 'expired') {
                $query->where('expiry_date', '<', $today);
            }
        }

        $promotions = $query->latest()->paginate(15);
        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        return view('admin.promotions.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'code' => 'required|unique:promotions',
            'discount_value' => ['required', 'numeric', 'gt:0'],
            'discount_type' => 'required|in:Percentage,Fixed',
            'expiry_date' => 'required|date',
            'usage_limit' => ['required', 'integer', 'min:1'],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];

        if ($request->discount_type === 'Percentage') {
            $rules['discount_value'][] = 'max:100';
        }

        $request->validate($rules, [
            'discount_value.required' => 'Vui lòng nhập giá trị giảm.',
            'discount_value.numeric' => 'Giá trị giảm phải là số hợp lệ.',
            'discount_value.gt' => 'Giá trị giảm phải lớn hơn 0.',
            'discount_value.max' => 'Giá trị giảm theo phần trăm không được vượt quá 100%.',
            'usage_limit.required' => 'Vui lòng nhập giới hạn sử dụng.',
            'usage_limit.integer' => 'Giới hạn sử dụng phải là số nguyên.',
            'usage_limit.min' => 'Giới hạn sử dụng phải lớn hơn 0.',
        ]);

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('promotions', 'public');
        }

        Promotion::create($data);

        return redirect()->route('admin.promotions.index')->with('success', 'Thêm mã khuyến mãi thành công.');
    }

    public function edit(Promotion $promotion)
    {
        return view('admin.promotions.edit', compact('promotion'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $rules = [
            'code' => 'required|unique:promotions,code,' . $promotion->id,
            'discount_value' => ['required', 'numeric', 'gt:0'],
            'discount_type' => 'required|in:Percentage,Fixed',
            'expiry_date' => 'required|date',
            'usage_limit' => ['required', 'integer', 'min:1'],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];

        if ($request->discount_type === 'Percentage') {
            $rules['discount_value'][] = 'max:100';
        }

        $request->validate($rules, [
            'discount_value.required' => 'Vui lòng nhập giá trị giảm.',
            'discount_value.numeric' => 'Giá trị giảm phải là số hợp lệ.',
            'discount_value.gt' => 'Giá trị giảm phải lớn hơn 0.',
            'discount_value.max' => 'Giá trị giảm theo phần trăm không được vượt quá 100%.',
            'usage_limit.required' => 'Vui lòng nhập giới hạn sử dụng.',
            'usage_limit.integer' => 'Giới hạn sử dụng phải là số nguyên.',
            'usage_limit.min' => 'Giới hạn sử dụng phải lớn hơn 0.',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($promotion->image_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($promotion->image_path);
            }
            $data['image_path'] = $request->file('image')->store('promotions', 'public');
        }

        $promotion->update($data);

        return redirect()->route('admin.promotions.index')->with('success', 'Cập nhật mã thành công.');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();
        return redirect()->route('admin.promotions.index')->with('success', 'Xóa mã khuyến mãi thành công.');
    }
}

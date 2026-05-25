<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    */

    'accepted'             => ':attribute phải được chấp nhận.',
    'active_url'           => ':attribute không phải là một URL hợp lệ.',
    'after'                => ':attribute phải là một ngày sau ngày :date.',
    'alpha'                => ':attribute chỉ có thể chứa các chữ cái.',
    'alpha_dash'           => ':attribute chỉ có thể chứa chữ cái, số và dấu gạch ngang.',
    'alpha_num'            => ':attribute chỉ có thể chứa chữ cái và số.',
    'array'                => ':attribute phải là một mảng.',
    'before'               => ':attribute phải là một ngày trước ngày :date.',
    'between'              => [
        'numeric' => ':attribute phải nằm trong khoảng :min - :max.',
        'file'    => ':attribute phải nằm trong khoảng :min - :max KB.',
        'string'  => ':attribute phải nằm trong khoảng :min - :max ký tự.',
        'array'   => ':attribute phải có từ :min đến :max phần tử.',
    ],
    'boolean'              => ':attribute phải là true hoặc false.',
    'confirmed'            => 'Xác nhận :attribute không khớp.',
    'date'                 => ':attribute không phải là một ngày hợp lệ.',
    'date_format'          => ':attribute không khớp với định dạng :format.',
    'different'            => ':attribute và :other phải khác nhau.',
    'digits'               => ':attribute phải có :digits chữ số.',
    'digits_between'       => ':attribute phải nằm trong khoảng :min - :max chữ số.',
    'email'                => ':attribute phải là một địa chỉ email hợp lệ.',
    'exists'               => ':attribute đã chọn không hợp lệ.',
    'filled'               => ':attribute không được để trống.',
    'image'                => ':attribute phải là một hình ảnh.',
    'in'                   => ':attribute đã chọn không hợp lệ.',
    'integer'              => ':attribute phải là một số nguyên.',
    'ip'                   => ':attribute phải là một địa chỉ IP hợp lệ.',
    'max'                  => [
        'numeric' => ':attribute không được lớn hơn :max.',
        'file'    => ':attribute không được lớn hơn :max KB.',
        'string'  => ':attribute không được lớn hơn :max ký tự.',
        'array'   => ':attribute không được có nhiều hơn :max phần tử.',
    ],
    'mimes'                => ':attribute phải là một tập tin có định dạng: :values.',
    'min'                  => [
        'numeric' => ':attribute phải tối thiểu là :min.',
        'file'    => ':attribute phải tối thiểu là :min KB.',
        'string'  => ':attribute phải tối thiểu là :min ký tự.',
        'array'   => ':attribute phải có tối thiểu :min phần tử.',
    ],
    'not_in'               => ':attribute đã chọn không hợp lệ.',
    'numeric'              => ':attribute phải là một số.',
    'regex'                => ':attribute có định dạng không hợp lệ.',
    'required'             => ':attribute không được để trống.',
    'required_if'          => ':attribute không được để trống khi :other là :value.',
    'required_with'        => ':attribute không được để trống khi một trong :values có mặt.',
    'required_with_all'    => ':attribute không được để trống khi tất cả :values có mặt.',
    'required_without'     => ':attribute không được để trống khi một trong :values không có mặt.',
    'required_without_all' => ':attribute không được để trống khi tất cả :values không có mặt.',
    'same'                 => ':attribute và :other phải khớp với nhau.',
    'size'                 => [
        'numeric' => ':attribute phải bằng :size.',
        'file'    => ':attribute phải bằng :size KB.',
        'string'  => ':attribute phải bằng :size ký tự.',
        'array'   => ':attribute phải chứa :size phần tử.',
    ],
    'string'               => ':attribute phải là một chuỗi ký tự.',
    'timezone'             => ':attribute phải là một múi giờ hợp lệ.',
    'unique'               => ':attribute đã tồn tại.',
    'url'                  => ':attribute có định dạng không hợp lệ.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    */

    'attributes' => [
        'full_name'    => 'Họ và tên',
        'email'        => 'Email',
        'password'     => 'Mật khẩu',
        'phone'        => 'Số điện thoại',
        'title'        => 'Tiêu đề',
        'summary'      => 'Tóm tắt',
        'base_price'   => 'Giá cơ bản',
        'duration'     => 'Thời lượng',
        'destinations' => 'Điểm đến',
        'images'       => 'Hình ảnh',
        'content'      => 'Nội dung',
        'name'         => 'Tên',
        'code'         => 'Mã',
        'discount'     => 'Giảm giá',
        'start_date'   => 'Ngày bắt đầu',
        'end_date'     => 'Ngày kết thúc',
        'departure'    => 'Ngày khởi hành',
        'address'      => 'Địa chỉ',
    ],

];

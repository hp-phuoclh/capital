<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute phải được chấp nhận.',
    'active_url' => ':attribute không phải là url hợp lệ.',
    'after' => ':attribute phải là ngày lớn hơn ngày :date.',
    'after_or_equal' => ':attribute phải là ngày lớn hoặc bằng ngày :date.',
    'alpha' => ':attribute chỉ có thể chứa chữ cái.',
    'alpha_dash' => ':attribute chỉ có thể chứa chữ cái, số, dấu gạch ngang và dấu gạch dưới.',
    'alpha_num' => ':attribute hỉ có thể chứa chữ cái và số.',
    'array' => ':attribute phải là một mảng.',
    'before' => ':attribute phải là ngày trước ngày :date.',
    'before_or_equal' => ':attribute phải là ngày trước hoặc bằng ngày :date.',
    'between' => [
        'numeric' => ':attribute phải thuộc trong khoảng :min và :max.',
        'file' => ':attribute phải thuộc trong khoảng :min và :max kilobytes.',
        'string' => ':attribute phải thuộc trong khoảng :min và :max ký tự.',
        'array' => ':attribute phải thuộc trong khoảng :min và :max mục.',
    ],
    'boolean' => 'Thuộc tính :attribute phải là đúng hoặc sai.',
    'confirmed' => ':attribute xác nhận không trùng khớp.',
    'date' => ':attribute không phải là ngày hợp lệ.',
    'date_equals' => ':attribute phải là ngày tương đương với ngày :date.',
    'date_format' => ':attribute không trùng với định dạng :format.',
    'different' => ':attribute và :other phải khác nhau.',
    'digits' => ':attribute phải có :digits chữ số.',
    'digits_between' => ':attribute phải thuộc trong khoảng :min và :max chữ số.',
    'dimensions' => ':attribute có kích thước hình ảnh không hợp lệ.',
    'distinct' => 'Thuộc tính :attribute bị trùng lặp giá trị.',
    'email' => ':attribute phải là địa chỉ hợp lệ.',
    'ends_with' => ':attribute phải kết thúc bằng một trong những giá trị sau đây: :values',
    'exists' => ':attribute không tồn tại trong hệ thống.',
    'file' => ':attribute phải là tập tin.',
    'filled' => 'Thuộc tính :attribute phải có giá trị.',
    'gt' => [
        'numeric' => ':attribute phải lớn hơn :value.',
        'file' => ':attribute phải lớn hơn :value kilobytes.',
        'string' => ':attribute phải lớn hơn :value ký tự.',
        'array' => ':attribute phải có nhiều hơn :value mục.',
    ],
    'gte' => [
        'numeric' => ':attribute phải lớn hơn or equal :value.',
        'file' => ':attribute phải lớn hơn or equal :value kilobytes.',
        'string' => ':attribute phải lớn hơn or equal :value ký tự.',
        'array' => ':attribute phải có :value mục hoặc nhiều hơn.',
    ],
    'image' => ':attribute phải là hình ảnh.',
    'in' => ':attribute được chọn không hợp lệ.',
    'in_array' => 'Thuộc tính :attribute không tồn tại trong :other.',
    'integer' => ':attribute phải là số nguyên.',
    'ip' => ':attribute phải là địa chỉ IP hợp lệ.',
    'ipv4' => ':attribute phải là địa chỉ IPv4 hợp lệ.',
    'ipv6' => ':attribute phải là địa chỉ IPv6 hợp lệ.',
    'json' => ':attribute phải là một chuỗi JSON.',
    'lt' => [
        'numeric' => ':attribute phải nhỏ hơn :value.',
        'file' => ':attribute phải nhỏ hơn :value kilobytes.',
        'string' => ':attribute phải nhỏ hơn :value ký tự.',
        'array' => ':attribute phải có ít hơn :value mục.',
    ],
    'lte' => [
        'numeric' => ':attribute phải nhỏ hơn hoặc bằng :value.',
        'file' => ':attribute phải nhỏ hơn hoặc bằng :value kilobytes.',
        'string' => ':attribute phải nhỏ hơn hoặc bằng :value ký tự.',
        'array' => ':attribute phải có nhiều hơn :value mục.',
    ],
    'max' => [
        'numeric' => ':attribute không được lớn hơn :max.',
        'file' => ':attribute phải nhỏ hơn :max kilobytes.',
        'string' => ':attribute tối đa là :max ký tự.',
        'array' => ':attribute không được lớn hơn :max mục.',
    ],
    'mimes' => ':attribute phải là một tập tin thuộc loại: :values.',
    'mimetypes' => ':attribute phải là một tập tin thuộc loại: :values.',
    'min' => [
        'numeric' => ':attribute phải có tối thiểu :min.',
        'file' => ':attribute phải có tối thiểu :min kilobytes.',
        'string' => ':attribute phải có tối thiểu là :min ký tự.',
        'array' => ':attribute phải có tối thiểu :min mục.',
    ],
    'not_in' => ':attribute được chọn không hợp lệ.',
    'not_regex' => ':attribute không hợp lệ.',
    'numeric' => ':attribute phải là số.',
    'present' => 'Thuộc tính :attribute phải tồn tại.',
    'regex' => ':attribute không hợp lệ.',
    'required' => ':attribute là bắt buộc.',
    'required_if' => 'Thuộc tính :attribute được yêu cầu khi :other có giá trị :value.',
    'required_unless' => 'Thuộc tính :attribute được yêu cầu trừ khi :other có trong :values.',
    'required_with' => 'Thuộc tính :attribute được yêu cầu khi :values tồn tại.',
    'required_with_all' => 'Thuộc tính :attribute được yêu cầu khi :values tồn tại.',
    'required_without' => 'Thuộc tính :attribute được yêu cầu khi :values không tồn tại.',
    'required_without_all' => 'Thuộc tính :attribute được yêu cầu khi none of :values tồn tại.',
    'same' => ':attribute và :other phải trùng nhau.',
    'size' => [
        'numeric' => ':attribute phải bằng :size.',
        'file' => ':attribute phải bằng :size kilobytes.',
        'string' => ':attribute phải có :size ký tự.',
        'array' => ':attribute phải chứa :size mục.',
    ],
    'starts_with' => ':attribute phải bắt đầu với một trong những giá trị sau: :values',
    'string' => ':attribute phải là một chuỗi.',
    'timezone' => ':attribute phải là một khu vực hợp lệ.',
    'unique' => ':attribute đã tồn tại.',
    'uploaded' => ':attribute không được đăng tải.',
    'url' => ':attribute có định dạng không hợp lệ.',
    'uuid' => ':attribute phải là UUID hợp lệ.',
    'phone' => 'Số điện thoại không hợp lệ.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'email' => 'Địa chỉ email',
        'username' => 'Số điện thoại', // in capital only phone
        'password' => 'Mật khẩu',
        'name' => 'Tên',
        'price' => 'Giá gốc',
        'discount' => 'Giá bán',
        'address' => 'Địa chỉ',
        'contacts.url' => 'URL',
        'contacts.phone' => 'Số điện thoại',
        'contacts.email' => 'Email',
        'contacts.company_name' => 'Tên công ty',
        'contacts.tax_number' => 'Mã số thuế',
        'contacts.address' => 'Địa chỉ',
        'contacts.identity_number' => 'Số CMND',
        'code' => 'Mã',
        'account_number' => 'Số tài khoản',
        'full_name' => 'Họ và tên',
        'content' => 'Nội dung',

        'quantity' => 'Số lượng',
        'start' => 'Ngày bắt đầu',
        'end' => 'Ngày kết thúc',
        'options.start' => 'Ngày tuỳ chọn bắt đầu',
        'options.end' => 'Ngày tuỳ chọn kết thúc',
        'notify_expiration' => 'Thông báo hết hạn',
        'number_of_days' => 'Số ngày',

        'money' => 'Số tiền',
        'current_password' => 'Mật khẩu hiện tại',
        'new_password' => 'Mật khẩu mới',
        'new_confirm_password' => 'Xác nhận mật khẩu',
        'type_id' => 'Id',

        'star' => "Sao",
        'content' => 'Nội dung',
        'files' => 'Tập tin',
        'likes' => 'Thích',
        'replies' => 'Trả lời',

        'sale_off' => 'Giá khuến mãi',
        'size' => 'Kích thước',
        'published' => 'Được phát hành',
        'image' => 'Hình ảnh',
        'image_edit' => 'Hình ảnh',
        'phone' => 'Số điện thoại',
        'birthday' => 'Ngày sinh',
        'gender' => 'Giới tính',
    ],

];

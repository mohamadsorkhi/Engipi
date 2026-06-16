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

    'accepted' => ':attribute باید پذیرفته شده باشد.',
    'active_url' => 'آدرس :attribute معتبر نیست.',
    'after' => ':attribute باید تاریخی بعد از :date باشد.',
    'after_or_equal' => ':attribute باید تاریخی بعد از :date، یا مطابق با آن باشد.',
    'alpha' => ':attribute باید فقط حروف الفبا باشد.',
    'alpha_dash' => ':attribute باید فقط حروف الفبا، اعداد، خط تیره و زیرخط باشد.',
    'alpha_num' => ':attribute باید فقط حروف الفبا و اعداد باشد.',
    'array' => ':attribute باید آرایه باشد.',
    'before' => ':attribute باید تاریخی قبل از :date باشد.',
    'before_or_equal' => ':attribute باید تاریخی قبل از :date، یا مطابق با آن باشد.',
    'between' => [
        'numeric' => ':attribute باید بین :min و :max باشد.',
        'file' => ':attribute باید بین :min و :max کیلوبایت باشد.',
        'string' => ':attribute باید بین :min و :max کاراکتر باشد.',
        'array' => ':attribute باید بین :min و :max آیتم باشد.',
    ],
    'boolean' => 'فیلد :attribute فقط می‌تواند true و یا false باشد.',
    'confirmed' => ':attribute با فیلد تکرار مطابقت ندارد.',
    'date' => ':attribute یک تاریخ معتبر نیست.',
    'date_equals' => ':attribute باید یک تاریخ برابر با تاریخ :date باشد.',
    'date_format' => ':attribute با الگوی :format مطابقت ندارد.',
    'different' => ':attribute و :other باید از یکدیگر متفاوت باشند.',
    'digits' => ':attribute باید :digits رقم باشد.',
    'digits_between' => ':attribute باید بین :min و :max رقم باشد.',
    'dimensions' => 'ابعاد تصویر :attribute قابل قبول نیست.',
    'distinct' => 'فیلد :attribute مقدار تکراری دارد.',
    'email' => ':attribute باید یک ایمیل معتبر باشد.',
    'ends_with' => 'فیلد :attribute باید با یکی از مقادیر زیر خاتمه یابد: :values',
    'exists' => ':attribute انتخاب شده، معتبر نیست.',
    'file' => ':attribute باید یک فایل معتبر باشد.',
    'filled' => 'فیلد :attribute باید مقدار داشته باشد.',
    'gt' => [
        'numeric' => ':attribute باید بزرگتر از :value باشد.',
        'file' => ':attribute باید بزرگتر از :value کیلوبایت باشد.',
        'string' => ':attribute باید بیشتر از :value کاراکتر داشته باشد.',
        'array' => ':attribute باید بیشتر از :value آیتم داشته باشد.',
    ],
    'gte' => [
        'numeric' => ':attribute باید بزرگتر یا مساوی :value باشد.',
        'file' => ':attribute باید بزرگتر یا مساوی :value کیلوبایت باشد.',
        'string' => ':attribute باید بیشتر یا مساوی :value کاراکتر داشته باشد.',
        'array' => ':attribute باید بیشتر یا مساوی :value آیتم داشته باشد.',
    ],
    'image' => ':attribute باید یک تصویر معتبر باشد.',
    'in' => ':attribute انتخاب شده، معتبر نیست.',
    'in_array' => 'فیلد :attribute در لیست :other وجود ندارد.',
    'integer' => ':attribute باید عدد صحیح باشد.',
    'ip' => ':attribute باید آدرس IP معتبر باشد.',
    'ipv4' => ':attribute باید یک آدرس معتبر از نوع IPv4 باشد.',
    'ipv6' => ':attribute باید یک آدرس معتبر از نوع IPv6 باشد.',
    'json' => 'فیلد :attribute باید یک رشته از نوع JSON باشد.',
    'lt' => [
        'numeric' => ':attribute باید کوچکتر از :value باشد.',
        'file' => ':attribute باید کوچکتر از :value کیلوبایت باشد.',
        'string' => ':attribute باید کمتر از :value کاراکتر داشته باشد.',
        'array' => ':attribute باید کمتر از :value آیتم داشته باشد.',
    ],
    'lte' => [
        'numeric' => ':attribute باید کوچکتر یا مساوی :value باشد.',
        'file' => ':attribute باید کوچکتر یا مساوی :value کیلوبایت باشد.',
        'string' => ':attribute باید کمتر یا مساوی :value کاراکتر داشته باشد.',
        'array' => ':attribute باید کمتر یا مساوی :value آیتم داشته باشد.',
    ],
    'max' => [
        'numeric' => ':attribute نباید بزرگتر از :max باشد.',
        'file' => ':attribute نباید بزرگتر از :max کیلوبایت باشد.',
        'string' => ':attribute نباید بیشتر از :max کاراکتر داشته باشد.',
        'array' => ':attribute نباید بیشتر از :max آیتم داشته باشد.',
    ],
    'mimes' => 'فرمت‌های معتبر فایل عبارتند از: :values.',
    'mimetypes' => 'فرمت‌های معتبر فایل عبارتند از: :values.',
    'min' => [
        'numeric' => ':attribute نباید کوچکتر از :min باشد.',
        'file' => ':attribute نباید کوچکتر از :min کیلوبایت باشد.',
        'string' => ':attribute نباید کمتر از :min کاراکتر داشته باشد.',
        'array' => ':attribute نباید کمتر از :min آیتم داشته باشد.',
    ],
    'not_in' => ':attribute انتخاب شده، معتبر نیست.',
    'not_regex' => 'فرمت :attribute معتبر نیست.',
    'numeric' => ':attribute باید عدد یا رشته‌ای از اعداد باشد.',
    'present' => 'فیلد :attribute باید در پارامترهای ارسالی وجود داشته باشد.',
    'regex' => 'فرمت :attribute معتبر نیست.',
    'required' => 'فیلد :attribute الزامی است.',
//    'required_if' => 'هنگامی که :other برابر با :value است، فیلد :attribute الزامی است.',
    'required_if' => 'این فیلد الزامی است.',
    'required_unless' => 'فیلد :attribute الزامی است، مگر آنکه :other در :values موجود باشد.',
    'required_with' => 'در صورت وجود فیلد :values، فیلد :attribute نیز الزامی است.',
    'required_with_all' => 'در صورت وجود فیلدهای :values، فیلد :attribute نیز الزامی است.',
//    'required_without'     => 'در صورت عدم وجود فیلد :values، فیلد :attribute الزامی است.',
    'required_without' => 'فیلد :attribute الزامی است.',
    'required_without_all' => 'در صورت عدم وجود هر یک از فیلدهای :values، فیلد :attribute الزامی است.',
    'same' => ':attribute و :other باید همانند هم باشند.',
    'size' => [
        'numeric' => ':attribute باید برابر با :size باشد.',
        'file' => ':attribute باید برابر با :size کیلوبایت باشد.',
        'string' => ':attribute باید برابر با :size کاراکتر باشد.',
        'array' => ':attribute باید شامل :size آیتم باشد.',
    ],
    'starts_with' => ':attribute باید با یکی از این ها شروع شود: :values',
    'string' => 'فیلد :attribute باید رشته باشد.',
    'timezone' => 'فیلد :attribute باید یک منطقه زمانی معتبر باشد.',
    'unique' => ':attribute قبلا ثبت شده است.',
    'uploaded' => 'بارگذاری فایل :attribute موفقیت آمیز نبود.',
    'url' => ':attribute معتبر نمی‌باشد.',
    'uuid' => ':attribute باید یک UUID معتبر باشد.',
    'verifyCode' => ':attribute وارد شده اشتباه است',
    'userExist' => 'کاربری با شماره موبایل وارد شده یافت نشد .',


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
        /*'toneOfVoiceProfileCustom' => [
            'required_if' => 'در صورتیکه لحن مقاله "سفارشی" باشد این فیلد الزامی است.',
        ],
        'extraEntitiesPrompt' => [
            'required_if' => 'در صورتیکه بهینه سازی مقاله "دستی" باشد این فیلد الزامی است.',
        ],
        'numberOfSections' => [
            'required_if' => 'در صورتیکه طول مقاله "تعداد مشخصی از بخش ها" باشد این فیلد الزامی است.',
        ],
        'youTubeVideoUrl' => [
            'required_if' => 'در صورتیکه نوع مقاله "تبدیل ویدیو یوتیوب به مقاله وبلاگ" باشد این فیلد الزامی است.',
        ],
        'articleUrl' => [
            'required_if' => 'در صورتیکه نوع مقاله "بازنویسی مقاله" باشد این فیلد الزامی است.',
        ],
        'target_keyword' => [
            'required_if' => 'در صورتیکه نوع مقاله "بازنویسی مقاله" یا "مقاله وبلاگ" باشد این فیلد الزامی است.',
        ],
        'imageStyle' => [
            'required_if' => 'در صورتیکه تصاویر و ویدیوهای یوتیوب هوش مصنوعی باشد بر روی "خودکار" یا "تصاویر" باشد این فیلد الزامی است.',
        ],
        'imageSize' => [
            'required_if' => 'در صورتیکه تصاویر و ویدیوهای یوتیوب هوش مصنوعی باشد بر روی "خودکار" یا "تصاویر" باشد این فیلد الزامی است.',
        ],
        'maxImages' => [
            'required_if' => 'در صورتیکه تصاویر و ویدیوهای یوتیوب هوش مصنوعی باشد بر روی "خودکار" یا "تصاویر" باشد این فیلد الزامی است.',
        ],
        'maxVideos' => [
            'required_if' => 'در صورتیکه تصاویر و ویدیوهای یوتیوب هوش مصنوعی باشد بر روی "خودکار" یا "ویدیوها" باشد این فیلد الزامی است.',
        ],*/
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'name' => 'نام',
        'username' => 'نام کاربری',
        'email' => 'ایمیل',
        'first_name' => 'نام',
        'last_name' => 'نام خانوادگی',
        'password' => 'رمز عبور',
        'password_confirmation' => 'تکرار رمز عبور',
        'city' => 'شهر',
        'country' => 'کشور',
        'address' => 'آدرس',
        'phone' => 'شماره ثابت',
        'mobile' => 'شماره همراه',
        'age' => 'سن',
        'sex' => 'جنسیت',
        'gender' => 'جنسیت',
        'day' => 'روز',
        'month' => 'ماه',
        'year' => 'سال',
        'hour' => 'ساعت',
        'minute' => 'دقیقه',
        'second' => 'ثانیه',
        'title' => 'عنوان',
        'text' => 'متن',
        'content' => 'محتوا',
        'description' => 'توضیحات',
        'excerpt' => 'گزیده مطلب',
        'date' => 'تاریخ',
        'time' => 'زمان',
        'available' => 'موجود',
        'size' => 'اندازه',
        'terms' => 'شرایط',
        'province' => 'استان',
        'image' => 'تصویر آپلودی',
        'verify_code' => 'کد فعالسازی',
        'type' => 'نوع',
        'new_password' => 'رمز عبور جدید',

        'birth_day' => 'روز تولد',
        'birth_month' => 'ماه تولد',
        'birth_year' => 'سال تولد',
        'subject' => 'عنوان',
        'message' => 'پیام',
        'tracking_code' => 'کد رهگیری',
        'amount' => 'مبلغ',
        'count' => 'تعداد',
        'answer' => 'پاسخ',
        'rate' => 'شماره',
        'video' => 'فیلم',
        'file' => 'فایل',
        'cover' => 'کاور',
        'question' => 'سوال',
        'national_code' => 'کد ملی',
        'card_number' => 'شماره کارت',
        'expire_at' => 'مهلت پرداخت',
        'products.*.name' => 'نام',
        'products.*.quantity' => 'تعداد',
        'products.*.link' => 'لینک',
        'products.*.image' => 'تصویر',
        'products.*.compressed' => 'تصویر',
        'products.*.blob' => 'تصویر',
        'products.*.description' => 'توضیحات',
        'products.*.website_link' => 'لینک وبسایت',
        'products.*.persian_name' => 'نام فارسی',
        'products.*.single_price' => 'قیمت واحد',
        'products.*.currency' => 'ارز',
        'products.*.properties.*.property' => 'ویژگی',
        'products.*.properties.*.value' => 'مقدار',
        'post_code' => 'کد پستی',
        'sheba_number' => 'شماره شبا',
        'bank_name' => 'نام بانک',
        'g-recaptcha-response' => "reCAPTCHA",
        'settings.min_usd' => "حداقل خرید دلار",
        'settings.min_gbp' => "حداقل خرید پوند",
        'settings.min_rmb' => "حداقل خرید یوان",
        'settings.min_eur' => "حداقل خرید یورو",
        'settings.min_aed' => "حداقل خرید درهم",

        'recaptcha' => 'خطای اعتبارسنجی reCAPTCHA',
        'recaptcha3' => 'خطای اعتبارسنجی reCAPTCHA',
        'aed' => 'درهم',
        'usd' => 'دلار',
        'rmb' => 'یوان',
        'eur' => 'یورو',
        'gbp' => 'پوند',

        'gptVersion' => 'ورژن GPT',
        'articleType' => 'نوع مقاله',
        'youTubeVideoUrl' => 'URL ویدیو یوتیوب',
        'articleUrl' => 'URL مقاله',
        'target_keyword' => 'موضوع و کلیدواژه مقاله ',
        'seoOptimizationLevel' => 'بهینه سازی سئو',
        'extraEntitiesPrompt' => 'کلمات کلیدی / موضوعات',
        'rewriteAllSourceDataArticle' => 'بازنویسی',
        'rewriteAllSourceData' => 'بازنویسی',
        'realTimeData' => ' استفاده از نتایج آنلاین',
        'shouldCiteSources' => 'ذکر منابع',
        'outline' => ' استفاده از ویرایشگر طرح کلی',
        'includeFaq' => 'پرسش و پاسخ',
        'includeKeyTakeaways' => 'افزودن نکات کلیدی',
        'disableIntroduction' => ' غیرفعال کردن مقدمه',
        'disableConclusion' => ' غیرفعال کردن نتیجه گیری',
        'multimediaOption' => 'تصاویر و ویدیوهای یوتیوب هوش مصنوعی',
        'customInstructions' => 'دستورات سفارشی',
        'imageStyle' => 'نوع تصویر',
        'imageSize' => 'اندازه تصویر',
        'maxImages' => 'حداکثر تعداد تصاویر',
        'maxVideos' => 'حداکثر تعداد ویدیوها',
        'articleLength' => 'طول مقاله',
        'numberOfSections' => 'تعداد بخش‌ها',
        'toneOfVoiceProfile' => 'لحن مقاله',
        'toneOfVoiceProfileCustom' => 'لحن سفارشی مقاله',
        'pointOfView' => 'دید مقاله',
        'plan' => 'اشتراک انتخابی',
        'captcha' => 'کد کپتچا',
        'skills' => 'مهارت ها'
    ],
];

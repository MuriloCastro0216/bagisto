<?php

return [
    'seeders' => [
        'attribute' => [
            'attribute-families' => [
                'default' => 'الافتراضي',
            ],

            'attribute-groups' => [
                'description'      => 'الوصف',
                'general'          => 'عام',
                'inventories'      => 'المخزونات',
                'meta-description' => 'الوصف الواجب',
                'price'            => 'السعر',
                'settings'         => 'الإعدادات',
                'shipping'         => 'الشحن',
            ],

            'attributes' => [
                'brand'                => 'العلامة التجارية',
                'color'                => 'اللون',
                'cost'                 => 'التكلفة',
                'description'          => 'الوصف',
                'featured'             => 'مميز',
                'guest-checkout'       => 'الدفع كضيف',
                'height'               => 'الارتفاع',
                'length'               => 'الطول',
                'manage-stock'         => 'إدارة المخزون',
                'meta-description'     => 'الوصف الواجب',
                'meta-keywords'        => 'الكلمات الرئيسية الواجبة',
                'meta-title'           => 'العنوان الواجب',
                'name'                 => 'الاسم',
                'new'                  => 'جديد',
                'price'                => 'السعر',
                'product-number'       => 'رقم المنتج',
                'short-description'    => 'وصف مختصر',
                'size'                 => 'الحجم',
                'sku'                  => 'رمز المنتج',
                'special-price'        => 'السعر الخاص',
                'special-price-from'   => 'السعر الخاص من',
                'special-price-to'     => 'السعر الخاص حتى',
                'status'               => 'الحالة',
                'tax-category'         => 'فئة الضريبة',
                'url-key'              => 'الرابط المميز',
                'visible-individually' => 'مرئي بشكل فردي',
                'weight'               => 'الوزن',
                'width'                => 'العرض',
            ],

            'attribute-options' => [
                'black'  => 'أسود',
                'green'  => 'أخضر',
                'l'      => 'كبير',
                'm'      => 'وسط',
                'red'    => 'أحمر',
                's'      => 'صغير',
                'white'  => 'أبيض',
                'xl'     => 'كبير جداً',
                'yellow' => 'أصفر',
            ],
        ],

        'category' => [
            'categories' => [
                'description' => 'وصف الفئة الرئيسية',
                'name'        => 'الرئيسية',
            ],
        ],

        'cms' => [
            'pages' => [
                'about-us' => [
                    'content' => 'محتوى صفحة من نحن',
                    'title'   => 'من نحن',
                ],

                'contact-us' => [
                    'content' => 'محتوى صفحة اتصل بنا',
                    'title'   => 'اتصل بنا',
                ],

                'customer-service' => [
                    'content' => 'محتوى صفحة خدمة العملاء',
                    'title'   => 'خدمة العملاء',
                ],

                'payment-policy'   => [
                    'content' => 'محتوى صفحة سياسة الدفع',
                    'title'   => 'سياسة الدفع',
                ],

                'privacy-policy' => [
                    'content' => 'محتوى صفحة سياسة الخصوصية',
                    'title'   => 'سياسة الخصوصية',
                ],

                'refund-policy' => [
                    'content' => 'محتوى صفحة سياسة الاسترداد',
                    'title'   => 'سياسة الاسترداد',
                ],

                'return-policy' => [
                    'content' => 'محتوى صفحة سياسة الإرجاع',
                    'title'   => 'سياسة الإرجاع',
                ],

                'shipping-policy' => [
                    'content' => 'محتوى صفحة سياسة الشحن',
                    'title'   => 'سياسة الشحن',
                ],

                'terms-conditions' => [
                    'content' => 'محتوى صفحة الشروط والأحكام',
                    'title'   => 'الشروط والأحكام',
                ],

                'terms-of-use' => [
                    'content' => 'محتوى صفحة شروط الاستخدام',
                    'title'   => 'شروط الاستخدام',
                ],

                'whats-new' => [
                    'content' => 'محتوى صفحة ما الجديد',
                    'title'   => 'ما الجديد',
                ],
            ],
        ],

        'core' => [
            'channels' => [
                'meta-description' => 'وصف متجر تجريبي',
                'meta-keywords'    => 'الكلمات الرئيسية للمتجر التجريبي',
                'meta-title'       => 'متجر تجريبي',
                'name'             => 'افتراضي',
            ],

            'currencies' => [
                'AED' => 'الدرهم الإماراتي',
                'ARS' => 'البيزو الأرجنتيني',
                'AUD' => 'الدولار الأسترالي',
                'BDT' => 'التاكا البنغلاديشي',
                'BRL' => 'الريال البرازيلي',
                'CAD' => 'الدولار الكندي',
                'CHF' => 'الفرنك السويسري',
                'CLP' => 'البيزو التشيلي',
                'CNY' => 'اليوان الصيني',
                'COP' => 'البيزو الكولومبي',
                'CZK' => 'الكرونة التشيكية',
                'DKK' => 'الكرونة الدنماركية',
                'DZD' => 'الدينار الجزائري',
                'EGP' => 'الجنيه المصري',
                'EUR' => 'اليورو',
                'FJD' => 'الدولار الفيجي',
                'GBP' => 'الجنيه الاسترليني',
                'HKD' => 'الدولار الهونغ كونغي',
                'HUF' => 'الفورنت المجري',
                'IDR' => 'الروبية الإندونيسية',
                'ILS' => 'الشيكل الإسرائيلي',
                'INR' => 'الروبية الهندية',
                'JOD' => 'الدينار الأردني',
                'JPY' => 'الين الياباني',
                'KRW' => 'الوون الكوري الجنوبي',
                'KWD' => 'الدينار الكويتي',
                'KZT' => 'التينغ الكازاخستاني',
                'LBP' => 'الليرة اللبنانية',
                'LKR' => 'الروبية السريلانكية',
                'LYD' => 'الدينار الليبي',
                'MAD' => 'الدرهم المغربي',
                'MUR' => 'الروبية الموريشية',
                'MXN' => 'البيزو المكسيكي',
                'MYR' => 'الرينغيت الماليزي',
                'NGN' => 'النايرا النيجيرية',
                'NOK' => 'الكرونة النرويجية',
                'NPR' => 'الروبية النيبالية',
                'NZD' => 'الدولار النيوزيلندي',
                'OMR' => 'الريال العماني',
                'PAB' => 'البالبوا البنمي',
                'PEN' => 'السول البيروفي',
                'PHP' => 'البيزو الفلبيني',
                'PKR' => 'الروبية الباكستانية',
                'PLN' => 'الزلوتي البولندي',
                'PYG' => 'الغواراني الباراغواياني',
                'QAR' => 'الريال القطري',
                'RON' => 'الليو الروماني',
                'RUB' => 'الروبل الروسي',
                'SAR' => 'الريال السعودي',
                'SEK' => 'الكرونة السويدية',
                'SGD' => 'الدولار السنغافوري',
                'THB' => 'الباخت التايلاندي',
                'TND' => 'الدينار التونسي',
                'TRY' => 'الليرة التركية',
                'TWD' => 'الدولار التايواني',
                'UAH' => 'الهريفنيا الأوكرانية',
                'USD' => 'الدولار الأمريكي',
                'UZS' => 'السوم الأوزبكستاني',
                'VEF' => 'البوليفار الفنزويلي',
                'VND' => 'الدونج الفيتنامي',
                'XAF' => 'فرنك وسط أفريقي',
                'XOF' => 'فرنك غرب أفريقي',
                'ZAR' => 'الراند الجنوب أفريقي',
                'ZMW' => 'الكواشا الزامبي',
            ],

            'locales' => [
                'ar'    => 'العربية',
                'bn'    => 'البنغالية',
                'de'    => 'الألمانية',
                'en'    => 'الإنجليزية',
                'es'    => 'الإسبانية',
                'fa'    => 'الفارسية',
                'fr'    => 'الفرنسية',
                'he'    => 'العبرية',
                'hi_IN' => 'الهندية',
                'it'    => 'الإيطالية',
                'ja'    => 'اليابانية',
                'nl'    => 'الهولندية',
                'pl'    => 'البولندية',
                'pt_BR' => 'البرتغالية البرازيلية',
                'ru'    => 'الروسية',
                'sin'   => 'السينهالية',
                'tr'    => 'التركية',
                'uk'    => 'الأوكرانية',
                'zh_CN' => 'الصينية',
            ],
        ],

        'customer' => [
            'customer-groups' => [
                'general'   => 'عام',
                'guest'     => 'زائر',
                'wholesale' => 'جملة',
            ],
        ],

        'inventory' => [
            'inventory-sources' => [
                'name' => 'افتراضي',
            ],
        ],

        'shop'      => [
            'theme-customizations' => [
                'all-products' => [
                    'name' => 'جميع المنتجات',

                    'options' => [
                        'title' => 'جميع المنتجات',
                    ],
                ],

                'bold-collections' => [
                    'content' => [
                        'btn-title'   => 'عرض المجموعات',
                        'description' => 'نقدم لك مجموعاتنا البارزة الجديدة! قم بتحسين أناقتك مع تصاميم جريئة وعبارات حيوية. استكشف أنماطًا بارزة وألوانًا جريئة تعيد تعريف خزانتك. استعد لاعتناق الاستثنائية!',
                        'title'       => 'استعد لمجموعاتنا البارزة الجديدة!',
                    ],

                    'name' => 'مجموعات بارزة',
                ],

                'categories-collections' => [
                    'name' => 'تصنيفات المجموعات',
                ],

                'featured-collections' => [
                    'name' => 'مجموعات مميزة',

                    'options' => [
                        'title' => 'منتجات مميزة',
                    ],
                ],

                'footer-links' => [
                    'name' => 'روابط الذيل',

                    'options' => [
                        'about-us'         => 'معلومات عنا',
                        'contact-us'       => 'اتصل بنا',
                        'customer-service' => 'خدمة العملاء',
                        'payment-policy'   => 'سياسة الدفع',
                        'privacy-policy'   => 'سياسة الخصوصية',
                        'refund-policy'    => 'سياسة الاسترداد',
                        'return-policy'    => 'سياسة الإرجاع',
                        'shipping-policy'  => 'سياسة الشحن',
                        'terms-conditions' => 'الشروط والأحكام',
                        'terms-of-use'     => 'شروط الاستخدام',
                        'whats-new'        => 'ما الجديد',
                    ],
                ],

                'game-container' => [
                    'content' => [
                        'sub-title-1' => 'مجموعاتنا',
                        'sub-title-2' => 'مجموعاتنا',
                        'title'       => 'اللعبة مع إضافاتنا الجديدة!',
                    ],

                    'name' => 'حاوية اللعبة',
                ],

                'image-carousel' => [
                    'name' => 'عرض الصور',

                    'sliders' => [
                        'title' => 'استعد للمجموعة الجديدة',
                    ],
                ],

                'new-products' => [
                    'name'    => 'منتجات جديدة',

                    'options' => [
                        'title' => 'منتجات جديدة',
                    ],
                ],

                'offer-information' => [
                    'content' => [
                        'title' => 'احصل على خصم يصل إلى 40% على طلبك الأول. تسوق الآن',
                    ],

                    'name' => 'معلومات العرض',
                ],

                'services-content' => [
                    'description' => [
                        'emi-available-info'   => 'توفر EMI بدون تكلفة على جميع بطاقات الائتمان الرئيسية',
                        'free-shipping-info'   => 'استمتع بالشحن المجاني على جميع الطلبات',
                        'product-replace-info' => 'استبدال المنتج بسهولة متاح!',
                        'time-support-info'    => 'دعم مخصص على مدار الساعة عبر الدردشة والبريد الإلكتروني',
                    ],

                    'name' => 'محتوى الخدمات',

                    'title' => [
                        'emi-available'   => 'توفر EMI',
                        'free-shipping'   => 'الشحن المجاني',
                        'product-replace' => 'استبدال المنتج',
                        'time-support'    => 'الدعم على مدار الساعة',
                    ],
                ],

                'top-collections' => [
                    'content' => [
                        'sub-title-1' => 'مجموعاتنا',
                        'sub-title-2' => 'مجموعاتنا',
                        'sub-title-3' => 'مجموعاتنا',
                        'sub-title-4' => 'مجموعاتنا',
                        'sub-title-5' => 'مجموعاتنا',
                        'sub-title-6' => 'مجموعاتنا',
                        'title'       => 'اللعبة مع إضافاتنا الجديدة!',
                    ],

                    'name' => 'أفضل المجموعات',
                ],
            ],
        ],

        'user' => [
            'roles' => [
                'description' => 'سيكون لدى مستخدمي هذا الدور وصولًا كاملاً',
                'name'        => 'مدير',
            ],

            'users' => [
                'name' => 'مثال',
            ],
        ],
    ],

    'installer' => [
        'index' => [
            'create-administrator' => [
                'admin'            => 'المشرف',
                'bagisto'          => 'Bagisto',
                'confirm-password' => 'تأكيد كلمة المرور',
                'email'            => 'البريد الإلكتروني',
                'email-address'    => 'admin@example.com',
                'password'         => 'كلمة المرور',
                'title'            => 'إنشاء المسؤول',
            ],

            'environment-configuration' => [
                'algerian-dinar'              => 'الدينار الجزائري (DZD)',
                'allowed-currencies'          => 'العملات المسموح بها',
                'allowed-locales'             => 'اللغات المسموح بها',
                'application-name'            => 'اسم التطبيق',
                'argentine-peso'              => 'البيزو الأرجنتيني (ARS)',
                'australian-dollar'           => 'الدولار الأسترالي (AUD)',
                'bagisto'                     => 'Bagisto',
                'bangladeshi-taka'            => 'التاكا البنغلاديشي (BDT)',
                'brazilian-real'              => 'الريال البرازيلي (BRL)',
                'british-pound-sterling'      => 'الجنيه الإسترليني البريطاني (GBP)',
                'canadian-dollar'             => 'الدولار الكندي (CAD)',
                'cfa-franc-bceao'             => 'فرنك CFA BCEAO (XOF)',
                'cfa-franc-beac'              => 'فرنك CFA BEAC (XAF)',
                'chilean-peso'                => 'البيزو التشيلي (CLP)',
                'chinese-yuan'                => 'اليوان الصيني (CNY)',
                'colombian-peso'              => 'البيزو الكولومبي (COP)',
                'czech-koruna'                => 'الكرونة التشيكية (CZK)',
                'danish-krone'                => 'الكرونة الدنماركية (DKK)',
                'database-connection'         => 'اتصال قاعدة البيانات',
                'database-hostname'           => 'اسم مضيف قاعدة البيانات',
                'database-name'               => 'اسم قاعدة البيانات',
                'database-password'           => 'كلمة مرور قاعدة البيانات',
                'database-port'               => 'منفذ قاعدة البيانات',
                'database-prefix'             => 'بادئة قاعدة البيانات',
                'database-username'           => 'اسم مستخدم قاعدة البيانات',
                'default-currency'            => 'العملة الافتراضية',
                'default-locale'              => 'اللغة الافتراضية',
                'default-timezone'            => 'المنطقة الزمنية الافتراضية',
                'default-url'                 => 'الرابط الافتراضي',
                'default-url-link'            => 'https://localhost',
                'egyptian-pound'              => 'الجنيه المصري (EGP)',
                'euro'                        => 'اليورو (EUR)',
                'fijian-dollar'               => 'الدولار الفيجي (FJD)',
                'hong-kong-dollar'            => 'الدولار الهونغ كونغي (HKD)',
                'hungarian-forint'            => 'الفورنت الهنغاري (HUF)',
                'indian-rupee'                => 'الروبية الهندية (INR)',
                'indonesian-rupiah'           => 'الروبية الإندونيسية (IDR)',
                'israeli-new-shekel'          => 'الشيكل الإسرائيلي الجديد (ILS)',
                'japanese-yen'                => 'الين الياباني (JPY)',
                'jordanian-dinar'             => 'الدينار الأردني (JOD)',
                'kazakhstani-tenge'           => 'التينغ الكازاخستاني (KZT)',
                'kuwaiti-dinar'               => 'الدينار الكويتي (KWD)',
                'lebanese-pound'              => 'الجنيه اللبناني (LBP)',
                'libyan-dinar'                => 'الدينار الليبي (LYD)',
                'malaysian-ringgit'           => 'الرينغيت الماليزي (MYR)',
                'mauritian-rupee'             => 'الروبية الموريشية (MUR)',
                'mexican-peso'                => 'البيزو المكسيكي (MXN)',
                'moroccan-dirham'             => 'الدرهم المغربي (MAD)',
                'mysql'                       => 'MySQL',
                'nepalese-rupee'              => 'الروبية النيبالية (NPR)',
                'new-taiwan-dollar'           => 'الدولار التايواني الجديد (TWD)',
                'new-zealand-dollar'          => 'الدولار النيوزيلندي (NZD)',
                'nigerian-naira'              => 'النايرا النيجيري (NGN)',
                'norwegian-krone'             => 'الكرونة النرويجية (NOK)',
                'omani-rial'                  => 'الريال العماني (OMR)',
                'pakistani-rupee'             => 'الروبية الباكستانية (PKR)',
                'panamanian-balboa'           => 'البالبوا البنمي (PAB)',
                'paraguayan-guarani'          => 'الغواراني الباراغواي (PYG)',
                'peruvian-nuevo-sol'          => 'السول البيروفي الجديد (PEN)',
                'pgsql'                       => 'PgSQL',
                'philippine-peso'             => 'البيزو الفلبيني (PHP)',
                'polish-zloty'                => 'الزلوتي البولندي (PLN)',
                'qatari-rial'                 => 'الريال القطري (QAR)',
                'romanian-leu'                => 'الليو الروماني (RON)',
                'russian-ruble'               => 'الروبل الروسي (RUB)',
                'saudi-riyal'                 => 'الريال السعودي (SAR)',
                'select-timezone'             => 'اختر المنطقة الزمنية',
                'singapore-dollar'            => 'الدولار السنغافوري (SGD)',
                'south-african-rand'          => 'الراند الجنوب أفريقي (ZAR)',
                'south-korean-won'            => 'الوون الكوري الجنوبي (KRW)',
                'sqlsrv'                      => 'SQLSRV',
                'sri-lankan-rupee'            => 'الروبية السريلانكية (LKR)',
                'swedish-krona'               => 'الكرونا السويدية (SEK)',
                'swiss-franc'                 => 'الفرنك السويسري (CHF)',
                'thai-baht'                   => 'الباهت التايلاندي (THB)',
                'title'                       => 'إعدادات المتجر',
                'tunisian-dinar'              => 'الدينار التونسي (TND)',
                'turkish-lira'                => 'الليرة التركية (TRY)',
                'ukrainian-hryvnia'           => 'الهريفنيا الأوكرانية (UAH)',
                'united-arab-emirates-dirham' => 'الدرهم الإماراتي (AED)',
                'united-states-dollar'        => 'الدولار الأمريكي (USD)',
                'uzbekistani-som'             => 'السوم الأوزبكستاني (UZS)',
                'venezuelan-bolívar'          => 'البوليفار الفنزويلي (VEF)',
                'vietnamese-dong'             => 'الدونج الفيتنامي (VND)',
                'warning-message'             => 'تحذير! إعدادات اللغات الافتراضية لنظامك وكذلك العملة الافتراضية هي دائمة ولا يمكن تغييرها مرة أخرى.',
                'zambian-kwacha'              => 'الكواشا الزامبي (ZMW)',
            ],

            'installation-processing' => [
                'bagisto'          => 'تثبيت Bagisto',
                'bagisto-info'     => 'إنشاء جداول قاعدة البيانات، وقد يستغرق ذلك بضع دقائق',
                'title'            => 'التثبيت',
            ],

            'installation-completed' => [
                'admin-panel'                => 'لوحة المشرف',
                'bagisto-forums'             => 'منتديات Bagisto',
                'customer-panel'             => 'لوحة العميل',
                'explore-bagisto-extensions' => 'استكشاف امتدادات Bagisto',
                'title'                      => 'اكتمال التثبيت',
                'title-info'                 => 'تم تثبيت Bagisto بنجاح على نظامك.',
            ],

            'ready-for-installation' => [
                'create-databsae-table'   => 'إنشاء جدول قاعدة البيانات',
                'install'                 => 'التثبيت',
                'install-info'            => 'Bagisto للتثبيت',
                'install-info-button'     => 'انقر على الزر أدناه ل',
                'populate-database-table' => 'ملء جداول قاعدة البيانات',
                'start-installation'      => 'بدء التثبيت',
                'title'                   => 'جاهز للتثبيت',
            ],

            'start' => [
                'locale'        => 'اللغة',
                'main'          => 'بداية',
                'select-locale' => 'اختر اللغة',
                'title'         => 'تثبيت Bagisto الخاص بك',
                'welcome-title' => 'مرحبًا بك في Bagisto 2.0.',
            ],

            'server-requirements' => [
                'calendar'    => 'التقويم',
                'ctype'       => 'cType',
                'curl'        => 'cURL',
                'dom'         => 'dom',
                'fileinfo'    => 'معلومات الملف',
                'filter'      => 'المرشح',
                'gd'          => 'GD',
                'hash'        => 'التجزئة',
                'intl'        => 'intl',
                'json'        => 'JSON',
                'mbstring'    => 'mbstring',
                'openssl'     => 'openssl',
                'pcre'        => 'pcre',
                'pdo'         => 'pdo',
                'php'         => 'PHP',
                'php-version' => '8.1 أو أعلى',
                'session'     => 'الجلسة',
                'title'       => 'متطلبات الخادم',
                'tokenizer'   => 'tokenizer',
                'xml'         => 'XML',
            ],

            'arabic'                   => 'العربية',
            'back'                     => 'رجوع',
            'bagisto'                  => 'Bagisto',
            'bagisto-info'             => 'مشروع مجتمعي من قبل',
            'bagisto-logo'             => 'شعار Bagisto',
            'bengali'                  => 'البنغالية',
            'chinese'                  => 'الصينية',
            'continue'                 => 'متابعة',
            'dutch'                    => 'هولندي',
            'english'                  => 'الإنجليزية',
            'french'                   => 'الفرنسية',
            'german'                   => 'ألماني',
            'hebrew'                   => 'العبرية',
            'hindi'                    => 'الهندية',
            'installation-description' => 'تتضمن عملية تثبيت Bagisto عادة عدة خطوات. إليك مخطط عام لعملية التثبيت لـ Bagisto:',
            'installation-info'        => 'نحن سعداء برؤيتك هنا!',
            'installation-title'       => 'مرحبًا بك في التثبيت',
            'italian'                  => 'الإيطالية',
            'japanese'                 => 'اليابانية',
            'persian'                  => 'الفارسية',
            'polish'                   => 'البولندية',
            'portuguese'               => 'البرتغالية البرازيلية',
            'russian'                  => 'الروسية',
            'sinhala'                  => 'السنهالية',
            'spanish'                  => 'الإسبانية',
            'title'                    => 'مثبت Bagisto',
            'turkish'                  => 'التركية',
            'ukrainian'                => 'الأوكرانية',
            'webkul'                   => 'Webkul',
        ],
    ],
];

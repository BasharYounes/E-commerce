# أمثلة على كيفية عمل نظام الإشعارات

## السيناريوهات المختلفة

### 1. رسالة بدون متغيرات
```php
// في config/notifications.php
'create Adv' => [
    'title' => 'إشعار جديد', 
    'body' => 'تم نشر الإعلان بنجاح'
]

// الاستخدام
$notificationService->send($user, 'create Adv', []);

// النتيجة النهائية
// title: "إشعار جديد"
// body: "تم نشر الإعلان بنجاح"
```

### 2. رسالة مع متغيرات
```php
// في config/notifications.php
'welcome' => [
    'title' => 'مرحباً {{user_name}}', 
    'body' => 'مرحباً بك في التطبيق {{user_name}}!'
]

// الاستخدام
$notificationService->send($user, 'welcome', [
    'user_name' => 'أحمد'
]);

// النتيجة النهائية
// title: "مرحباً أحمد"
// body: "مرحباً بك في التطبيق أحمد!"
```

### 3. رسالة مع متغيرات جزئية
```php
// في config/notifications.php
'mixed' => [
    'title' => 'مرحباً {{user_name}}', 
    'body' => 'تم نشر الإعلان بنجاح'
]

// الاستخدام
$notificationService->send($user, 'mixed', [
    'user_name' => 'أحمد'
]);

// النتيجة النهائية
// title: "مرحباً أحمد"
// body: "تم نشر الإعلان بنجاح"
```

### 4. رسالة مع متغيرات غير موجودة
```php
// في config/notifications.php
'welcome' => [
    'title' => 'مرحباً {{user_name}}', 
    'body' => 'مرحباً بك في التطبيق {{user_name}}!'
]

// الاستخدام (بدون تمرير user_name)
$notificationService->send($user, 'welcome', []);

// النتيجة النهائية
// title: "مرحباً {{user_name}}"  // يبقى كما هو
// body: "مرحباً بك في التطبيق {{user_name}}!"  // يبقى كما هو
```

### 5. قالب غير موجود
```php
// الاستخدام مع قالب غير موجود
$notificationService->send($user, 'non_existent_template', []);

// النتيجة النهائية (يستخدم القالب الافتراضي)
// title: "إشعار جديد"
// body: "لديك إشعار جديد"
```

## كيفية إضافة قوالب جديدة

### 1. أضف القالب في config/notifications.php
```php
'templates' => [
    'new_message' => [
        'title' => 'رسالة جديدة من {{sender_name}}',
        'body' => 'لديك رسالة جديدة: {{message_preview}}'
    ],
    'order_status' => [
        'title' => 'تحديث حالة الطلب',
        'body' => 'تم تحديث حالة طلبك رقم {{order_id}} إلى {{status}}'
    ]
]
```

### 2. استخدم القالب في الكود
```php
// إشعار رسالة جديدة
$notificationService->send($user, 'new_message', [
    'sender_name' => 'أحمد محمد',
    'message_preview' => 'مرحباً، كيف حالك؟'
]);

// إشعار تحديث حالة الطلب
$notificationService->send($user, 'order_status', [
    'order_id' => '12345',
    'status' => 'تم الشحن'
]);
```

## نصائح مهمة

1. **استخدم أسماء متغيرات واضحة**: `{{user_name}}` بدلاً من `{{name}}`
2. **تأكد من تمرير جميع المتغيرات المطلوبة**
3. **اختبر القوالب قبل الاستخدام**
4. **استخدم قوالب افتراضية للرسائل العامة**

## اختبار القوالب

يمكنك اختبار القوالب باستخدام endpoint الاختبار:

```bash
# اختبار قالب موجود
curl -X POST http://your-domain/api/test-notification \
  -H "Authorization: Bearer YOUR_TOKEN"

# اختبار قالب مخصص
curl -X POST http://your-domain/api/send-notification-to-all \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "مرحباً {{user_name}}",
    "body": "تم نشر الإعلان بنجاح"
  }'
```

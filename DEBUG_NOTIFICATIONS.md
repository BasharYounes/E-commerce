# دليل تشخيص مشاكل الإشعارات

## 🔍 خطوات التشخيص

### 1. فحص حالة نظام الإشعارات
```bash
curl -X GET http://your-domain/api/debug-notification-system \
  -H "Authorization: Bearer YOUR_TOKEN"
```

هذا الـ endpoint سيفحص:
- ✅ وجود FCM token للمستخدم
- ✅ توفر Firebase messaging service
- ✅ وجود قوالب الإشعارات
- ✅ حالة قاعدة البيانات

### 2. اختبار إرسال إشعار مع تفاصيل مفصلة
```bash
curl -X POST http://your-domain/api/debug-test-notification \
  -H "Authorization: Bearer YOUR_TOKEN"
```

هذا الـ endpoint سيقوم بـ:
- إنشاء محتوى الإشعار
- إرسال الإشعار عبر Firebase
- حفظ الإشعار في قاعدة البيانات
- إرجاع تفاصيل كل خطوة

### 3. اختبار NotificationService
```bash
curl -X POST http://your-domain/api/debug-test-service \
  -H "Authorization: Bearer YOUR_TOKEN"
```

هذا الـ endpoint سيختبر NotificationService مباشرة.

## 🚨 المشاكل المحتملة والحلول

### 1. FCM Token غير موجود
**الأعراض:** `"fcm_token": {"exists": false}`
**الحل:** تأكد من أن Flutter يرسل FCM token بشكل صحيح

### 2. Firebase Service غير متاح
**الأعراض:** `"firebase_service": {"available": false}`
**الحل:** 
- تحقق من ملف Firebase credentials
- تأكد من متغيرات البيئة
- أعد تشغيل `php artisan config:cache`

### 3. قوالب الإشعارات غير موجودة
**الأعراض:** `"templates": {"available": false}`
**الحل:** تحقق من ملف `config/notifications.php`

### 4. مشكلة في قاعدة البيانات
**الأعراض:** `"database": {"available": false}`
**الحل:** تحقق من اتصال قاعدة البيانات

### 5. فشل إرسال عبر Firebase
**الأعراض:** خطأ في `firebase_response`
**الحل:** 
- تحقق من صحة FCM token
- تحقق من إعدادات Firebase
- راجع logs Laravel

## 📊 تفسير النتائج

### نتيجة ناجحة:
```json
{
  "status": "success",
  "message": "تم اختبار الإشعار بنجاح",
  "data": {
    "content_created": {
      "title": "اختبار إشعار",
      "body": "هذا إشعار تجريبي للاختبار"
    },
    "firebase_response": {
      "success": true,
      "message": "تم إرسال الإشعار عبر Firebase بنجاح"
    },
    "database_save": {
      "success": true,
      "notification_id": 123
    }
  }
}
```

### نتيجة فاشلة:
```json
{
  "status": "error",
  "message": "فشل اختبار الإشعار: [تفاصيل الخطأ]",
  "data": {
    "error": {
      "message": "تفاصيل الخطأ",
      "file": "مسار الملف",
      "line": "رقم السطر"
    }
  }
}
```

## 🔧 خطوات إضافية للتشخيص

### 1. فحص Logs Laravel
```bash
tail -f storage/logs/laravel.log | grep -i "notification\|fcm\|firebase"
```

### 2. فحص قاعدة البيانات
```sql
-- فحص FCM tokens
SELECT id, name, fcm_token FROM users WHERE fcm_token IS NOT NULL;

-- فحص الإشعارات
SELECT * FROM notifications ORDER BY created_at DESC LIMIT 10;
```

### 3. فحص متغيرات البيئة
```bash
php artisan tinker --execute="echo env('FIREBASE_PROJECT');"
php artisan tinker --execute="echo env('FIREBASE_CREDENTIALS');"
```

## 📝 تقرير المشكلة

عند الإبلاغ عن مشكلة، أرسل:

1. **نتيجة فحص النظام:**
   ```bash
   curl -X GET http://your-domain/api/debug-notification-system \
     -H "Authorization: Bearer YOUR_TOKEN"
   ```

2. **نتيجة اختبار الإشعار:**
   ```bash
   curl -X POST http://your-domain/api/debug-test-notification \
     -H "Authorization: Bearer YOUR_TOKEN"
   ```

3. **Logs Laravel** (آخر 50 سطر)

4. **معلومات البيئة:**
   - Laravel version
   - PHP version
   - Firebase project ID

## 🎯 النتائج المتوقعة

بعد إصلاح المشاكل، يجب أن تحصل على:

- ✅ FCM token موجود
- ✅ Firebase service متاح
- ✅ قوالب الإشعارات موجودة
- ✅ قاعدة البيانات متاحة
- ✅ إرسال الإشعار ناجح
- ✅ حفظ الإشعار ناجح

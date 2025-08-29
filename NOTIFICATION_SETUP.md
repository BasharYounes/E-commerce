# دليل إعداد الإشعارات - Firebase Cloud Messaging

## المشاكل التي تم إصلاحها

### 1. إعداد Firebase Service Provider
- تم إضافة `Kreait\Laravel\Firebase\ServiceProvider::class` إلى `bootstrap/providers.php`

### 2. إصلاح مسار ملف Firebase credentials
- تم نسخ ملف Firebase credentials إلى المسار المطلوب: `storage/app/firebase/serviceacountKey.json`

### 3. إصلاح NotificationService
- تم إصلاح دالة `getTemplate()` التي كانت معلقة
- تم تحسين دالة `sendFCM()` مع إضافة معالجة الأخطاء والتحقق من وجود FCM token

### 4. تحسين AuthController
- تم تغيير اسم الحقل من `fcm_token` إلى `fcm` ليتطابق مع Flutter app

## متغيرات البيئة المطلوبة

أضف هذه المتغيرات إلى ملف `.env`:

```env
# Firebase Configuration
FIREBASE_PROJECT=buyro-app
FIREBASE_CREDENTIALS=storage/app/firebase/serviceacountKey.json
FIREBASE_DATABASE_URL=https://buyro-app.firebaseio.com
FIREBASE_STORAGE_DEFAULT_BUCKET=buyro-app.appspot.com
FIREBASE_CACHE_STORE=file
```

## API Endpoints الجديدة

### 1. اختبار الإشعارات
```
POST /api/test-notification
Headers: Authorization: Bearer {token}
```

### 2. إرسال إشعار لجميع المستخدمين
```
POST /api/send-notification-to-all
Headers: Authorization: Bearer {token}
Body: {
    "title": "عنوان الإشعار",
    "body": "محتوى الإشعار"
}
```

## كيفية اختبار الإشعارات

### 1. تأكد من وجود FCM token للمستخدم
```bash
# تحقق من قاعدة البيانات
SELECT id, name, fcm_token FROM users WHERE fcm_token IS NOT NULL;
```

### 2. استخدم endpoint الاختبار
```bash
curl -X POST http://your-domain/api/test-notification \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json"
```

### 3. تحقق من logs Laravel
```bash
tail -f storage/logs/laravel.log
```

## مقارنة مع Flutter App

### Flutter Code:
```dart
await SendFcmTokenRemote().postData(fcm: token);
```

### Laravel Endpoint:
```php
Route::post('/store-fcm-token', [AuthController::class, 'storeFCM_Token']);
```

**المشكلة كانت:** Flutter يرسل `fcm` بينما Laravel كان يتوقع `fcm_token`

## نصائح للاستكشاف

1. **تحقق من logs Laravel** في `storage/logs/laravel.log`
2. **تأكد من صحة FCM token** في قاعدة البيانات
3. **اختبر الإشعارات** باستخدام endpoints الجديدة
4. **تحقق من إعدادات Firebase** في Firebase Console

## الأمان

- تأكد من أن ملف `serviceacountKey.json` غير موجود في Git
- استخدم HTTPS في الإنتاج
- تحقق من صلاحيات Firebase في Console

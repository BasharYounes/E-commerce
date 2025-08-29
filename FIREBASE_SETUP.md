# إعداد Firebase للإشعارات

## الخطوات المطلوبة لإعداد Firebase Cloud Messaging (FCM)

### 1. الحصول على ملف serviceacountKey.json

1. اذهب إلى [Firebase Console](https://console.firebase.google.com/)
2. اختر مشروعك أو أنشئ مشروع جديد
3. اضغط على أيقونة الترس (Settings) بجانب "Project Overview"
4. اختر "Project settings"
5. في القائمة الجانبية، اختر "Service accounts"
6. ستجد قسم "Firebase Admin SDK"
7. اختر "Generate new private key"
8. ستظهر نافذة تحذير - اضغط "Generate key"
9. سيتم تحميل ملف JSON يحتوي على مفاتيح المصادقة

### 2. وضع الملف في المشروع

1. ضع ملف `serviceacountKey.json` في المجلد: `storage/app/firebase/`
2. تأكد من أن اسم الملف هو: `serviceacountKey.json`

### 3. إعداد متغيرات البيئة

أضف هذه المتغيرات إلى ملف `.env`:

```env
# Firebase Configuration
FIREBASE_PROJECT=your-project-id
FIREBASE_CREDENTIALS=storage/app/firebase/serviceacountKey.json
```

**ملاحظة مهمة:** استبدل `your-project-id` بـ Project ID الخاص بك من Firebase Console.

### 4. إعدادات إضافية (اختيارية)

يمكنك إضافة هذه الإعدادات الإضافية إلى ملف `.env`:

```env
# إعدادات Firebase إضافية
FIREBASE_DATABASE_URL=https://your-project-id.firebaseio.com
FIREBASE_STORAGE_DEFAULT_BUCKET=your-project-id.appspot.com
FIREBASE_CACHE_STORE=file
```

### 5. اختبار الإعداد

بعد إكمال الخطوات السابقة، يمكنك اختبار الإشعارات من خلال:

1. تأكد من أن المستخدم لديه `fcm_token` في قاعدة البيانات
2. استخدم `NotificationService` لإرسال إشعار تجريبي

### 6. الأمان

**تحذير مهم:** 
- لا تشارك ملف `serviceacountKey.json` مع أي شخص
- تأكد من إضافته إلى `.gitignore` لعدم رفعه إلى Git
- استخدم متغيرات البيئة لحفظ المسارات الحساسة

### 7. استكشاف الأخطاء

إذا واجهت مشاكل:

1. تأكد من صحة مسار الملف في `FIREBASE_CREDENTIALS`
2. تأكد من صحة `FIREBASE_PROJECT`
3. تحقق من صلاحيات الملف
4. راجع logs Laravel للتفاصيل

## مثال على الاستخدام

```php
use App\Services\Notifications\NotificationService;

$notificationService = new NotificationService();
$user = User::find(1); // مستخدم لديه fcm_token
$notificationService->send($user, 'create Adv', ['user_name' => 'أحمد']);
```

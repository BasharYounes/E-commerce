# ميزة إشعارات المتابعين

## الوصف
تم إضافة ميزة جديدة لإرسال إشعارات تلقائية لجميع المتابعين عندما ينشر مستخدم إعلان جديد.

## الملفات المضافة/المحدثة

### 1. Event جديد
- `app/Events/AdPublishedEvent.php` - Event يتم إرساله عند نشر إعلان جديد

### 2. Listener جديد
- `app/Listeners/NotifyFollowersOfNewAd.php` - يتعامل مع Event ويرسل الإشعارات للمتابعين

### 3. Provider جديد
- `app/Providers/EventServiceProvider.php` - لتسجيل Event و Listener

### 4. ملفات محدثة
- `app/Services/Adv/AdCommandService.php` - إضافة إرسال Event عند إنشاء إعلان
- `config/notifications.php` - إضافة قالب الإشعار الجديد
- `app/Models/Follow.php` - إضافة العلاقات
- `bootstrap/providers.php` - تسجيل EventServiceProvider

## كيفية العمل

1. عندما ينشر مستخدم إعلان جديد، يتم إرسال `AdPublishedEvent`
2. `NotifyFollowersOfNewAd` Listener يتلقى الحدث
3. يتم الحصول على جميع متابعي الناشر
4. يتم إرسال إشعار لكل متابع يحتوي على:
   - اسم الناشر
   - وصف الإعلان
   - معرف الإعلان

## قالب الإشعار
```
العنوان: إعلان جديد من {{publisher_name}}
المحتوى: قام {{publisher_name}} بنشر إعلان جديد: {{ad_title}}
```

## التحسينات المضافة
- فحص وجود متابعين قبل إرسال الإشعارات
- استخدام Firebase Cloud Messaging للإشعارات الفورية
- حفظ الإشعارات في قاعدة البيانات
- دعم متغيرات ديناميكية في قوالب الإشعارات

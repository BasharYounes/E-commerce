# ميزة إشعارات المتابعين - دليل الاستخدام

## نظرة عامة
تم إضافة ميزة جديدة لإرسال إشعارات تلقائية لجميع المتابعين عندما ينشر مستخدم إعلان جديد. هذه الميزة تعمل بشكل تلقائي ولا تحتاج إلى تدخل من المطور.

## الملفات المضافة

### 1. Event جديد
**الملف:** `app/Events/AdPublishedEvent.php`
- يحتوي على بيانات الإعلان والناشر
- يتم إرساله عند إنشاء إعلان جديد

### 2. Listener جديد  
**الملف:** `app/Listeners/NotifyFollowersOfNewAd.php`
- يتعامل مع Event ويرسل الإشعارات
- يتحقق من وجود متابعين قبل الإرسال
- يستخدم NotificationService لإرسال الإشعارات

### 3. Provider جديد
**الملف:** `app/Providers/EventServiceProvider.php`
- يسجل العلاقة بين Event و Listener

## الملفات المحدثة

### 1. AdCommandService
**الملف:** `app/Services/Adv/AdCommandService.php`
- تم إضافة `AdPublishedEvent::dispatch($ad, auth()->user())` في دالة `createAd()`

### 2. تكوين الإشعارات
**الملف:** `config/notifications.php`
- تم إضافة قالب `new_ad_from_following`

### 3. نموذج Follow
**الملف:** `app/Models/Follow.php`
- تم إضافة العلاقات والخصائص

## كيفية العمل

### التدفق التلقائي:
1. المستخدم ينشر إعلان جديد
2. `AdCommandService::createAd()` يتم استدعاؤها
3. يتم إرسال `AdPublishedEvent`
4. `NotifyFollowersOfNewAd` يتلقى الحدث
5. يتم إرسال إشعار لكل متابع

### مثال على الإشعار:
```
العنوان: إعلان جديد من أحمد
المحتوى: قام أحمد بنشر إعلان جديد: سيارة للبيع
```

## الاختبار

تم إنشاء ملف اختبار `tests/Feature/FollowersNotificationTest.php` يحتوي على:
- اختبار إرسال الإشعارات للمتابعين
- اختبار عدم إرسال إشعارات عند عدم وجود متابعين

لتشغيل الاختبارات:
```bash
php artisan test tests/Feature/FollowersNotificationTest.php
```

## المتطلبات

- يجب أن يكون لدى المستخدمين متابعين
- يجب أن يكون لدى المتابعين FCM token للإشعارات الفورية
- يجب أن يكون Firebase مُعد بشكل صحيح

## ملاحظات مهمة

1. الإشعارات تُرسل فقط عند **إنشاء** إعلان جديد، وليس عند التحديث
2. يتم التحقق من وجود متابعين قبل إرسال الإشعارات
3. الإشعارات تُحفظ في قاعدة البيانات وتُرسل عبر Firebase
4. يمكن تخصيص قوالب الإشعارات من خلال `config/notifications.php`

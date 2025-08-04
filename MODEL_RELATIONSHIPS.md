# العلاقات بين Models في مشروع Ecommerce

## نظرة عامة على العلاقات

### 1. User Model
```php
// Many-to-Many relationships through pivot tables
public function likedAdvs()       // User <-> Adv (through likes table)
public function favoriteAdvs()    // User <-> Adv (through favorites table)
public function evaluatedAdvs()   // User <-> Adv (through evaluations table)
public function reportedAdvs()    // User <-> Adv (through reports table)

// One-to-Many relationships
public function advs()           // User -> Adv (created ads)
```

### 2. Adv Model
```php
// Many-to-Many relationships through pivot tables
public function likedByUsers()    // Adv <-> User (through likes table)
public function favoritedByUsers() // Adv <-> User (through favorites table)
public function evaluatedByUsers() // Adv <-> User (through evaluations table)
public function reportedByUsers() // Adv <-> User (through reports table)

// Many-to-One relationships
public function category()        // Adv -> Category
public function user()           // Adv -> User (creator)
```

### 3. Category Model
```php
// One-to-Many relationships
public function advs()           // Category -> Adv
public function advReads()       // Category -> AdvRead
```

### 4. Like Model (Pivot Table)
```php
// Many-to-One relationships
public function user()           // Like -> User
public function adv()            // Like -> Adv
```

### 5. Report Model (Pivot Table with additional data)
```php
// Many-to-One relationships
public function user()           // Report -> User
public function adv()            // Report -> Adv
```

### 6. Evaluation Model (Pivot Table with additional data)
```php
// Many-to-One relationships
public function user()           // Evaluation -> User
public function adv()            // Evaluation -> Adv
```

### 7. Favorite Model (Pivot Table)
```php
// Many-to-One relationships
public function user()           // Favorite -> User
public function adv()            // Favorite -> Adv
```

## تفاصيل العلاقات

### User ↔ Adv (Many-to-Many through likes)
- **User** يمكن أن يعجب بالعديد من الإعلانات
- **Adv** يمكن أن يعجب به العديد من المستخدمين
- **جدول وسيط**: `likes`

### User ↔ Adv (Many-to-Many through favorites)
- **User** يمكن أن يضيف العديد من الإعلانات للمفضلة
- **Adv** يمكن أن يكون في مفضلة العديد من المستخدمين
- **جدول وسيط**: `favorites`

### User ↔ Adv (Many-to-Many through evaluations)
- **User** يمكن أن يقيم العديد من الإعلانات
- **Adv** يمكن أن يقيمه العديد من المستخدمين
- **جدول وسيط**: `evaluations` (مع بيانات إضافية: rating, comment)

### User ↔ Adv (Many-to-Many through reports)
- **User** يمكن أن يبلغ عن العديد من الإعلانات
- **Adv** يمكن أن يبلغ عنه العديد من المستخدمين
- **جدول وسيط**: `reports` (مع بيانات إضافية: type, content)

### User ↔ Adv (One-to-Many for creation)
- **User** يمكن أن ينشئ العديد من الإعلانات
- **Adv** ينتمي إلى مستخدم واحد فقط (المنشئ)

### Category ↔ Adv (One-to-Many)
- **Category** يمكن أن يحتوي على العديد من الإعلانات
- **Adv** ينتمي إلى فئة واحدة فقط

## استخدام العلاقات

### أمثلة على الاستعلامات

```php
// الحصول على جميع الإعلانات التي أعجب بها المستخدم
$user = User::find(1);
$likedAds = $user->likedAdvs;

// الحصول على جميع الإعلانات في مفضلة المستخدم
$user = User::find(1);
$favoriteAds = $user->favoriteAdvs;

// الحصول على جميع الإعلانات التي قيمها المستخدم مع التقييمات
$user = User::find(1);
$evaluatedAds = $user->evaluatedAdvs;
foreach($evaluatedAds as $adv) {
    echo "Rating: " . $adv->pivot->rating;
    echo "Comment: " . $adv->pivot->comment;
}

// الحصول على جميع الإعلانات التي أبلغ عنها المستخدم مع تفاصيل البلاغ
$user = User::find(1);
$reportedAds = $user->reportedAdvs;
foreach($reportedAds as $adv) {
    echo "Report Type: " . $adv->pivot->type;
    echo "Report Content: " . $adv->pivot->content;
}

// الحصول على جميع المستخدمين الذين أعجبوا بإعلان معين
$adv = Adv::find(1);
$usersWhoLiked = $adv->likedByUsers;

// الحصول على جميع المستخدمين الذين أضافوا إعلان للمفضلة
$adv = Adv::find(1);
$usersWhoFavorited = $adv->favoritedByUsers;

// الحصول على جميع المستخدمين الذين قيموا إعلان معين مع التقييمات
$adv = Adv::find(1);
$usersWhoEvaluated = $adv->evaluatedByUsers;
foreach($usersWhoEvaluated as $user) {
    echo "User: " . $user->name . " Rating: " . $user->pivot->rating;
}

// الحصول على جميع المستخدمين الذين أبلغوا عن إعلان معين مع تفاصيل البلاغ
$adv = Adv::find(1);
$usersWhoReported = $adv->reportedByUsers;
foreach($usersWhoReported as $user) {
    echo "User: " . $user->name . " Report Type: " . $user->pivot->type;
}

// التحقق من إعجاب المستخدم بإعلان معين
$user = User::find(1);
$adv = Adv::find(1);
$hasLiked = $user->likedAdvs()->where('adv_id', $adv->id)->exists();

// إضافة إعلان لمفضلة المستخدم
$user = User::find(1);
$adv = Adv::find(1);
$user->favoriteAdvs()->attach($adv->id);

// إزالة إعلان من مفضلة المستخدم
$user->favoriteAdvs()->detach($adv->id);

// إضافة تقييم لإعلان
$user = User::find(1);
$adv = Adv::find(1);
$user->evaluatedAdvs()->attach($adv->id, [
    'rating' => 5,
    'comment' => 'Great product!'
]);

// إضافة بلاغ لإعلان
$user = User::find(1);
$adv = Adv::find(1);
$user->reportedAdvs()->attach($adv->id, [
    'type' => 'spam',
    'content' => 'This is spam content'
]);

// تحديث تقييم موجود
$user->evaluatedAdvs()->updateExistingPivot($adv->id, [
    'rating' => 4,
    'comment' => 'Updated comment'
]);

// تحديث بلاغ موجود
$user->reportedAdvs()->updateExistingPivot($adv->id, [
    'type' => 'inappropriate_content',
    'content' => 'Updated report content'
]);

// الحصول على متوسط تقييم إعلان
$adv = Adv::find(1);
$averageRating = $adv->evaluatedByUsers()->avg('rating');

// الحصول على عدد البلاغات على إعلان
$adv = Adv::find(1);
$reportsCount = $adv->reportedByUsers()->count();

// الحصول على عدد الإعجابات لإعلان
$adv = Adv::find(1);
$likesCount = $adv->likedByUsers()->count();

// الحصول على جميع إعلانات المستخدم (التي أنشأها)
$user = User::find(1);
$userCreatedAds = $user->advs;

// الحصول على المستخدم الذي أنشأ الإعلان
$adv = Adv::find(1);
$creator = $adv->user;

// الحصول على فئة الإعلان
$adv = Adv::find(1);
$category = $adv->category;

// الحصول على جميع الإعلانات في فئة معينة
$category = Category::find(1);
$ads = $category->advs;
```

## ملاحظات مهمة

1. **Many-to-Many**: العلاقات بين `User` و `Adv` من خلال `likes`, `favorites`, `evaluations`, و `reports` هي علاقات Many-to-Many
2. **Pivot Tables**: جداول `likes`, `favorites`, `evaluations`, و `reports` تعمل كجداول وسيطة (pivot tables)
3. **Additional Data**: جداول `evaluations` و `reports` تحتوي على بيانات إضافية لذا نستخدم `withPivot()`
4. **One-to-Many**: العلاقة بين `User` و `Adv` من خلال `user_id` في جدول `advs` هي One-to-Many (المستخدم ينشئ الإعلانات)
5. **Methods**: يمكن استخدام `attach()`, `detach()`, `sync()`, `updateExistingPivot()` مع العلاقات Many-to-Many
6. **Timestamps**: جميع الجداول تحتوي على `timestamps` ما عدا الجداول الوسيطة 
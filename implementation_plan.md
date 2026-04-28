# 📋 خطة تنفيذ مشروع: المنصة الذكية لحفظ القرآن الكريم ومتابعته

> **Smart Quran Memorization & Tracking Platform**

---

## 📊 تحليل الوضع الحالي للمشروع

### ✅ ما تم إنجازه (موجود في الكود)

| العنصر | الحالة | الملاحظات |
|--------|--------|-----------|
| مشروع Laravel 12 | ✅ مُنشأ | يعمل مع Vite + Tailwind CSS v4 |
| قاعدة بيانات MySQL | ✅ مُهيأ | `.env` يشير إلى `smart_qurann_app` |
| 12 Migration | ✅ مكتملة | roles, users, profiles, role_user, surahs, ayahs, progress, attempts, questions, quiz |
| 9 Models | ✅ مكتملة | User, Role, Profile, Surah, Ayah, UserMemorizationProgress, RecitationAttempt, GeneratedQuestion, UserQuizAttempt |
| العلاقات (Relations) | ✅ مكتملة | belongsTo, hasMany, belongsToMany كلها موصولة |
| 6 Controllers للمستخدم | ✅ هيكل أساسي | Dashboard, Quran, Recitation, Review, Quiz, Profile |
| Routes (web.php) | ✅ مكتملة | كل المسارات معرّفة مع middleware auth |
| خوارزمية SRS | ✅ مكتوبة | SuperMemo-2 في RecitationController |

### ❌ ما هو مفقود (يجب تنفيذه)

| العنصر | الأولوية | التفاصيل |
|--------|----------|----------|
| Laravel Breeze | 🔴 حرج | **غير مُثبّت** - لا يوجد في composer.json ولا ملف `routes/auth.php` |
| FilamentPHP v3 | 🔴 حرج | **غير مُثبّت** - لا يوجد في composer.json ولا مجلد `app/Filament` |
| Seeders | 🔴 حرج | فقط `DatabaseSeeder` افتراضي - لا يوجد RoleSeeder, AdminSeeder, QuranDataSeeder |
| Artisan Command `quran:sync` | 🔴 حرج | غير موجود - مطلوب لجلب بيانات القرآن من API |
| Blade Views | 🔴 حرج | فقط `welcome.blade.php` - **صفر صفحات للمستخدم** |
| Filament Resources | 🔴 حرج | لا يوجد أي Resource أو Widget |
| Alpine.js تسجيل صوت | 🟡 مهم | واجهة Web Audio API غير موجودة |
| تكامل AI (Whisper/Gemini) | 🟡 مهم | الكود الحالي يستخدم Python Server خارجي (يجب تحويله لـ HTTP مباشر) |
| إعدادات `.env` للـ AI | 🟡 مهم | لا يوجد `OPENAI_API_KEY` أو `GEMINI_API_KEY` |
| ملف `bla.php` | 🔵 تنظيف | Controller فارغ يجب حذفه |

---

## 🏗️ خطة التنفيذ المفصّلة

---

### المرحلة 1: الإعداد والبنية التحتية ⚙️

> **الهدف:** تثبيت Laravel Breeze + FilamentPHP وتهيئة البيئة

#### 1.1 تثبيت Laravel Breeze

```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run build
```

**الملفات المُنشأة تلقائياً:**
- `routes/auth.php` — مسارات تسجيل الدخول والتسجيل
- `app/Http/Controllers/Auth/*` — LoginController, RegisterController, etc.
- `resources/views/auth/*` — login.blade.php, register.blade.php
- `resources/views/layouts/app.blade.php` — Layout رئيسي
- `resources/views/layouts/guest.blade.php` — Layout للزوار
- `resources/views/components/*` — مكونات Blade

#### 1.2 تثبيت FilamentPHP v3

```bash
composer require filament/filament:"^3.3" -W
php artisan filament:install --panels
```

**الملفات المُنشأة:**
- `app/Providers/Filament/AdminPanelProvider.php`
- `config/filament.php`

#### 1.3 تحديث `.env`

```env
APP_NAME="Smart Quran Platform"
APP_LOCALE=ar
APP_FALLBACK_LOCALE=ar

# مفاتيح API للذكاء الاصطناعي
GEMINI_API_KEY=your-gemini-api-key-here
OPENAI_API_KEY=your-openai-api-key-here

# تخزين الصوت محلياً (بدلاً من S3)
FILESYSTEM_DISK=local
```

#### [MODIFY] [.env](file:///d:/Tecjno-Injaz/smart-quran-app/.env)
- إضافة مفاتيح API
- تغيير اللغة إلى العربية

#### [DELETE] [bla.php](file:///d:/Tecjno-Injaz/smart-quran-app/app/Http/Controllers/bla.php)
- حذف Controller الفارغ غير المستخدم

---

### المرحلة 2: قاعدة البيانات، Seeders وأمر مزامنة القرآن 🗄️

> **الهدف:** تعبئة قاعدة البيانات ببيانات القرآن الكريم والأدوار

#### 2.1 الملفات الجديدة

#### [NEW] `database/seeders/RoleSeeder.php`
إنشاء الأدوار: `admin`, `student`

#### [NEW] `database/seeders/AdminUserSeeder.php`
إنشاء حساب المدير:
- الاسم: `المدير العام`
- البريد: `admin@smartquran.com`
- كلمة المرور: `password`
- الدور: `admin`

#### [NEW] `database/seeders/StudentSeeder.php`
إنشاء 5 حسابات طلاب وهمية مع بروفايلات

#### [NEW] `app/Console/Commands/SyncQuranDataCommand.php`
```
php artisan quran:sync
```
**المنطق:**
1. جلب بيانات السور والآيات من `http://api.alquran.cloud/v1/quran/quran-uthmani`
2. جلب النص الإملائي من `http://api.alquran.cloud/v1/quran/ar.alafasy` (للصوت)
3. تخزين 114 سورة و 6236 آية في قاعدة البيانات
4. Progress bar لمتابعة التقدم

#### [MODIFY] `database/seeders/DatabaseSeeder.php`
تعديل لاستدعاء كل الـ Seeders بالترتيب الصحيح

---

### المرحلة 3: لوحة الإدارة (FilamentPHP) 📊

> **الهدف:** بناء لوحة تحكم إدارية كاملة

#### 3.1 Filament Resources

#### [NEW] `app/Filament/Resources/UserResource.php`
- عرض/تعديل/حذف المستخدمين
- فلاتر حسب الدور
- **Relation Manager:** `MemorizationProgressRelationManager` — عرض تقدم حفظ كل طالب

#### [NEW] `app/Filament/Resources/SurahResource.php`
- عرض فقط (View-only) — قائمة الـ 114 سورة
- **Relation Manager:** `AyahsRelationManager` — عرض آيات السورة

#### [NEW] `app/Filament/Resources/RecitationAttemptResource.php`
- عرض فقط — اسم الطالب، الآية، نسبة التطابق
- مشغل صوتي HTML `<audio>` للاستماع للمحاولة
- فلتر حسب `is_passed`

#### 3.2 Filament Widgets

#### [NEW] `app/Filament/Widgets/StatsOverviewWidget.php`
- إجمالي المستخدمين
- إجمالي الآيات المحفوظة
- محاولات التسميع اليوم

#### [NEW] `app/Filament/Widgets/LatestRecitationsChart.php`
- مخطط خطي (Line Chart) يوضح المحاولات خلال آخر 7 أيام

---

### المرحلة 4: واجهة المستخدم (Blade + Tailwind + Alpine.js) 🎨

> **الهدف:** تصميم واجهة عصرية بطابع إسلامي

#### 4.1 Layout الرئيسي

#### [MODIFY] `resources/views/layouts/app.blade.php`
- شريط تنقل علوي (Navbar) مع شعار + روابط
- Sidebar قابل للإخفاء
- دعم RTL كامل
- خطوط عربية (Amiri أو Noto Naskh Arabic)
- تدرجات لونية إسلامية (ذهبي + أخضر غامق + أزرق داكن)

#### 4.2 الصفحة الرئيسية (Landing Page)

#### [MODIFY] `resources/views/welcome.blade.php`
- Hero Section مع عنوان المنصة ووصفها
- قسم المميزات (التسميع الذكي، التكرار المتباعد، الاختبارات)
- زر "ابدأ رحلة الحفظ" → يوجه لصفحة التسجيل
- تصميم عصري بـ Glassmorphism وزخارف إسلامية

#### 4.3 لوحة تحكم الطالب

#### [NEW] `resources/views/user/dashboard.blade.php`
- بطاقات إحصائية: عدد الآيات المحفوظة، نسبة التقدم، أيام الالتزام
- قائمة "الآيات المستحقة للمراجعة اليوم" مع أزرار إجراء
- آخر المحاولات (Timeline)
- شريط تقدم عام

#### 4.4 تصفح القرآن

#### [NEW] `resources/views/user/quran/index.blade.php`
- شبكة بطاقات (Grid) لعرض الـ 114 سورة
- كل بطاقة: رقم السورة، الاسم بالعربية والإنجليزية، عدد الآيات، نوع الوحي
- بحث وفلترة (مكية/مدنية)
- ألوان مختلفة لنوع الوحي

#### [NEW] `resources/views/user/quran/show.blade.php`
- عرض آيات السورة بخط عثماني كبير وواضح
- لكل آية:
  - زر 🔊 لتشغيل الصوت الرسمي
  - زر "ابدأ الحفظ" لإضافتها للورد
  - مؤشر حالة الحفظ (جديدة/قيد التعلم/محفوظة)
  - زر 🎤 للذهاب لصفحة التسميع

#### 4.5 صفحة المراجعة (SRS)

#### [NEW] `resources/views/user/reviews/index.blade.php`
- عرض الآيات المستحقة للمراجعة
- كل آية مع نص عثماني + اسم السورة + رقم الآية
- زر "سمّع الآن" → يوجه لصفحة التسميع
- فلترة حسب الأولوية (الأقدم أولاً)

#### 4.6 صفحة التسميع (Audio Recording)

#### [NEW] `resources/views/user/recitation/create.blade.php`
- عرض نص الآية المطلوب تسميعها
- زر تشغيل الصوت الرسمي (للمساعدة)
- **أزرار التسجيل (Alpine.js):**
  - 🎤 بدء التسجيل
  - ⏹️ إيقاف التسجيل
  - 📤 ارفع ملف صوتي
  - ✅ أرسل للتقييم
- عرض نتيجة التقييم (نسبة التطابق + الأخطاء + ناجح/راسب)

#### 4.7 صفحة الاختبارات

#### [NEW] `resources/views/user/quiz/show.blade.php`
- عرض 5 أسئلة عشوائية عن السورة
- كل سؤال مع 4 خيارات (اختيار من متعدد)
- تفاعل Alpine.js لإظهار النتيجة فوراً
- عرض الإجابة الصحيحة بعد الإجابة

#### 4.8 الملف الشخصي

#### [NEW] `resources/views/user/profile/edit.blade.php`
- تعديل الاسم والهاتف والدولة والنبذة
- رفع صورة شخصية
- عرض إحصائيات الحفظ الشخصية

---

### المرحلة 5: تسجيل الصوت (Alpine.js + Web Audio API) 🎙️

> **الهدف:** بناء واجهة تسجيل صوتي تعمل في المتصفح

#### [NEW] `resources/js/audio-recorder.js`
```javascript
// Alpine.js Component
// - navigator.mediaDevices.getUserMedia({ audio: true })
// - MediaRecorder API
// - تسجيل chunks → Blob (audio/webm)
// - إرسال عبر Fetch API إلى RecitationController@store
// - عرض حالة التسجيل (مؤقت زمني + موجة صوتية)
```

#### [NEW] `resources/js/audio-visualizer.js`
- موجة صوتية مرئية أثناء التسجيل (Canvas/SVG)
- مؤقت زمني للتسجيل

---

### المرحلة 6: منطق الذكاء الاصطناعي (Laravel HTTP) 🤖

> **الهدف:** تحويل الكود من Python Server إلى HTTP مباشر

#### [MODIFY] `app/Http/Controllers/User/RecitationController.php`
إعادة كتابة `store()`:
1. **تخزين محلي** بدلاً من S3: `$request->file('audio')->store('recitations')`
2. **Speech-to-Text عبر Gemini:**
   ```php
   Http::withHeaders([
       'Content-Type' => 'application/json',
   ])->post('https://generativelanguage.googleapis.com/v1/models/gemini-pro:generateContent?key=' . env('GEMINI_API_KEY'), [
       // إرسال الصوت كـ base64
   ]);
   ```
   أو **OpenAI Whisper:**
   ```php
   Http::withToken(env('OPENAI_API_KEY'))
       ->attach('file', file_get_contents($audioPath), 'audio.webm')
       ->post('https://api.openai.com/v1/audio/transcriptions', [
           'model' => 'whisper-1',
           'language' => 'ar',
       ]);
   ```
3. **مطابقة النص:** خوارزمية `similar_text()` أو `levenshtein()` في PHP
4. **حد النجاح:** `similarity_score >= 90%`

#### [NEW] `app/Services/SpeechToTextService.php`
- Service class مُنظّم لاستدعاء Gemini أو Whisper API
- دعم fallback (إذا فشل Gemini → جرّب Whisper)
- إعادة خطأ واضح في حالة التعذّر

#### [NEW] `app/Services/TextMatchingService.php`
- مقارنة النص المُفرّغ مع `text_imlaei`
- حساب `similarity_score` بدقة
- تحديد عدد الأخطاء `mistakes_count`
- تنظيف النص من التشكيل والمسافات الزائدة

---

### المرحلة 7: نظام التكرار المتباعد SRS 🔄

> **الهدف:** تنقيح خوارزمية SuperMemo-2

#### [NEW] `app/Services/SpacedRepetitionService.php`
نقل منطق SRS من RecitationController إلى Service مستقل:
```php
class SpacedRepetitionService
{
    // خوارزمية SuperMemo-2 الكاملة
    public function calculateNextReview(UserMemorizationProgress $progress, float $quality): array
    {
        // 1. حساب easiness_factor الجديد
        // 2. حساب interval_days بناءً على repetition_count
        // 3. حساب next_review_date
        // 4. تحديث الحالة (learning → memorized)
    }

    // تحويل similarity_score إلى quality (0-5)
    private function scoreToQuality(float $score): int { ... }
}
```

#### [MODIFY] `app/Http/Controllers/User/RecitationController.php`
- استخدام `SpacedRepetitionService` بدلاً من الدالة الخاصة
- Dependency Injection في constructor

---

### المرحلة 8: توليد الاختبارات بالذكاء الاصطناعي 📝

> **الهدف:** توليد أسئلة اختيار من متعدد تلقائياً

#### [NEW] `app/Services/QuizGeneratorService.php`
```php
class QuizGeneratorService
{
    public function generateForAyah(Ayah $ayah): GeneratedQuestion
    {
        $prompt = "أنشئ سؤال اختيار من متعدد باللغة العربية...";
        // إرسال إلى Gemini API أو GPT-4
    }
}
```

#### [MODIFY] `app/Http/Controllers/User/QuizController.php`
- في `show()`: إذا لم توجد أسئلة → توليدها تلقائياً عبر AI
- Cache الأسئلة المُولّدة لعدم تكرار الاستدعاء

---

## 📁 هيكل الملفات النهائي (الكامل)

```
smart-quran-app/
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       └── SyncQuranDataCommand.php          ← [جديد]
│   ├── Filament/
│   │   ├── Resources/
│   │   │   ├── UserResource.php                   ← [جديد]
│   │   │   ├── UserResource/
│   │   │   │   ├── Pages/
│   │   │   │   └── RelationManagers/
│   │   │   │       └── MemorizationProgressRelationManager.php
│   │   │   ├── SurahResource.php                  ← [جديد]
│   │   │   ├── SurahResource/
│   │   │   │   └── RelationManagers/
│   │   │   │       └── AyahsRelationManager.php
│   │   │   └── RecitationAttemptResource.php      ← [جديد]
│   │   └── Widgets/
│   │       ├── StatsOverviewWidget.php            ← [جديد]
│   │       └── LatestRecitationsChart.php         ← [جديد]
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Auth/                              ← [جديد - Breeze]
│   │       └── User/
│   │           ├── DashboardController.php        ← [تعديل]
│   │           ├── QuranController.php            ← موجود
│   │           ├── RecitationController.php       ← [تعديل رئيسي]
│   │           ├── ReviewController.php           ← موجود
│   │           ├── QuizController.php             ← [تعديل]
│   │           └── ProfileController.php          ← موجود
│   ├── Models/                                    ← موجود ومكتمل ✅
│   ├── Providers/
│   │   ├── AppServiceProvider.php                 ← [تعديل]
│   │   └── Filament/
│   │       └── AdminPanelProvider.php             ← [جديد - Filament]
│   └── Services/
│       ├── SpeechToTextService.php                ← [جديد]
│       ├── TextMatchingService.php                ← [جديد]
│       ├── SpacedRepetitionService.php            ← [جديد]
│       └── QuizGeneratorService.php               ← [جديد]
├── database/
│   ├── migrations/                                ← موجود ومكتمل ✅
│   └── seeders/
│       ├── DatabaseSeeder.php                     ← [تعديل]
│       ├── RoleSeeder.php                         ← [جديد]
│       ├── AdminUserSeeder.php                    ← [جديد]
│       └── StudentSeeder.php                      ← [جديد]
├── resources/
│   ├── css/
│   │   └── app.css                                ← [تعديل - تصميم إسلامي]
│   ├── js/
│   │   ├── app.js                                 ← [تعديل]
│   │   ├── audio-recorder.js                      ← [جديد]
│   │   └── audio-visualizer.js                    ← [جديد]
│   └── views/
│       ├── welcome.blade.php                      ← [تعديل كامل]
│       ├── layouts/
│       │   ├── app.blade.php                      ← [جديد - Breeze]
│       │   └── guest.blade.php                    ← [جديد - Breeze]
│       ├── auth/                                  ← [جديد - Breeze]
│       │   ├── login.blade.php
│       │   ├── register.blade.php
│       │   └── ...
│       ├── components/                            ← [جديد - Breeze]
│       └── user/
│           ├── dashboard.blade.php                ← [جديد]
│           ├── quran/
│           │   ├── index.blade.php                ← [جديد]
│           │   └── show.blade.php                 ← [جديد]
│           ├── recitation/
│           │   └── create.blade.php               ← [جديد]
│           ├── reviews/
│           │   └── index.blade.php                ← [جديد]
│           ├── quiz/
│           │   └── show.blade.php                 ← [جديد]
│           └── profile/
│               └── edit.blade.php                 ← [جديد]
└── routes/
    ├── web.php                                    ← موجود ✅
    ├── auth.php                                   ← [جديد - Breeze]
    └── console.php                                ← [تعديل]
```

---

## 🎨 أسلوب التصميم (Design System)

| العنصر | القيمة |
|--------|--------|
| **اللون الأساسي** | `#1B5E20` أخضر إسلامي غامق |
| **اللون الثانوي** | `#C9A84C` ذهبي |
| **لون الخلفية** | `#0F172A` أزرق داكن (Dark Mode) |
| **لون النص** | `#f8fafc` أبيض فاتح |
| **خط العناوين** | Amiri (Google Fonts) |
| **خط القرآن** | Noto Naskh Arabic |
| **خط الواجهة** | Tajawal |
| **اتجاه الصفحة** | RTL (dir="rtl") |
| **التأثيرات** | Glassmorphism + Gradients + Micro-animations |

---

## 🔧 أوامر التنفيذ بالترتيب

```bash
# المرحلة 1: التثبيت
composer require laravel/breeze --dev
php artisan breeze:install blade
composer require filament/filament:"^3.3" -W
php artisan filament:install --panels
npm install && npm run build

# المرحلة 2: قاعدة البيانات
php artisan migrate:fresh
php artisan db:seed
php artisan quran:sync

# المرحلة 3-8: التطوير (كل ملف يُنشأ يدوياً)
# سيتم تنفيذها بالتسلسل مرحلة بعد مرحلة
```

---

## ⚠️ نقاط تحتاج قرار المستخدم

> [!IMPORTANT]
> ### 1. مفاتيح API
> هل لديك مفتاح Gemini API أو OpenAI API Key جاهز؟ يمكنني جعل الخدمة تعمل بـ Mock Data مؤقتاً.

> [!IMPORTANT]
> ### 2. التخزين المحلي vs S3
> الكود الحالي يستخدم S3 لتخزين الملفات الصوتية. هل تريد التخزين المحلي (أسهل للتطوير) أم S3؟

> [!WARNING]
> ### 3. حجم بيانات القرآن
> أمر `quran:sync` سيجلب 6,236 آية. هذا يتطلب اتصال إنترنت ويستغرق 2-5 دقائق.

> [!NOTE]
> ### 4. ترتيب التنفيذ
> أقترح تنفيذ المراحل **بالتسلسل** (1 → 2 → 3 → ... → 8) لأن كل مرحلة تعتمد على السابقة. هل توافق على هذا الترتيب؟

---

## ✅ خطة التحقق والاختبار

### اختبارات تلقائية
```bash
php artisan test                    # اختبارات PHP
php artisan migrate:fresh --seed    # التأكد من عمل الـ Seeders
php artisan quran:sync              # التأكد من جلب بيانات القرآن
```

### اختبارات يدوية
1. تسجيل حساب جديد → تأكد من عمل Breeze
2. تسجيل دخول كـ Admin → تأكد من لوحة Filament على `/admin`
3. تصفح السور → فتح سورة → عرض الآيات
4. بدء حفظ آية → ظهورها في المراجعة
5. تسجيل صوت → إرسال → عرض النتيجة
6. فتح اختبار → إجابة سؤال → عرض الصحيح/الخطأ

---

> **📌 ملاحظة:** هذه الخطة شاملة وتغطي جميع المتطلبات من ملف `prompt.md`. سأنتظر موافقتك قبل البدء بالتنفيذ مرحلة بمرحلة.

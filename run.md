# دليل التشغيل الكامل - المنصة الذكية لحفظ القرآن الكريم

## المتطلبات الأساسية

| المتطلب | الإصدار المطلوب |
|---------|-----------------|
| PHP | 8.2+ |
| Composer | 2.x |
| Node.js | 18+ |
| NPM | 9+ |
| MySQL | 5.7+ أو 8.x |
| إضافة PHP: `php-mysql`, `php-mbstring`, `php-xml`, `php-curl`, `php-fileinfo` |

---

## 1. إعداد المشروع الأولي

```bash
# استنساخ المشروع
git clone <repo-url> smart-quran-app
cd smart-quran-app

# تثبيت حزم PHP
composer install

# تثبيت حزم Node.js
npm install
```

## 2. إعداد ملف البيئة (.env)

```bash
cp .env.example .env
php artisan key:generate
```

افتح `.env` وعدّل القيم التالية:

```env
APP_NAME="المنصة الذكية لحفظ القرآن الكريم"
APP_URL=http://127.0.0.1:8000
APP_LOCALE=ar
APP_FALLBACK_LOCALE=ar

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smart_qurann_app
DB_USERNAME=root
DB_PASSWORD=your_password

QUEUE_CONNECTION=database
CACHE_STORE=database
SESSION_DRIVER=database
FILESYSTEM_DISK=public

# إعدادات الذكاء الاصطناعي (مطلوبة للتسميع والاختبارات الذكية)
AI_API_URL=https://api.abdalgani.com/openai/deployments/gemini-3-flash-preview/chat/completions
AI_API_KEY=your_api_key
AI_MODEL=gemini-3-flash-preview
```

## 3. إنشاء قاعدة البيانات

```sql
CREATE DATABASE smart_qurann_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

## 4. تشغيل الهجرات والبذور (Migrations & Seeders)

```bash
# تشغيل الهجرات لإنشاء الجداول
php artisan migrate

# تشغيل البذور (الأدوار + المدير + الطلاب + بيانات تجريبية)
php artisan db:seed
```

**بيانات الدخول الافتراضية:**

| الدور | البريد الإلكتروني | كلمة المرور |
|-------|-------------------|-------------|
| مدير | admin@smartquran.com | `password` |
| طالب | ahmed@example.com | `password` |
| طالب | fatima@example.com | `password` |
| طالب | omar@example.com | `password` |
| طالب | maryam@example.com | `password` |
| طالب | yousuf@example.com | `password` |

## 5. جلب بيانات القرآن الكريم (السور والآيات)

هذه الخطوة **إلزامية** لتشغيل كل ميزات المنصة:

```bash
# جلب 114 سورة + 6236 آية (النص العثماني + الإملائي + صوت العفاسي)
# يستغرق حوالي 5-10 دقائق حسب سرعة الإنترنت
php artisan quran:sync
```

**ماذا يفحص هذا الأمر:**
- يجلب بيانات السور الـ114 من `api.alquran.cloud`
- يصحح تصنيف السور (مكية/مدنية)
- يجلب النص العثماني لكل آية
- يجلب النص الإملائي (بدون تشكيل) للمقارنة
- يجلب روابط الصوت من القارئ مشاري العفاسي

## 6. جلب بيانات التجويد (نصوص ملونة)

```bash
# جلب نصوص التجويد الملونة لكل الآيات
# يستغرق حوالي 3-5 دقائق
php artisan quran:sync-tajweed
```

**ماذا يفعل:** يجلب نص كل آية مع رموز التجويد الملونة من `quran-tajweed` edition ويحفظها في حقل `text_tajweed`.

## 7. تصحيح تصنيف السور (اختياري)

```bash
php artisan quran:fix-classification
```

يُشغّل تلقائياً مع `quran:sync` لكن يمكنك تشغيله منفصلاً إذا لزم الأمر.

## 8. بناء الواجهة الأمامية

```bash
# بناء ملفات CSS/JS للإنتاج
npm run build

# أو بناء مظهر Filament المخصص
npm run build:filament
```

## 9. ربط مجلد التخزين (Storage)

```bash
# إنشاء رابط رمزي من public/storage إلى storage/app/public
php artisan storage:link
```

مطلوب لعرض ملفات الصوت المسجلة من التسميع.

## 10. تشغيل المشروع

### الطريقة 1: تشغيل كامل (موصى بها)

```bash
composer dev
```

هذا يشغّل 3 عمليات معاً:
1. **خادم Laravel** على `http://127.0.0.1:8000`
2. **معالج الطوابير** (Queue Worker) لمعالجة المهام في الخلفية
3. **Vite Dev Server** للـ hot-reload أثناء التطوير

### الطريقة 2: تشغيل منفصل (4 Terminal)

```bash
# Terminal 1 - خادم Laravel
php artisan serve

# Terminal 2 - معالج الطوابير (مهم للإشعارات والمهام)
php artisan queue:listen --tries=1

# Terminal 3 - المجدول (يشغل الأوامر المجدولة تلقائياً كل دقيقة)
php artisan schedule:work

# Terminal 4 - Vite (اختياري أثناء التطوير)
npm run dev
```

---

## 11. جدولة المهام والإشعارات (Scheduler)

المنصة تستخدم جدولة Laravel لإرسال إشعارات المراجعة والإنجازات تلقائياً.

### تشغيل المجدول بالخلفية بشكل دائم

#### على Windows (التطوير):

**الطريقة الأفضل:** استخدم `composer dev` — يشغّل الخادم + الطوابير + المجدول + Vite معاً.

**أو يدوياً في Terminal منفصلة:**
```bash
# schedule:work يعمل كبديل لـ cron على Windows — يفحص الجدولة كل دقيقة
php artisan schedule:work
```

**أو كخدمة Windows دائمة (Task Scheduler):**
1. افتح **Task Scheduler** من قائمة Start
2. **Create Task** → الاسم: `QuranApp Scheduler`
3. **Triggers** → **New** → **Repeat task every: 1 minute** → **for a duration of: Indefinitely**
4. **Actions** → **New** → **Start a program**:
   - Program: `C:\path\to\php.exe`
   - Arguments: `artisan schedule:run`
   - Start in: `D:\Tecjno-Injaz\smart-quran-app`
5. **Conditions** → ألغِ "Start only if computer is on AC power"
6. **Settings** → فعّل "Run task as soon as possible after a scheduled start is missed"

#### على Linux/macOS (الإنتاج):

أضف هذا الـ Cron:
```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

### تشغيل معالج الطوابير بالخلفية بشكل دائم

#### على Windows (التطوير):
```bash
# في Terminal منفصلة — يعالج الإشعارات والمهام المؤجلة
php artisan queue:listen --tries=1
```

**أو كخدمة Windows دائمة (Task Scheduler):**
- نفس الخطوات أعلاه لكن:
  - Arguments: `artisan queue:listen --tries=1 --timeout=60`

#### على Linux/macOS (الإنتاج) مع Supervisor:
```bash
sudo apt install supervisor
```

أنشئ ملف `/etc/supervisor/conf.d/quran-worker.conf`:
```ini
[program:quran-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path-to-project/artisan queue:listen --tries=1
autostart=true
autorestart=true
numprocs=2
redirect_stderr=true
stdout_logfile=/path-to-project/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start quran-worker:*
```

### أو شغّل يدوياً للاختبار:
```bash
# تشغيل المجدول مرة واحدة
php artisan schedule:run

# إرسال إشعارات المراجعة يدوياً
php artisan notifications:review-reminders
```

### الأوامر المجدولة:
- `notifications:review-reminders` — يُشغّل كل ساعة لإرسال:
  - إشعارات تذكير المراجعة (ReviewReminderNotification)
  - إشعارات مراجعة الآية (AyahReviewNotification)
  - إشعارات الإنجازات (AchievementNotification) — عند حفظ 1, 10, 50, 100, 200, 500, 1000 آية
  - إشعارات التتابع (StreakNotification) — عند 3, 7, 14, 30, 60, 90, 180, 365 يوم

---

## ملخص ميزات المشروع وكيفية عملها

### 1. عرض القرآن الكريم (`/quran`)
- يعرض قائمة السور مع معلوماتها (مكية/مدنية، عدد الآيات)
- عرض آيات كل سورة بالنص العثماني مع روابط الصوت
- **يعمل بعد:** `php artisan quran:sync`

### 2. التجويد الملون (`/quran/{surah}` و `/quran/tajweed-guide`)
- عرض الآيات بألوان التجويد المختلفة (مد، إدغام، إخفاء، قلقلة، غنة...)
- دليل تفصيلي لكل قاعدة تجويد مع الشرح والأمثلة
- **يعمل بعد:** `php artisan quran:sync-tajweed`

### 3. التسميع والتقييم (`/recitation/{ayah}` و `/recitation/surah/{surah}`)
- المستخدم يسجل صوته عبر المتصفح (Web Audio API)
- الصوت يُرسل للذكاء الاصطناعي للتفريغ النصي (Speech-to-Text)
- النص المفرّغ يُقارن مع النص المرجعي (TextMatchingService)
- يُحسب: نسبة التشابه، عدد الأخطاء، كلمة بكلمة (محاذاة DP)
- **يعمل بعد:** إعداد `AI_API_URL` و `AI_API_KEY` في `.env`
- **بدون AI:** يستخدم نص تجريبي وهمي (`بسم الله الرحمن الرحيم`)

### 4. الحفظ والتكرار المتباعد (`/hifz`)
- المستخدم يحفظ الآية ويسجل صوته
- SpacedRepetitionService يطبق خوارزمية SM-2:
  - الجولة 1: مراجعة بعد يوم
  - الجولة 2: مراجعة بعد 6 أيام
  - الجولة 3+: الفترة × عامل السهولة (يبدأ من 2.5)
  - إذا النسبة < 70%: إعادة من البداية
  - إذا النسبة ≥ 95%: تُعتبر "محفوظة"
- **يعمل تلقائياً** مع بيانات التسميع

### 5. الاختبارات (`/quiz/{surah}`)
- **إكمال الآية:** أكمل الآية الناقصة
- **اختيار من متعدد (MCQ):** أسئلة متنوعة بمستويات:
  - سهل: إكمال كلمة ناقصة
  - متوسط: الآية التالية/السابقة، معاني المفردات
  - صعب: خواتيم الآيات، المتشابهات اللفظية
- **مع AI:** أسئلة ذكية مولّدة بالذكاء الاصطناعي
- **بدون AI:** أسئلة محلية من بيانات القرآن نفسها

### 6. المراجعة والجدولة (`/reviews` و `/reviews/schedule`)
- عرض الآيات التي حان موعد مراجعتها
- عرض جدول المراجعات القادمة
- يعتمد على `next_review_date` من SpacedRepetitionService

### 7. الإشعارات (`/notifications`)
- إشعارات مراجعة الآيات المستحقة
- إشعارات الإنجازات (عدد الآيات المحفوظة)
- إشعارات التتابع (streak)
- **يتطلب:** `php artisan queue:listen` + جدولة `notifications:review-reminders`

### 8. لوحة تحكم المدير (`/admin`)
- لوحة Filament لإدارة:
  - المستخدمين وبياناتهم
  - السور والآيات
  - محاولات التسميع
- **بيانات الدخول:** `admin@smartquran.com` / `password`
- **صلاحية:** فقط المستخدمون بدور `admin`

### 9. الملف الشخصي (`/user-profile`)
- تعديل الاسم، البريد، كلمة المرور
- معلومات إضافية: السيرة، البلد، المنطقة الزمنية

---

## أوامر Artisan المخصصة

| الأمر | الوصف |
|-------|-------|
| `php artisan quran:sync` | جلب السور والآيات من API |
| `php artisan quran:sync-tajweed` | جلب نصوص التجويد الملونة |
| `php artisan quran:fix-classification` | تصحيح تصنيف السور (مكية/مدنية) |
| `php artisan notifications:review-reminders` | إرسال إشعارات المراجعة والإنجازات |
| `php artisan schedule:run` | تشغيل المجدول يدوياً |

---

## خريطة الروابط

| الرابط | الصفحة |
|--------|--------|
| `/` | الصفحة الرئيسية |
| `/register` | إنشاء حساب |
| `/login` | تسجيل الدخول |
| `/dashboard` | لوحة الطالب |
| `/quran` | قائمة السور |
| `/quran/{surah}` | عرض آيات سورة |
| `/quran/tajweed-guide` | دليل التجويد |
| `/recitation/{ayah}` | تسميع آية |
| `/recitation/surah/{surah}` | تسميع سورة كاملة |
| `/hifz` | قائمة الحفظ |
| `/hifz/{ayah}` | حفظ وتسميع آية |
| `/quiz/{surah}` | اختبار سورة |
| `/quiz/{surah}/complete` | إكمال الآية |
| `/quiz/{surah}/mcq` | اختيار من متعدد |
| `/reviews` | المراجعات المستحقة |
| `/reviews/schedule` | جدول المراجعات |
| `/notifications` | الإشعارات |
| `/user-profile` | الملف الشخصي |
| `/admin` | لوحة تحكم المدير (Filament) |

---

## استكشاف الأخطاء

### خطأ في الاتصال بقاعدة البيانات
```
SQLSTATE[HY000] [1049] Unknown database 'smart_qurann_app'
```
**الحل:** تأكد من إنشاء قاعدة البيانات: `CREATE DATABASE smart_qurann_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;`

### خطأ في جلب بيانات القرآن
```
فشل في جلب بيانات السور من API
```
**الحل:** تأكد من اتصال الإنترنت وأن `api.alquran.cloud` متاح. أعد المحاولة.

### الإشعارات لا تُرسل
**الحل:** تأكد من تشغيل معالج الطوابير: `php artisan queue:listen --tries=1` والمجدول.

### التسميع لا يعمل (الصوت لا يُفرّغ)
**الحل:** تأكد من إعداد `AI_API_URL` و `AI_API_KEY` في `.env`. بدونها سيعمل بنص وهمي.

### خطأ الصلاحيات في storage
```
Unable to write to storage/logs
```
**الحل (Linux):** `chmod -R 775 storage bootstrap/cache`

### لوحة Filament لا تفتح
**الحل:** تأكد من بناء ملفات Filament: `npm run build:filament` وأن المستخدم لديه دور `admin`.

---

## ملخص سريع للتشغيل من الصفر

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
# عدّل .env ببيانات قاعدة البيانات
php artisan migrate
php artisan db:seed
php artisan quran:sync          # 5-10 دقائق - يجلب كل السور والآيات
php artisan quran:sync-tajweed  # 3-5 دقائق - يجلب نصوص التجويد
php artisan storage:link
npm run build
php artisan serve               # أو: composer dev
```

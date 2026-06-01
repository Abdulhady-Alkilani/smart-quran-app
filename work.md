# الدليل الشامل لتشغيل مشروع المنصة الذكية لحفظ القرآن الكريم (Smart Quran App)

يحتوي هذا الملف على جميع التعليمات اللازمة لتجهيز وتشغيل المشروع من الصفر بعد سحبه من مستودع Github.

---

## 1. المتطلبات الأساسية (Prerequisites)
قبل البدء، تأكد من تثبيت البرامج التالية على جهازك:
* **PHP** (الإصدار 8.2 أو أحدث)
* **Composer** (لإدارة حزم PHP)
* **Node.js & npm** (لإدارة حزم الواجهة الأمامية)
* **MySQL** أو أي قاعدة بيانات مدعومة من Laravel.
* **Git** (لإدارة النسخ)

---

## 2. خطوات التثبيت والتشغيل الأساسية (Installation Steps)

### أ. سحب المشروع من GitHub
قم بفتح موجّه الأوامر (Terminal/CMD) ونفذ الأمر التالي لسحب المشروع، ثم ادخل إلى المجلد:
```bash
git clone <رابط_المستودع_هنا>
cd smart-quran-app
```

### ب. تثبيت الاعتمادات (Dependencies)
تثبيت حزم Laravel (الخلفية):
```bash
composer install
```
تثبيت حزم Node.js (الواجهة الأمامية):
```bash
npm install
```

### ج. إعداد ملف البيئة (.env)
قم بنسخ ملف الإعدادات الافتراضي لإنشاء ملف البيئة الخاص بك:
```bash
cp .env.example .env
```
*(ملاحظة: في الويندوز استخدم `copy .env.example .env`)*

بعد ذلك، قم بإنشاء مفتاح التشفير الخاص بالتطبيق:
```bash
php artisan key:generate
```

### د. إعداد قاعدة البيانات (Database Configuration)
قم بفتح ملف `.env` الذي قمت بإنشائه، وقم بتعديل بيانات الاتصال بقاعدة البيانات لتتطابق مع جهازك:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=اسم_قاعدة_البيانات
DB_USERNAME=اسم_المستخدم
DB_PASSWORD=كلمة_المرور
```
*(تأكد من إنشاء قاعدة البيانات بنفس الاسم في MySQL قبل الخطوة التالية)*

### هـ. تهيئة قاعدة البيانات وإنشاء الجداول (Migrations & Seeding)
قم بتشغيل أوامر التهيئة لإنشاء الجداول وإدخال البيانات الأساسية (إن وجدت):
```bash
php artisan migrate --seed
```

### و. جلب بيانات القرآن الكريم والتجويد (Sync Quran Data)
هذا المشروع يحتوي على أوامر مخصصة لجلب السور والآيات وبيانات التجويد من الـ API إلى قاعدة البيانات المحلية.
قم بتشغيل هذه الأوامر بالترتيب:

جلب السور والآيات الأساسية:
```bash
php artisan quran:sync
```

جلب نصوص التجويد الملونة:
```bash
php artisan quran:sync-tajweed
```

### ز. ربط مجلد التخزين (Storage Link)
للسماح بعرض الملفات المرفوعة (مثل الصور أو التسجيلات الصوتية) للمستخدمين:
```bash
php artisan storage:link
```

---

## 3. الإعدادات الخاصة بهذا المشروع (AI & Services)

يعتمد هذا المشروع بشكل أساسي على خدمات الذكاء الاصطناعي (مثل تقييم التلاوة والاختبارات). تأكد من إضافة إعدادات الـ API في ملف `.env`:

```env
# إعدادات الذكاء الاصطناعي (LiteLLM / Gemini / OpenAI)
AI_API_URL=https://api.abdalgani.com/openai/deployments/gemini-3-flash-preview/chat/completions
AI_API_KEY=your_api_key_here
AI_MODEL=gemini-3-flash-preview
```
> **ملاحظة هامة:** تأكد من أن مفتاح الـ API صالح ولديه رصيد كافٍ (لتجنب أخطاء 403 Permission Denied و Lightning dunning decision).

---

## 4. تشغيل المشروع (Running the Application)

لتشغيل المشروع بالكامل، ستحتاج إلى فتح نافذتين (Terminals) في مجلد المشروع:

**النافذة الأولى (لتشغيل خادم Laravel):**
```bash
php artisan serve
```
*(يمكنك تشغيله على بورت محدد هكذا: `php artisan serve --port=8001`)*

**النافذة الثانية (لتشغيل تجميع الواجهات الأمامية Vite/Tailwind):**
```bash
npm run dev
```

الآن يمكنك الوصول إلى المشروع عبر المتصفح على الرابط:  
[http://localhost:8000](http://localhost:8000) (أو البورت الذي حددته)

---

## 5. أوامر صيانة هامة (Troubleshooting & Maintenance)

إذا واجهتك مشاكل في التنسيقات أو بعد سحب تحديثات جديدة من Git، استخدم هذه الأوامر لتنظيف الذاكرة المخبأة (Cache):

```bash
# مسح ذاكرة التخزين المؤقت بالكامل
php artisan optimize:clear

# مسح كاش الواجهات (Views) فقط
php artisan view:clear

# مسح كاش الإعدادات فقط
php artisan config:clear
```

إذا قمت بتغييرات كبيرة على قواعد البيانات وتريد إعادة بنائها من الصفر (احذر، سيمسح جميع بياناتك الحالية):
```bash
php artisan migrate:fresh --seed
```

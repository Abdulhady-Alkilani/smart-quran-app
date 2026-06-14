# تقرير شامل عن مشروع المنصة الذكية لحفظ القرآن الكريم

---

## 1. نظرة عامة على المشروع

| البيان | التفاصيل |
|--------|----------|
| **اسم المشروع** | المنصة الذكية لحفظ القرآن الكريم (Smart Quran App) |
| **الإطار المستخدم** | Laravel 12 مع PHP 8.2+ |
| **لوحة التحكم** | Filament 3.3 |
| **قاعدة البيانات** | MySQL (`smart_qurann_app`) |
| **الذكاء الاصطناعي** | Gemini 3 Flash Preview عبر proxy مخصص (`api.abdalgani.com`) |
| **الواجهة الأمامية** | Blade Templates + Tailwind CSS 4 + Alpine.js + Vite 7 |
| **اللغة الافتراضية** | العربية (مع دعم ثنائي اللغة عربي/إنجليزي) |
| **نظام المصادقة** | Laravel Breeze |
| **خوارزمية المراجعة** | التكرار المتباعد (Spaced Repetition - SM-2) |
| **مزود بيانات القرآن** | alquran.cloud API |

---

## 2. البنية العامة للمشروع (المجلدات الجذرية)

```
smart-quran-app/
├── app/                    # الكود الأساسي للتطبيق (المنطق البرمجي)
├── bootstrap/              # ملفات الإقلاع والتهيئة
├── config/                 # ملفات الإعداد (11 ملف)
├── database/               # التهجيرات، الـ Seeders، والـ Factories
├── lang/                   # ملفات الترجمة (ar/en)
├── node_modules/           # حزم Node.js
├── public/                 # الملفات العامة (CSS, JS المُجمّع، index.php)
├── resources/              # واجهات Blade، CSS، JavaScript
├── routes/                 # تعريفات المسارات (web, auth, console)
├── storage/                # التخزين (الملفات المرفوعة، السجلات)
├── tests/                  # الاختبارات
├── vendor/                 # حزم Composer
├── composer.json           # إدارة حزم PHP
├── package.json            # إدارة حزم Node.js
├── .env                    # متغيرات البيئة
├── vite.config.js          # إعدادات بناء الواجهة
├── tailwind.config.js      # إعدادات Tailwind CSS
└── postcss.config.js       # إعدادات PostCSS
```

---

## 3. تفصيل مجلد `app/` (قلب المشروع)

مجلد `app/` يحتوي على 9 مجلدات فرعية:

```
app/
├── Console/                # أوامر Artisan المخصصة (4 أوامر)
├── Filament/               # لوحة تحكم المديرين (Filament)
├── Http/                   # المتحكمات، الوسائط، والطلبات
├── Listeners/              # مستمعو الأحداث (1 مستمع)
├── Models/                 # نماذج Eloquent (9 نماذج)
├── Notifications/          # إشعارات النظام (4 إشعارات)
├── Providers/              # مزودو الخدمات (2 مزود)
├── Services/               # الخدمات والمنطق البرمجي (6 خدمات)
└── View/                   # مكونات العرض (2 مكون)
```

---

### 3.1 النماذج (`app/Models/`) — 9 نماذج

#### 3.1.1 `User.php` (76 سطر)

**الوظيفة:** نموذج المستخدم الأساسي، يطبق واجهة `FilamentUser` للسماح بالوصول للوحة تحكم Filament.

**الحقول القابلة للتعبئة (fillable):**
- `name` — اسم المستخدم
- `email` — البريد الإلكتروني
- `password` — كلمة المرور (مشفّرة تلقائياً عبر cast `hashed`)

**الحقول المخفية:**
- `password`, `remember_token`

**التحويلات (casts):**
- `email_verified_at` → `datetime`
- `password` → `hashed` (تشفير تلقائي)

**العلاقات:**
- `roles()` — ارتباط Many-to-Many مع `Role` (جدول وسيط `role_user`)
- `profile()` — ارتباط One-to-One مع `Profile`
- `memorizationProgress()` — ارتباط One-to-Many مع `UserMemorizationProgress`
- `recitationAttempts()` — ارتباط One-to-Many مع `RecitationAttempt`
- `quizAttempts()` — ارتباط One-to-Many مع `UserQuizAttempt`

**الدوال:**
- `canAccessPanel(Panel $panel): bool` — تتحقق من أن المستخدم يمتلك دور `admin` للوصول للوحة Filament

---

#### 3.1.2 `Surah.php` (16 سطر)

**الوظيفة:** نموذج سورة القرآن الكريم.

**الحقول القابلة للتعبئة:**
- `number` — رقم السورة (1-114)
- `name_ar` — الاسم بالعربية
- `name_en` — الاسم بالإنجليزية
- `revelation_type` — نوع الوحي (`Meccan` أو `Medinan`)
- `total_ayahs` — عدد آيات السورة

**العلاقات:**
- `ayahs()` — ارتباط One-to-Many مع `Ayah`
- `generatedQuestions()` — ارتباط One-to-Many مع `GeneratedQuestion`

---

#### 3.1.3 `Ayah.php` (20 سطر)

**الوظيفة:** نموذج آية القرآن الكريم.

**الحقول القابلة للتعبئة:**
- `surah_id` — معرف السورة (مفتاح أجنبي)
- `number_in_surah` — رقم الآية داخل السورة
- `number_in_quran` — رقم الآية في المصحف (1-6236)
- `text_uthmani` — النص العثماني المشكّل (النص الأصلي مع التشكيل)
- `text_imlaei` — النص الإملائي البسيط (بدون تشكيل، يُستخدم للمقارنة في التسميع)
- `text_tajweed` — نص التجويد الملون (يحتوي رموزاً خاصة لتلوين أحكام التجويد)
- `audio_url` — رابط ملف الصوت (تلاوة العفاسي)

**العلاقات:**
- `surah()` — ارتباط BelongsTo مع `Surah`
- `memorizationProgress()` — ارتباط One-to-Many مع `UserMemorizationProgress`
- `recitationAttempts()` — ارتباط One-to-Many مع `RecitationAttempt`

---

#### 3.1.4 `RecitationAttempt.php` (24 سطر)

**الوظيفة:** نموذج محاولة تسميع (تسجيل صوتي لمقارنته بالآية).

**الحقول القابلة للتعبئة:**
- `user_id` — معرف المستخدم
- `ayah_id` — معرف الآية
- `audio_file_path` — مسار ملف الصوت المُسجَّل
- `transcribed_text` — النص المُفرَّغ من الصوت (نتيجة Speech-to-Text)
- `similarity_score` — نسبة التشابه مع النص المرجعي (مثلاً 92.50)
- `mistakes_count` — عدد الأخطاء
- `is_passed` — هل نجح التسميع (true إذا similarity ≥ 90%)

**التحويلات (casts):**
- `is_passed` → `boolean`
- `similarity_score` → `decimal:2`

**العلاقات:**
- `user()` — ارتباط BelongsTo مع `User`
- `ayah()` — ارتباط BelongsTo مع `Ayah`

---

#### 3.1.5 `UserMemorizationProgress.php` (28 سطر)

**الوظيفة:** نموذج تقدم حفظ المستخدم لآية معينة — يخزّن بيانات خوارزمية التكرار المتباعد SM-2.

> **ملاحظة:** تم تحديد اسم الجدول يدوياً `protected $table = 'user_memorization_progress'` لأن الاسم لا يتبع صيغة الجمع الافتراضية في Laravel.

**الحقول القابلة للتعبئة:**
- `user_id` — معرف المستخدم
- `ayah_id` — معرف الآية
- `status` — حالة الحفظ (`memorized` أو `learning`)
- `repetition_count` — عدد مرات التكرار (0, 1, 2, ...)
- `easiness_factor` — معامل السهولة (يبدأ من 2.5، الحد الأدنى 1.3)
- `interval_days` — الفاصل الزمني بالأيام حتى المراجعة التالية
- `last_review_date` — تاريخ آخر مراجعة
- `next_review_date` — تاريخ المراجعة التالية المُجدوَلة

**التحويلات (casts):**
- `last_review_date` → `date`
- `next_review_date` → `date`
- `easiness_factor` → `decimal:4`

**العلاقات:**
- `user()` — ارتباط BelongsTo مع `User`
- `ayah()` — ارتباط BelongsTo مع `Ayah`

---

#### 3.1.6 `UserQuizAttempt.php` (20 سطر)

**الوظيفة:** محاولة إجابة المستخدم على سؤال اختبار.

**الحقول القابلة للتعبئة:**
- `user_id` — معرف المستخدم
- `question_id` — معرف السؤال (مفتاح أجنبي لـ `GeneratedQuestion`)
- `user_answer` — إجابة المستخدم
- `is_correct` — هل الإجابة صحيحة

**التحويلات (casts):**
- `is_correct` → `boolean`

**العلاقات:**
- `user()` — ارتباط BelongsTo مع `User`
- `question()` — ارتباط BelongsTo مع `GeneratedQuestion` عبر `question_id`

---

#### 3.1.7 `GeneratedQuestion.php` (24 سطر)

**الوظيفة:** سؤال اختبار مُولَّد بالذكاء الاصطناعي.

**الحقول القابلة للتعبئة:**
- `surah_id` — معرف السورة
- `ayah_id` — معرف الآية
- `question_text` — نص السؤال
- `options` — الخيارات (يُخزَّن كـ JSON، يُحوَّل تلقائياً لمصفوفة عبر cast `array`)
- `correct_answer` — الإجابة الصحيحة

**العلاقات:**
- `surah()` — ارتباط BelongsTo مع `Surah`
- `ayah()` — ارتباط BelongsTo مع `Ayah`
- `quizAttempts()` — ارتباط One-to-Many مع `UserQuizAttempt` عبر `question_id`

---

#### 3.1.8 `Profile.php` (16 سطر)

**الوظيفة:** الملف الشخصي للمستخدم (معلومات إضافية).

**الحقول القابلة للتعبئة:**
- `user_id` — معرف المستخدم
- `avatar` — صورة المستخدم
- `bio` — نبذة تعريفية
- `phone` — رقم الهاتف
- `country` — البلد
- `timezone` — المنطقة الزمنية
- `preferences` — تفضيلات المستخدم (JSON يُحوَّل تلقائياً لمصفوفة)

**العلاقات:**
- `user()` — ارتباط BelongsTo مع `User`

---

#### 3.1.9 `Role.php` (12 سطر)

**الوظيفة:** نموذج الدور (admin, student, إلخ).

**الحقول القابلة للتعبئة:**
- `name` — اسم الدور
- `description` — وصف الدور

**العلاقات:**
- `users()` — ارتباط Many-to-Many مع `User` (جدول وسيط `role_user`)

---

### 3.2 الخدمات (`app/Services/`) — 6 خدمات

#### 3.2.1 `SpeechToTextService.php` (143 سطر)

**الوظيفة:** تحويل الصوت المسجّل إلى نص عربي عبر الذكاء الاصطناعي (Gemini).

**الدالة الرئيسية: `transcribe(string $audioPath, string $language = 'ar'): ?string`**
- تتحقق من توفر إعدادات API (`ai.api_url`, `ai.api_key`)
- إذا لم تتوفر، تعيد نصاً تجريبياً عبر `mockTranscription()` → `"بسم الله الرحمن الرحيم"`
- إذا توفرت، تستدعي `transcribeViaLiteLLM()`

**الدالة `transcribeViaLiteLLM()`:**
1. تتحقق من وجود ملف الصوت
2. تشفّر الملف بـ base64
3. تحدد نوع MIME عبر `resolveMimeType()`
4. تزيد وقت التنفيذ إلى 120 ثانية (لتجنب Timeout)
5. ترسل طلب POST إلى API مع:
   - **نظام (system):** "أنت نظام تفريغ صوتي حرفي. مهمتك الوحيدة هي كتابة ما تسمعه بالضبط. ممنوع عليك تصحيح أي خطأ أو تعديل أي كلمة."
   - **مستخدم (user):** تعليمات صارمة: اكتب بالإملاء البسيط، لا تصحح أي خطأ نطق، لا تكمل أي جملة ناقصة، لا تضف أي كلمة لم تُنطق
   - **الصوت:** مرسل كـ `image_url` مع data URI (base64)
   - **درجة الحرارة:** 0.0 (أقصى دقة)
   - **الحد الأقصى:** 4096 tokens
6. تستخرج النص من `choices.0.message.content`
7. تنظّف النص عبر `cleanTranscription()`

**دالة التنظيف `cleanTranscription()`:**
- تزيل التشكيل (الحركات، الشدة، السكون...)
- توحّد الألف: `ٱ`, `إ`, `أ`, `آ` → `ا`
- تزيل أي أحرف غير عربية وغير مسافات
- تنظف المسافات الزائدة

**دالة تحديد MIME `resolveMimeType()`:**
- دعم الصيغ: weba, webm, ogg, oga, m4a, mp3, wav, flac, aac
- تصحح الكشف الخاطئ: `video/webm` → `audio/webm`

---

#### 3.2.2 `TextMatchingService.php` (247 سطر)

**الوظيفة:** مقارنة النص المُفرَّغ صوتياً مع النص المرجعي للآية وحساب نسبة التطابق والأخطاء.

**الدالة الرئيسية: `match(string $transcribed, string $reference): array`**

**المعالجة:**
1. **تطبيع النص** `normalize()`:
   - إزالة علامات HTML
   - إزالة التشكيل
   - توحيد الألف: `ٱ/إ/أ/آ` → `ا`
   - توحيد التاء المربوطة: `ة` → `ه`
   - توحيد الألف المقصورة: `ى` → `ي`
   - إزالة الأرقام العربية واللاتينية
   - إزالة الأحرف غير العربية
   - تنظيف المسافات

2. **مقارنة كلمة بكلمة** `compareWords()`:
   - تستخدم خوارزمية **البرمجة الديناميكية (Dynamic Programming)** لمحاذاة الكلمات (Word Alignment)
   - تبني مصفوفة DP بحجم (n+1) × (m+1)
   - تحسب تكلفة الاستبدال: 0 إذا تطابقتا، 0.5 إذا كانتا قريبتين (≥65%)، 1 إذا مختلفتين
   - **التتبع العكسي (Backtrack)** لتحديد نوع كل كلمة:
     - `correct` — الكلمة صحيحة (تطابقتا تماماً)
     - `partial` — الكلمة قريبة (تشابه ≥ 65%)
     - `wrong` — الكلمة خاطئة
     - `missing` — كلمة ناقصة في النص المُتَرْجَم
     - `extra` — كلمة زائدة في النص المُتَرْجَم

3. **حساب نسبة الكلمات** `calculateWordScore()`:
   - `correct`: وزن 1، قيمة 1
   - `partial`: وزن 1، قيمة 0.7
   - `wrong`: وزن 1، قيمة 0
   - `missing`: وزن 1، قيمة 0
   - `extra`: وزن 0.3، قيمة 0

4. **حساب التشابه النصي** `calculateTextSimilarity()`:
   - يستخدم `similar_text()` كمعيار ثانوي

5. **النسبة النهائية:**
   - **80%** من تحليل الكلمات + **20%** من التشابه النصي

6. **عدد الأخطاء** `countMistakes()`:
   - يعد `wrong`, `missing`, `extra` كأخطاء كاملة
   - يعد `partial` كخطأ واحد

7. **علامة النجاح:** `similarity_score >= 90`

**النتيجة المُعادة:**
```php
[
    'similarity_score' => 92.50,
    'mistakes_count' => 2,
    'is_passed' => true,
    'transcribed_normalized' => '...',
    'reference_normalized' => '...',
    'word_diff' => [
        ['status' => 'correct', 'expected' => 'بسم', 'got' => 'بسم'],
        ['status' => 'wrong', 'expected' => 'الرحمن', 'got' => 'الرحيم'],
        // ...
    ],
]
```

**دالة `wordSimilarity()`:**
- تحسب تشابه كلمتين بأكثر من طريقة وتأخذ الأفضل:
  - `similar_text()` — تشابه عام
  - `levenshtein()` — المسافة التحريرية (آمنة لأن الكلمات العربية قصيرة < 255 حرف)
- تعيد القيمة الأعلى

---

#### 3.2.3 `SpacedRepetitionService.php` (64 سطر)

**الوظيفة:** تنفيذ خوارزمية **SM-2** (SuperMemo 2) للتكرار المتباعد — تحدد متى يجب مراجعة الآية التالية.

**الدالة الرئيسية: `calculateNextReview(UserMemorizationProgress $progress, float $similarityScore): array`**

**خطوات الخوارزمية:**

1. **تحويل النسبة إلى جودة (quality 0-5):**
   | النسبة | الجودة | الوصف |
   |--------|--------|-------|
   | ≥ 95% | 5 | ممتاز |
   | ≥ 90% | 4 | جيد جداً |
   | ≥ 80% | 3 | جيد |
   | ≥ 70% | 2 | مقبول |
   | ≥ 50% | 1 | ضعيف |
   | < 50% | 0 | فاشل |

2. **حساب معامل السهولة الجديد (easiness factor):**
   ```
   newFactor = max(1.3, currentFactor + (0.1 - (5-quality) * (0.08 + (5-quality) * 0.02)))
   ```
   - الحد الأدنى: 1.3 (لن ينخفض أكثر من ذلك)
   - إذا كانت الجودة 5: newFactor = currentFactor + 0.1 (يزيد)
   - إذا كانت الجودة أقل: ينخفض المعامل

3. **حساب الفاصل الزمني وعدد التكرارات:**

   | الشرط | الفاصل (أيام) | التكرارات |
   |--------|---------------|-----------|
   | quality < 3 (فاشل) | 1 | يُصفَّر إلى 0 |
   | التكرار 0 (أول مرة) | 1 | 1 |
   | التكرار 1 (ثاني مرة) | 6 | 2 |
   | التكرار 2+ | `round(interval × newFactor)` | تكرار + 1 |

4. **تحديد الحالة:**
   - `similarity >= 95%` → `memorized`
   - أقل من ذلك → `learning`

**النتيجة المُعادة:**
```php
[
    'repetition_count' => 2,
    'easiness_factor' => 2.6000,
    'interval_days' => 15,
    'last_review_date' => '2026-06-10',
    'next_review_date' => '2026-06-25',
    'status' => 'memorized',
]
```

---

#### 3.2.4 `McqQuizService.php` (418 سطر)

**الوظيفة:** توليد اختبارات اختيار من متعدد (MCQ) متنوعة ومتدرجة الصعوبة.

**الدالة الرئيسية: `generate(Surah $surah): array`**
- أولاً يحاول التوليد عبر الذكاء الاصطناعي `generateViaAI()`
- إذا فشل أو أقل من 3 أسئلة، يستخدم التوليد المحلي `generateLocal()`

**التوليد عبر AI `generateViaAI()`:**
- يختار 8 آيات عشوائية من السورة
- يبني prompt مفصّل يطلب 5 أسئلة متنوعة:
  - [سهل - إكمال الكلمة]: آية ينقصها كلمة واحدة
  - [متوسط - الآية التالية]: ما الآية التي تلي هذه الآية
  - [متوسط - معاني المفردات]: معنى كلمة غريبة
  - [صعب - خواتيم الآيات]: بداية آية مع طلب الخاتمة
  - [صعب - المتشابهات اللفظية]: في أي سورة توجد هذه الآية
  - [تحدي - الآية السابقة]: ما الآية التي تسبق هذه الآية
- يطلب الإجابة بصيغة JSON فقط
- يرسل الطلب إلى API بدرجة حرارة 0.7
- ينبّه القيود: يمنع سؤال رقم الآية أو عدد الآيات

**التوليد المحلي `generateLocal()` — 5 أنواع أسئلة:**

1. **`generateCompleteWord()`** — إكمال الكلمة الناقصة (سهل)
   - يختار آية عشوائية
   - يخفي كلمة واحدة ويستبدلها بـ `______`
   - يولّد 4 خيارات: الكلمة الصحيحة + 3 كلمات خاطئة من آيات أخرى

2. **`generateNextAyah()`** — الآية التالية (متوسط)
   - يختار آية عشوائية (ليست الأخيرة)
   - الإجابة: الآية التي تليها
   - الخيارات الخاطئة: آيات من سور أخرى

3. **`generatePreviousAyah()`** — الآية السابقة (تحدي)
   - يختار آية عشوائية (ليست الأولى)
   - الإجابة: الآية التي تسبقها
   - الخيارات الخاطئة: آيات من سور أخرى

4. **`generateAyahEndings()`** — خواتيم الآيات (صعب)
   - يختار آية ويقطع آخر 30% من كلماتها
   - الإجابة: الجزء المقطوع
   - الخيارات: نهايات آيات أخرى من نفس السورة

5. **`generateSimilarVerses()` — المتشابهات اللفظية (صعب)
   - يختار آية ويسأل "في أي سورة توجد هذه الآية؟"
   - الإجابة: اسم السورة الصحيحة
   - الخيارات: 3 سور أخرى عشوائية

6. **`generateVocabulary()`** — معاني المفردات (متوسط، fallback)
   - سؤال بسيط عن معنى كلمة في آية
   - يُفعّل فقط عند عدم وجود أسئلة كافية

---

#### 3.2.5 `QuizGeneratorService.php` (103 سطر)

**الوظيفة:** توليد سؤال اختبار فردي لآية محددة بالذكاء الاصطناعي.

**الدالة الرئيسية: `generateForAyah(Ayah $ayah): ?GeneratedQuestion`**
- يبني prompt يحتوي: نص الآية، اسم السورة، رقم الآية
- يطلب سؤال JSON مع 4 خيارات
- أنواع الأسئلة الممكنة: تحديد السورة، الآية التالية، معنى كلمة، ترتيب الآية
- يرسل إلى API بدرجة حرارة 0.8
- ينبّه الرد من markdown code blocks
- يحفظ السؤال في جدول `generated_questions` ويعيد النموذج
- إذا فشل، يولّد سؤالاً محلياً بسيطاً عبر `generateMockQuestion()`

**السؤال المحلي `generateMockQuestion()`:**
- سؤال بسيط: "ما هي الآية التالية: [نص]؟"
- 4 خيارات تتعلق برقم الآية واسم السورة

---

#### 3.2.6 `TajweedService.php` (319 سطر)

**الوظيفة:** خدمة أحكام التجويد — تحتوي على **16 قاعدة تجويد** كاملة بالعربي والإنجليزي.

**بنية كل قاعدة:**
```php
'identifier' => [
    'class' => 'css_class',           // فئة CSS للتلوين
    'type' => 'rule-type',            // نوع القاعدة
    'color' => '#HEX',                // لون التمييز
    'desc_ar' => '...',               // وصف عربي
    'desc_en' => '...',               // وصف إنجليزي
    'category_ar' => '...',           // فئة عربية
    'category_en' => '...',           // فئة إنجليزية
    'explanation_ar' => '...',        // شرح مفصل عربي
    'explanation_en' => '...',        // شرح مفصل إنجليزي
    'example_ar' => '...',            // مثال عربي
    'example_en' => '...',            // مثال إنجليزي
    'how_to_ar' => '...',             // طريقة التطبيق عربي
    'how_to_en' => '...',             // طريقة التطبيق إنجليزي
]
```

**القواعد الـ 16:**

| الرمز | القاعدة | اللون | الفئة |
|-------|---------|-------|-------|
| `h` | همزة الوصل | `#AAAAAA` | همزات |
| `s` | حرف ساكن (صامت) | `#AAAAAA` | أحكام عامة |
| `l` | لام التعريف الشمسية | `#AAAAAA` | أحكام اللام |
| `n` | المد الطبيعي (2 حركات) | `#537FFF` | أحكام المد |
| `p` | المد الجائز (2/4/6 حركات) | `#4050FF` | أحكام المد |
| `m` | المد اللازم (6 حركات) | `#000EBC` | أحكام المد |
| `o` | المد الواجب المتصل (4-5 حركات) | `#2144C1` | أحكام المد |
| `q` | القلقلة | `#DD0008` | أحكام الوقف |
| `c` | الإخفاء الشفوي | `#D500B7` | أحكام الميم الساكنة |
| `f` | الإخفاء الحقيقي | `#9400A8` | أحكام النون الساكنة والتنوين |
| `w` | الإدغام الشفوي | `#58B800` | أحكام الميم الساكنة |
| `i` | الإقلاب | `#26BFFD` | أحكام النون الساكنة والتنوين |
| `a` | الإدغام بغنة | `#169777` | أحكام النون الساكنة والتنوين |
| `u` | الإدغام بلا غنة | `#169200` | أحكام النون الساكنة والتنوين |
| `d` | الإدغام المتماثلين | `#A1A1A1` | أنواع الإدغام |
| `b` | الإدغام المتقاربين | `#A1A1A1` | أنواع الإدغام |
| `g` | الغنة (2 حركات) | `#FF7E1E` | أحكام عامة |

**الدوال:**
- `parse(string $text): string` — تحوّل النص المشفّر `[h:...[...]]` إلى وسوم HTML:
  ```html
  <tajweed class="ham_wasl" data-type="hamza-wasl" data-desc="همزة الوصل">ٱلْحَمْدُ</tajweed>
  ```
  - تدعم اللغة الحالية للتطبيق (ar/en) في وصف القاعدة

- `getRules(): array` — تعيد كل القواعد كمصفوفة

- `getRulesByCategory(): array` — تجمّع القواعد حسب الفئة:
  - همزات
  - أحكام عامة
  - أحكام اللام
  - أحكام المد
  - أحكام الوقف
  - أحكام الميم الساكنة
  - أحكام النون الساكنة والتنوين
  - أنواع الإدغام

---

### 3.3 المتحكمات (`app/Http/Controllers/`)

#### 3.3.1 المتحكمات الرئيسية

##### `Controller.php` (8 أسطر)
المتحكم الأساسي المجرد (abstract) الذي يرثه جميع المتحكمات.

##### `LocaleController.php` (23 سطر)
**الوظيفة:** تبديل لغة التطبيق.
- `switch(Request $request, string $locale)` — يتحقق أن اللغة ar أو en، يخزنها في الجلسة، يعيّنها في التطبيق، يعيد التوجيه للصفحة السابقة.

##### `ProfileController.php` (60 سطر) — Breeze
**الوظيفة:** الملف الشخصي الافتراضي من Laravel Breeze.
- `edit()` — عرض نموذج تعديل الملف الشخصي
- `update(ProfileUpdateRequest)` — تحديث الاسم/البريد، يزيل تأكيد البريد إذا تغير
- `destroy()` — حذف الحساب بعد تأكيد كلمة المرور

---

#### 3.3.2 متحكمات المستخدم (`Http/Controllers/User/`)

##### `DashboardController.php` (108 سطر)

**الوظيفة:** لوحة تحكم الطالب الرئيسية — تعرض إحصائيات شاملة ورسوماً بيانية.

**الدالة: `index(Request $request)`**

**البيانات المحسوبة:**
1. **عدد الآيات المحفوظة** — `memorizationProgress` حيث `status = 'memorized'`
2. **عدد الآيات قيد التعلم** — `memorizationProgress` حيث `status = 'learning'`
3. **الآيات المستحقة للمراجعة** — حيث `next_review_date <= today`
4. **آخر 10 محاولات تسميع** — مع تحميل علاقات `ayah.surah`

**الرسوم البيانية:**
- **خطي (Line Chart):** نشاط الحفظ اليومي لآخر 30 يوماً (عدد محاولات التسميع في كل يوم)
- **دائري (Doughnut Chart):** توزيع حالات الحفظ (محفوظ / قيد التعلم / جديد من 6236)
- **أعمدة (Bar Chart):** درجات آخر 10 محاولات تسميع (أخضر إذا نجح، أحمر إذا فشل)

**إحصائيات إضافية:**
- نسبة نجاح التسميع: `passedAttempts / totalAttempts × 100`
- نسبة الاختبارات: `correctQuiz / totalQuiz × 100`
- عدد السور المبدوء بحفظها
- عدد محاولات التسميع الكلي

**حماية:** يمنع المدير من الوصول ويحوّله تلقائياً إلى `/admin`.

---

##### `QuranController.php` (54 سطر)

**الوظيفة:** استعراض القرآن الكريم.

**الدوال:**
- `index()` — يعرض قائمة بكل السور (114 سورة)
- `show($surah)` — يعرض آيات سورة محددة مع:
  - حالة حفظ المستخدم لكل آية (محفوظ/قيد التعلم/جديد)
  - قواعد التجويد للتلوين
- `startMemorizing(Ayah $ayah)` — يضيف آية لورد المستخدم اليومي (`firstOrCreate` بحالة `learning`)

---

##### `RecitationController.php` (203 سطر)

**الوظيفة:** تسميع الآيات والسور — الواجهة الأساسية لتسجيل الصوت ومقارنته.

**التبعيات (Dependency Injection):**
- `SpeechToTextService` — تحويل الصوت لنص
- `TextMatchingService` — مقارنة النصوص
- `SpacedRepetitionService` — حساب المراجعة التالية

**تسميع آية مفردة:**
- `create(Ayah $ayah)` — عرض واجهة التسميع
- `store(Request $request, Ayah $ayah)` — معالجة التسميع:
  1. التحقق من وجود ملف الصوت
  2. تخزين الملف في `storage/app/public/recitations/`
  3. إنشاء سجل `RecitationAttempt`
  4. استدعاء `speechService->transcribe()` لتحويل الصوت لنص
  5. استدعاء `textMatching->match()` للمقارنة مع `text_imlaei`
  6. تحديث سجل المحاولة بالنتيجة
  7. إذا نجح (≥90%): تحديث `UserMemorizationProgress` عبر خوارزمية SRS
  8. إعادة النتيجة كـ JSON (للاستخدام عبر AJAX)

**تسميع سورة كاملة:**
- `createSurah($surah)` — عرض واجهة تسميع سورة كاملة
- `storeSurah(Request $request, $surah)` — معالجة تسميع سورة كاملة:
  1. نفس خطوات تسميع الآية المفردة
  2. يدمج نصوص كل الآيات كنص واحد للمقارنة
  3. يُنشئ محاولة مرتبطة بأول آية
  4. إذا نجح: يحدّث تقدم الحفظ لجميع آيات السورة

---

##### `HifzController.php` (166 سطر)

**الوظيفة:** واجهة الحفظ والمراجعة — تعرض الآيات المستحقة للمراجعة مع إمكانية التسميع.

**التبعيات:** نفس RecitationController

**الدوال:**
- `index(Request $request)` — تعرض:
  - الآيات المستحقة للمراجعة (مرتبة بالأقدم أولاً)
  - عدد المتأخرات (قبل اليوم)
  - عدد المستحقات اليوم
  - عدد المستحقات هذا الأسبوع (7 أيام قادمة)
  - فلتر حسب السورة
  - رابط لأول آية مستحقة
- `recite(Ayah $ayah)` — عرض واجهة تسميع آية مفردة
- `submit(Request $request, Ayah $ayah)` — معالجة التسميع (نفس منطق RecitationController)

---

##### `QuizController.php` (200 سطر)

**الوظيفة:** اختبارات حفظ القرآن بأنواع متعددة.

**الدوال:**
- `show(Surah $surah)` — اختبار عادي محلي:
  - سؤال 1: هل السورة مكية أم مدنية؟
  - سؤال 2: كم عدد آيات السورة؟
  - 3 أسئلة إضافية عشوائية (الآية التالية أو إكمال الآية)

- `mcqQuiz(Surah $surah)` — اختبار تحدي MCQ:
  - يستخدم `McqQuizService` لتوليد أسئلة متنوعة
  - يدعم التوليد عبر AI أو محلياً

- `completeAyah(Surah $surah)` — اختبار إكمال الآية:
  - يختار 5 آيات عشوائية
  - يعرض النصف الأول ويطلب إكمال النصف الثاني
  - 4 خيارات لكل سؤال (1 صحيح + 3 من آيات أخرى في نفس السورة)

- `submit(Request $request, GeneratedQuestion $question)` — حفظ إجابة المستخدم

---

##### `ReviewController.php` (133 سطر)

**الوظيفة:** إدارة المراجعات المجدولة.

**الدوال:**
- `index(Request $request)` — تعرض:
  - الآيات المستحقة للمراجعة
  - عدد المتأخرات / اليوم / الأسبوع القادم
  - فلتر حسب السورة
  - رابط مباشر لأول آية مستحقة

- `schedule(Request $request)` — جدول المراجعات التفصيلي:
  - يقسم الآيات إلى 5 مجموعات زمنية:
    - متأخر (قبل اليوم)
    - اليوم
    - هذا الأسبوع (1-7 أيام)
    - هذا الشهر (8-30 يوم)
    - لاحق (أكثر من 30 يوم)
  - إحصائيات: عدد المحفوظ / قيد التعلم / الكلي
  - متوسط معامل السهولة والفاصل الزمني
  - الحمل اليومي لـ 14 يوماً قادماً (عدد الآيات المستحقة كل يوم)
  - فلتر حسب السورة

---

##### `TajweedGuideController.php` (18 سطر)

**الوظيفة:** عرض دليل أحكام التجويد.
- `index()` — يجلب القواعد مقسمة حسب الفئة وجميع القواعد ويعرضها في واجهة تفصيلية

---

##### `NotificationController.php` (99 سطر)

**الوظيفة:** إدارة إشعارات المستخدم.

**الدوال:**
- `index()` — عرض صفحة الإشعارات (20 في الصفحة)
- `markAsRead($id)` — تحديد إشعار كمقروء (يعيد JSON)
- `readAndRedirect($id)` — يقرأ الإشعار ويوجّه لرابط الإجراء
- `markAllAsRead()` — تحديد كل الإشعارات كمقروءة
- `getUnreadCount()` — عدد الإشعارات غير المقروءة (API)
- `getLatest()` — آخر 10 إشعارات غير مقروءة مع دعم ثنائي اللغة
- `destroy($id)` — حذف إشعار

---

##### `ProfileController.php` (User) (38 سطر)

**الوظيفة:** تعديل الملف الشخصي الخاص بالمستخدم.
- `edit()` — عرض النموذج (يُنشئ بروفايل تلقائياً إذا لم يكن موجوداً)
- `update()` — تحديث الاسم، الهاتف، البلد، النبذة

---

#### 3.3.3 متحكمات المصادقة (`Http/Controllers/Auth/`)

مجموعة متحكمات Laravel Breeze القياسية:

| المتحكم | الوظيفة |
|---------|---------|
| `AuthenticatedSessionController` | تسجيل الدخول (عرض النموذج + المعالجة) والخروج |
| `RegisteredUserController` | تسجيل حساب جديد |
| `PasswordResetLinkController` | طلب رابط إعادة تعيين كلمة المرور |
| `NewPasswordController` | إعادة تعيين كلمة المرور |
| `EmailVerificationPromptController` | عرض مطالبة تأكيد البريد |
| `VerifyEmailController` | معالجة تأكيد البريد عبر الرابط الموقّع |
| `EmailVerificationNotificationController` | إرسال إشعار تأكيد البريد |
| `ConfirmablePasswordController` | تأكيد كلمة المرور قبل العمليات الحساسة |
| `PasswordController` | تغيير كلمة المرور |

---

### 3.4 الوسائط (`app/Http/Middleware/`)

#### `SetLocale.php` (25 سطر)

**الوظيفة:** وسيطة تحدد لغة التطبيق من الجلسة.
- تقرأ اللغة من `Session::get('locale')` مع افتراضي `ar`
- تدعم `ar` و `en` فقط
- تعيّن اللغة عبر `App::setLocale()`
- تُطبق على كل الطلبات

---

### 3.5 طلبات النموذج (`app/Http/Requests/`)

#### `ProfileUpdateRequest.php` (31 سطر)
- `name`: مطلوب، نص، أقصى 255 حرف
- `email`: مطلوب، نص، أحرف صغيرة، بريد صالح، أقصى 255، فريد (مع تجاهل المستخدم الحالي)

#### `Auth/LoginRequest.php` (86 سطر)
- `email`: مطلوب، نص، بريد صالح
- `password`: مطلوب، نص
- `authenticate()`: يحاول تسجيل الدخول مع Rate Limiting (5 محاولات ثم قفل لمدة متغيرة)
- `throttleKey()`: مفتاح Rate Limiting = بريد صغير + IP

---

### 3.6 لوحة تحكم Filament (`app/Filament/`)

#### 3.6.1 الموارد (`Resources/`)

##### `UserResource.php` (141 سطر)
**الوظيفة:** إدارة المستخدمين في لوحة التحكم.
- **الأيقونة:** `heroicon-o-users`
- **النموذج (Form):** الاسم، البريد، كلمة المرور (مشروطة: مطلوبة عند الإنشاء فقط)، الأدوار (اختيار متعدد)
- **الجدول (Table):** الاسم (قابل للبحث)، البريد (قابل للبحث)، الدور (شارة ملونة: admin=خطر، student=نجاح)، عدد الآيات المحفوظة (شارة)، تاريخ التسجيل
- **الفلترة:** حسب الدور (اختيار متعدد)
- **الإجراءات:** عرض، تعديل، حذف
- **العرض التفصيلي (Infolist):** الاسم، البريد، الدور، تاريخ التسجيل
- **Relation Managers:** `MemorizationProgressRelationManager` — عرض تقدم الحفظ لكل مستخدم

##### `SurahResource.php` (156 سطر)
**الوظيفة:** عرض السور (بدون إنشاء أو تعديل).
- **الأيقونة:** `heroicon-o-book-open`
- **تعطيل الإنشاء:** `canCreate() = false`
- **النموذج:** جميع الحقول معطلة (disabled)
- **الجدول:** الرقم، الاسم عربي/إنجليزي (قابل للبحث)، نوع الوحي (شارة: مكي=تحذير/مدني=معلومات)، عدد الآيات، عدد الآيات الفعلي
- **الفلترة:** حسب نوع الوحي
- **الإجراءات:** عرض فقط
- **Relation Managers:** `AyahsRelationManager` — عرض آيات السورة

##### `RecitationAttemptResource.php` (137 سطر)
**الوظيفة:** عرض محاولات التسميع (بدون إنشاء).
- **الأيقونة:** `heroicon-o-microphone`
- **تعطيل الإنشاء:** `canCreate() = false`
- **الجدول:** اسم المستخدم، اسم السورة، رقم الآية، نسبة التشابه% (ملونة: ≥90%=أخضر، ≥70%=أصفر، أقل=أحمر)، نجح/فشل (أيقونة)، عدد الأخطاء (شارة)، التاريخ، مشغّل صوت مخصص
- **الفلترة:** حسب الحالة (نجح/فشل)
- **الترتيب الافتراضي:** الأحدث أولاً
- **العرض التفصيلي:** كل البيانات + النص المُفرَّغ (عرض كامل العمود)
- **مشغّل الصوت:** يستخدم عرض Blade مخصص `filament.columns.audio-player`

---

#### 3.6.2 الويدجت (`Widgets/`)

##### `StatsOverviewWidget.php` (31 سطر)
3 بطاقات إحصائية:
1. عدد المستخدمين (أخضر)
2. عدد الآيات المحفوظة (من 6236) (أزرق)
3. محاولات التسميع اليوم (أصفر)

##### `LatestRecitationsChart.php` (45 سطر)
رسم بياني خطي لمحاولات التسميع آخر 7 أيام:
- اللون: أخضر داكن `#1B5E20`
- الارتفاع الأقصى: 300px
- نوع الرسم: خط (line)

---

#### 3.6.3 إعدادات لوحة التحكم (`AdminPanelProvider.php` — 81 سطر)

**الإعدادات:**
- المعرف: `admin`
- المسار: `/admin`
- اسم العلامة التجارية: من الترجمة `messages.app_name`
- الخط: `Tajawal`
- SPA مفعل
- اللون الأساسي: أخضر داكن `#1B5E20`
- وضع السمة الافتراضي: فاتح
- سمة مخصصة: `css/filament/admin/theme.css`
- عنصر قائمة المستخدم: "العودة للمنصة" → رابط `dashboard`
- الوسائط: تشفير الكوكيز، الجلسات، CSRF، الروابط...
- مصادقة Filament مطلوبة

---

### 3.7 أوامر Artisan (`app/Console/Commands/`) — 4 أوامر

#### `SyncQuranDataCommand.php` (174 سطر)
**الأمر:** `quran:sync`
**الوظيفة:** جلب بيانات القرآن الكريم من API خارجي `alquran.cloud`.

**الخطوات:**
1. **`syncSurahs()`**:
   - يجلب 114 سورة من `http://api.alquran.cloud/v1/surah`
   - يُنشئ أو يحدّث كل سورة في قاعدة البيانات
   - يعرض شريط تقدم

2. **`fixClassification()`**:
   - يصحّح تصنيف مكي/مدني حسب القائمة المعتمدة (26 سورة مدنية)
   - الأرقام المدنية: 2,3,4,5,8,9,22,24,33,47,48,49,57,58,59,60,61,62,63,64,65,66,76,98,99,110

3. **`syncAyahs()`**:
   - يجلب النص العثماني من `quran-uthmani` edition
   - يجلب النص الإملائي من `ar.asad` edition
   - يجلب صوت العفاسي من `ar.alafasy` edition
   - يزيل التشكيل عبر `stripDiacritics()` للحصول على نص إملائي نظيف
   - يُنشئ أو يحدّث كل آية (6236 آية)
   - يعرض شريط تقدم شامل

**دالة `stripDiacritics()`:**
- تزيل التشكيل والعلامات الصوتية
- توحّد الألف: `ٱ/إ/أ/آ` → `ا`
- تنظف المسافات الزائدة

---

#### `FixSurahClassificationCommand.php` (52 سطر)
**الأمر:** `quran:fix-classification`
**الوظيفة:** تصحيح تصنيف السور بين مكية ومدنية حسب القائمة المعتمدة.
- يعرض تفصيل كل تعديل (الاسم، التصنيف القديم → الجديد)
- يعرض شريط تقدم وعدد السور المعدلة

---

#### `SyncTajweedData.php` (62 سطر)
**الأمر:** `quran:sync-tajweed`
**الوظيفة:** جلب نصوص التجويد الملونة من API.
- يجلب من `https://api.alquran.cloud/v1/quran/quran-tajweed`
- يحدّث حقل `text_tajweed` لكل آية
- يعرض شريط تقدم وعدد الآيات المحدّثة

---

#### `SendReviewReminders.php` (181 سطر)
**الأمر:** `notifications:review-reminders`
**الوظيفة:** إرسال إشعارات المراجعة والتذكيرات للمستخدمين.
**يُنفّذ كل ساعة** (مجدول في `console.php`).

**الخطوات:**
1. يجلب كل المستخدمين الذين لديهم تقدم حفظ
2. لكل مستخدم:
   - يجلب الآيات المستحقة للمراجعة
   - إذا وجد:
     - **ReviewReminderNotification:** تذكير عام (إذا لم يُرسل اليوم)
     - **AyahReviewNotification:** إشعار تفصيلي لكل آية مستحقة اليوم
   - يفحص سلسلة الأيام المتتالية `checkStreak()`
   - يفحص الإنجازات `checkAchievements()`

**`checkStreak()`:**
- يحسب عدد الأيام المتتالية التي كان فيها نشاط (تسميع أو مراجعة)
- عند المعلم (3, 7, 14, 30, 60, 90, 180, 365 يوم): يرسل `StreakNotification`
- يمنع إرسال نفس الإشعار مرتين

**`calculateStreak()`:**
- يبدأ من اليوم ويتراجع للخلف
- يفحص وجود محاولات تسميع أو مراجعات في كل يوم
- يتوقف عند أول يوم بدون نشاط

**`checkAchievements()`:**
- يفحص عدد الآيات المحفوظة (`status = 'memorized'`)
- عند المعلم (1, 10, 50, 100, 200, 500, 1000 آية): يرسل `AchievementNotification`
- الرسائل ثنائية اللغة
- يمنع إرسال نفس الإشعار مرتين

---

### 3.8 الإشعارات (`app/Notifications/`) — 4 إشعارات

جميع الإشعارات تُرسل عبر قناة `database` فقط وتدعم ثنائي اللغة.

#### `ReviewReminderNotification.php` (41 سطر)
- **البيانات:** نوع `review_reminder`، عدد المستحقات، عدد المتأخرات
- **الرسالة:** "لديك X آية مستحقة للمراجعة اليوم" أو "لديك X آية متأخرة وY آية مستحقة"
- **رابط الإجراء:** `reviews.index`

#### `AyahReviewNotification.php` (47 سطر)
- **البيانات:** نوع `ayah_review`، اسم السورة، رقم الآية، معرف الآية، عدد الباقي
- **الرسالة:** "حان وقت مراجعة سورة X آية Y" مع ذكر عدد الآيات الأخرى
- **رابط الإجراء:** `reviews.index`

#### `StreakNotification.php` (34 سطر)
- **البيانات:** نوع `streak`، عدد الأيام
- **الرسالة:** "أحسنت! واصلت المراجعة لمدة X يوم متتالي"
- **رابط الإجراء:** `reviews.index`

#### `AchievementNotification.php` (41 سطر)
- **البيانات:** نوع `achievement`، نوع الإنجاز، القيمة، رسالة عربية/إنجليزية
- **الرسالة:** "حفظت X آية! إنجاز رائع"
- **رابط الإجراء:** `dashboard`

---

### 3.9 المستمعون (`app/Listeners/`)

#### `HandleLocaleChanged.php` (15 سطر)
**الوظيفة:** يستمع لحدث `LocaleChanged` من إضافة Filament Language Switch.
- يحدّث لغة التطبيق: `App::setLocale($event->locale)`
- يخزن اللغة في الجلسة: `session()->put('locale', $event->locale)`
- يضمن توافق اللغة بين واجهة المستخدم ولوحة تحكم Filament

---

### 3.10 مزودو الخدمات (`app/Providers/`)

#### `AppServiceProvider.php` (36 سطر)
**الوظيفة:** مزود الخدمات الرئيسي.
- **`boot()`:**
  - يسجّل مستمع حدث تغيير اللغة: `LocaleChanged` → `HandleLocaleChanged`
  - يهيّئ Filament Language Switch:
    - اللغات المدعومة: ar, en
    - مرئي داخل وخارج لوحات Filament
    - التسميات: العربية / English
    - شكل دائري

#### `Filament/AdminPanelProvider.php` (81 سطر)
**الوظيفة:** يعرّف لوحة تحكم Filament بالكامل — مفصّل في قسم 3.6.3.

---

### 3.11 مكونات العرض (`app/View/Components/`)

#### `AppLayout.php` (17 سطر)
يعرض قالب `layouts.app` — القالب الأساسي للمستخدمين المسجلين.

#### `GuestLayout.php` (17 سطر)
يعرض قالب `layouts.guest` — قالب الزوار (تسجيل، دخول، إلخ).

---

## 4. المسارات (`routes/`)

### 4.1 `web.php` (77 سطر)

| المسار | المتحكم | الاسم | الوصف |
|--------|---------|-------|-------|
| `GET /locale/{locale}` | LocaleController@switch | locale.switch | تبديل اللغة |
| `GET /` | Closure | home | صفحة الترحيب |
| **محمية (auth + verified):** | | | |
| `GET /dashboard` | DashboardController@index | dashboard | لوحة تحكم الطالب |
| `GET /quran` | QuranController@index | quran.index | قائمة السور |
| `GET /quran/tajweed-guide` | TajweedGuideController@index | quran.tajweed-guide | دليل التجويد |
| `GET /quran/{surah}` | QuranController@show | quran.show | عرض سورة بالآيات |
| `POST /quran/{ayah}/start-memorizing` | QuranController@startMemorizing | quran.start | بدء حفظ آية |
| `GET /recitation/surah/{surah}` | RecitationController@createSurah | recitation.surah | تسميع سورة كاملة |
| `POST /recitation/surah/{surah}` | RecitationController@storeSurah | recitation.surah.store | معالجة تسميع سورة |
| `GET /recitation/{ayah}` | RecitationController@create | recitation.create | واجهة تسميع آية |
| `POST /recitation/{ayah}` | RecitationController@store | recitation.store | معالجة تسميع آية |
| `GET /reviews` | ReviewController@index | reviews.index | المراجعات المستحقة |
| `GET /reviews/schedule` | ReviewController@schedule | reviews.schedule | جدول المراجعات |
| `GET /hifz` | HifzController@index | hifz.index | واجهة الحفظ |
| `GET /hifz/{ayah}` | HifzController@recite | hifz.recite | تسميع آية من الحفظ |
| `POST /hifz/{ayah}` | HifzController@submit | hifz.submit | معالجة تسميع الحفظ |
| `GET /quiz/{surah}` | QuizController@show | quiz.show | اختبار سورة |
| `GET /quiz/{surah}/complete` | QuizController@completeAyah | quiz.complete | اختبار إكمال |
| `GET /quiz/{surah}/mcq` | QuizController@mcqQuiz | quiz.mcq | اختبار تحدي MCQ |
| `POST /quiz/{question}/submit` | QuizController@submit | quiz.submit | إرسال إجابة |
| `GET /notifications` | NotificationController@index | notifications.index | صفحة الإشعارات |
| `GET /notifications/latest` | NotificationController@getLatest | notifications.latest | آخر الإشعارات (API) |
| `GET /notifications/unread-count` | NotificationController@getUnreadCount | notifications.unread-count | عدد غير المقروءة |
| `POST /notifications/{id}/read` | NotificationController@markAsRead | notifications.read | تحديد كمقروء |
| `GET /notifications/{id}/go` | NotificationController@readAndRedirect | notifications.go | قراءة وتوجيه |
| `POST /notifications/read-all` | NotificationController@markAllAsRead | notifications.read-all | تحديد الكل كمقروء |
| `DELETE /notifications/{id}` | NotificationController@destroy | notifications.destroy | حذف إشعار |
| `GET /user-profile` | UserProfileController@edit | user.profile.edit | الملف الشخصي |
| `PUT /user-profile` | UserProfileController@update | user.profile.update | تحديث الملف الشخصي |
| **محمية (auth فقط):** | | | |
| `GET /profile` | ProfileController@edit | profile.edit | إعدادات Breeze |
| `PATCH /profile` | ProfileController@update | profile.update | تحديث Breeze |
| `DELETE /profile` | ProfileController@destroy | profile.destroy | حذف الحساب |

### 4.2 `auth.php` (59 سطر)
مسارات المصادقة من Laravel Breeze:
- `GET/POST /register` — تسجيل حساب
- `GET/POST /login` — تسجيل دخول
- `GET/POST /forgot-password` — طلب إعادة تعيين كلمة المرور
- `GET/POST /reset-password/{token}` — إعادة تعيين كلمة المرور
- `GET /verify-email` — مطالبة تأكيد البريد
- `GET /verify-email/{id}/{hash}` — تأكيد البريد (موقّع)
- `POST /email/verification-notification` — إعادة إرسال تأكيد البريد
- `GET/POST /confirm-password` — تأكيد كلمة المرور
- `PUT /password` — تغيير كلمة المرور
- `POST /logout` — تسجيل خروج

### 4.3 `console.php` (11 سطر)
- أمر `inspire` — عرض اقتباس ملهم
- **جدولة:** `notifications:review-reminders` يُنفّذ كل ساعة (`hourlyAt(0)`)

---

## 5. قاعدة البيانات (`database/`)

### 5.1 التهجيرات (Migrations) — 14 تهجيرة

| التهجيرة | الجدول | الوصف |
|----------|--------|-------|
| `0001_01_01_000000_create_users_table` | `users` | المستخدمين (name, email, password) |
| `0001_01_01_000001_create_cache_table` | `cache` | الكاش |
| `0001_01_01_000002_create_jobs_table` | `jobs` | طوابير المهام |
| `0000_04_01_144029_create_roles_table` | `roles` | الأدوار (name, description) |
| `2026_04_01_144029_create_profiles_table` | `profiles` | الملفات الشخصية (user_id, avatar, bio, phone, country, timezone, preferences) |
| `2026_04_01_144029_create_role_user_table` | `role_user` | جدول وسيط (user_id, role_id) |
| `2026_04_01_144030_create_surahs_table` | `surahs` | السور (number, name_ar, name_en, revelation_type, total_ayahs) |
| `2026_04_01_144031_create_ayahs_table` | `ayahs` | الآيات (surah_id, number_in_surah, number_in_quran, text_uthmani, text_imlaei, audio_url) |
| `2026_04_01_144031_create_recitation_attempts_table` | `recitation_attempts` | محاولات التسميع |
| `2026_04_01_144031_create_user_memorization_progress_table` | `user_memorization_progress` | تقدم الحفظ (SM-2) |
| `2026_04_01_144031_create_generated_questions_table` | `generated_questions` | الأسئلة المولّدة |
| `2026_04_01_144031_create_user_quiz_attempts_table` | `user_quiz_attempts` | محاولات الاختبار |
| `2026_05_17_110946_create_notifications_table` | `notifications` | الإشعارات (Laravel) |
| `2026_05_17_113536_add_text_tajweed_to_ayahs_table` | إضافة حقل | `text_tajweed` في جدول `ayahs` |

### 5.2 الـ Seeders

| الـ Seeder | الوظيفة |
|------------|---------|
| `DatabaseSeeder` | الـ Seeder الرئيسي (ينادي بقية الـ Seeders) |
| `AdminUserSeeder` | ينشئ حساب مدير افتراضي |
| `RoleSeeder` | ينشئ الأدوار (admin, student) |
| `StudentSeeder` | ينشئ حسابات طلاب تجريبية |
| `TestDataSeeder` | بيانات تجريبية |

### 5.3 الـ Factories

- `UserFactory` — مصنع بيانات المستخدمين للاختبارات

---

## 6. الواجهات (`resources/views/`)

```
views/
├── layouts/
│   ├── app.blade.php          # قالب التطبيق (للمسجلين)
│   ├── guest.blade.php        # قالب الزوار (للتسجيل والدخول)
│   └── navigation.blade.php   # شريط التنقل الرئيسي
│
├── user/
│   ├── dashboard.blade.php        # لوحة تحكم الطالب (إحصائيات + رسوم بيانية)
│   ├── quran/
│   │   ├── index.blade.php        # قائمة الـ 114 سورة
│   │   ├── show.blade.php         # عرض سورة بالآيات مع التجويد والحفظ
│   │   └── tajweed-guide.blade.php # دليل أحكام التجويد التفاعلي
│   ├── recitation/
│   │   ├── create.blade.php       # واجهة تسميع آية (تسجيل صوت)
│   │   └── surah-recitation.blade.php # واجهة تسميع سورة كاملة
│   ├── hifz/
│   │   ├── index.blade.php        # قائمة المراجعات المستحقة
│   │   └── recite.blade.php       # واجهة تسميع الحفظ
│   ├── quiz/
│   │   ├── show.blade.php         # اختبار عادي
│   │   ├── mcq.blade.php          # اختبار تحدي MCQ (متعدد الأنواع)
│   │   └── complete.blade.php    # اختبار إكمال الآية
│   ├── reviews/
│   │   ├── index.blade.php        # المراجعات المستحقة
│   │   └── schedule.blade.php     # جدول المراجعات التفصيلي
│   ├── notifications/
│   │   └── index.blade.php        # صفحة الإشعارات
│   └── profile/
│       └── edit.blade.php         # تعديل الملف الشخصي
│
├── auth/                           # صفحات المصادقة (Breeze)
│   ├── login.blade.php
│   ├── register.blade.php
│   ├── forgot-password.blade.php
│   ├── reset-password.blade.php
│   ├── verify-email.blade.php
│   └── confirm-password.blade.php
│
├── profile/                        # صفحات الملف الشخصي (Breeze)
│   ├── edit.blade.php
│   └── partials/
│       ├── update-profile-information-form.blade.php
│       ├── update-password-form.blade.php
│       └── delete-user-form.blade.php
│
├── components/                     # مكونات Blade قابلة لإعادة الاستخدام
│   ├── application-logo.blade.php
│   ├── nav-link.blade.php
│   ├── responsive-nav-link.blade.php
│   ├── primary-button.blade.php
│   ├── secondary-button.blade.php
│   ├── danger-button.blade.php
│   ├── input-label.blade.php
│   ├── text-input.blade.php
│   ├── input-error.blade.php
│   ├── auth-session-status.blade.php
│   ├── dropdown.blade.php
│   ├── dropdown-link.blade.php
│   └── modal.blade.php
│
├── filament/
│   └── columns/
│       └── audio-player.blade.php  # مشغّل صوت مخصص لجدول Filament
│
├── welcome.blade.php               # صفحة الترحيب (الرئيسية)
└── dashboard.blade.php              # لوحة تحكم Breeze الافتراضية
```

---

## 7. الإعدادات (`config/`)

### 7.1 `ai.php` — إعدادات الذكاء الاصطناعي
```php
'api_url'     => env('AI_API_URL'),          // رابط API
'api_key'     => env('AI_API_KEY'),          // مفتاح API
'model'       => env('AI_MODEL', 'gemini-3-flash-preview'),  // النموذج
'max_tokens'  => env('AI_MAX_TOKENS', 4096), // الحد الأقصى للـ tokens
'temperature' => env('AI_TEMPERATURE', 0.7), // درجة الحرارة
```

### 7.2 ملفات الإعداد الأخرى
- `app.php` — إعدادات التطبيق (الاسم، اللغة، timezone...)
- `auth.php` — إعدادات المصادقة (guards, providers, passwords...)
- `database.php` — إعدادات MySQL
- `cache.php` — إعدادات الكاش (database)
- `session.php` — إعدادات الجلسات (database, 120 دقيقة)
- `queue.php` — إعدادات الطوابير (database)
- `mail.php` — إعدادات البريد
- `logging.php` — إعدادات السجلات
- `filesystems.php` — إعدادات نظام الملفات (public)
- `services.php` — إعدادات خدمات الطرف الثالث

---

## 8. التدفق الأساسي للنظام

```
المستخدم يزور الموقع → صفحة الترحيب
    │
    ├── تسجيل حساب جديد → RegisteredUserController
    │       └── إنشاء مستخدم + بروفايل + دور "student"
    │
    └── تسجيل دخول → AuthenticatedSessionController
            │
            └── لوحة التحكم (DashboardController)
                    │
                    ├── استعراض القرآن (QuranController)
                    │       ├── قائمة السور
                    │       ├── عرض سورة بالآيات + التجويد
                    │       └── إضافة آية للحفظ
                    │
                    ├── تسميع آية/سورة (RecitationController / HifzController)
                    │       ├── تسجيل صوت (WebM/OGG/MP3...)
                    │       ├── SpeechToTextService → نص عربي
                    │       ├── TextMatchingService → نسبة + أخطاء + word_diff
                    │       ├── SpacedRepetitionService → تاريخ المراجعة التالية
                    │       └── تحديث UserMemorizationProgress
                    │
                    ├── المراجعات (ReviewController)
                    │       ├── الآيات المستحقة اليوم
                    │       └── جدول المراجعات (14 يوم)
                    │
                    ├── الاختبارات (QuizController)
                    │       ├── اختبار عادي (محلي)
                    │       ├── اختبار تحدي MCQ (AI + محلي)
                    │       └── اختبار إكمال الآية (محلي)
                    │
                    ├── دليل التجويد (TajweedGuideController)
                    │       └── 16 قاعدة بالشرح والأمثلة
                    │
                    ├── الإشعارات (NotificationController)
                    │       ├── تذكير مراجعة
                    │       ├── سلسلة أيام
                    │       └── إنجازات
                    │
                    └── الملف الشخصي (UserProfileController)
                            └── تعديل الاسم/الهاتف/البلد/النبذة
```

---

## 9. نظام الإشعارات المؤتمت

```
جدولة كل ساعة (console.php)
    │
    └── SendReviewReminders
            │
            ├── لكل مستخدم لديه تقدم حفظ:
            │       ├── مراجعات مستحقة → ReviewReminderNotification
            │       ├── آية مستحقة اليوم → AyahReviewNotification
            │       ├── فحص السلسلة (streak) → StreakNotification
            │       └── فحص الإنجازات → AchievementNotification
            │
            └── منع الإرسال المكرر:
                    ├── لا يرسل نفس نوع الإشعار في نفس اليوم
                    └── لا يرسل نفس القيمة (streak/achievement) مرتين
```

---

## 10. متغيرات البيئة (`.env`)

| المتغير | القيمة | الوصف |
|---------|--------|-------|
| `APP_NAME` | المنصة الذكية لحفظ القرآن الكريم | اسم التطبيق |
| `APP_LOCALE` | ar | اللغة الافتراضية |
| `DB_CONNECTION` | mysql | نوع قاعدة البيانات |
| `DB_DATABASE` | smart_qurann_app | اسم قاعدة البيانات |
| `SESSION_DRIVER` | database | تخزين الجلسات في قاعدة البيانات |
| `QUEUE_CONNECTION` | database | طوابير المهام في قاعدة البيانات |
| `CACHE_STORE` | database | الكاش في قاعدة البيانات |
| `AI_API_URL` | `https://api.abdalgani.com/openai/...` | رابط API الذكاء الاصطناعي |
| `AI_MODEL` | gemini-3-flash-preview | نموذج الذكاء الاصطناعي |

---

## 11. الحزم المستخدمة

### 11.1 Composer (PHP)

**الحزم الرئيسية:**
- `laravel/framework` ^12.0 — إطار العمل الأساسي
- `filament/filament` 3.3 — لوحة التحكم الإدارية
- `bezhansalleh/filament-language-switch` — تبديل اللغة في Filament
- `laravel/tinker` ^2.10.1 — REPL تفاعلي

**حزم التطوير:**
- `laravel/breeze` — نظام المصادقة
- `fakerphp/faker` — بيانات تجريبية
- `laravel/pail` — مراقبة السجلات
- `laravel/pint` — تنسيق الكود
- `laravel/sail` — بيئة Docker
- `mockery/mockery` — محاكاة الاختبارات
- `pestphp/pest` — إطار الاختبارات

### 11.2 NPM (JavaScript)

- `tailwindcss` ^4.3.0 — إطار CSS
- `@tailwindcss/forms` — تنسيق النماذج
- `@tailwindcss/typography` — تنسيق النصوص
- `alpinejs` ^3.4.2 — تفاعلية الواجهة
- `vite` ^7.0.7 — بناء الواجهة
- `laravel-vite-plugin` — تكامل Vite مع Laravel
- `axios` — طلبات HTTP
- `concurrently` — تشغيل عدة أوامر بالتوازي
- `postcss` + `postcss-nesting` — معالجة CSS

---

## 12. مخطط العلاقات بين النماذج (ERD)

```
User (1) ──────< (N) Role          [role_user pivot]
User (1) ──────  (1) Profile
User (1) ──────< (N) UserMemorizationProgress
User (1) ──────< (N) RecitationAttempt
User (1) ──────< (N) UserQuizAttempt

Surah (1) ─────< (N) Ayah
Surah (1) ─────< (N) GeneratedQuestion

Ayah (1) ──────< (N) UserMemorizationProgress
Ayah (1) ──────< (N) RecitationAttempt
Ayah (1) ──────  (1) Surah

GeneratedQuestion (1) ──< (N) UserQuizAttempt

UserMemorizationProgress → belongs to User, Ayah
RecitationAttempt → belongs to User, Ayah
UserQuizAttempt → belongs to User, GeneratedQuestion
```

---

## 13. ملخص التقنيات والخوارزميات

| التقنية | الاستخدام |
|---------|-----------|
| **SM-2 (Spaced Repetition)** | جدولة المراجعات بناءً على أداء المستخدم |
| **Dynamic Programming (Word Alignment)** | محاذاة الكلمات ومقارنتها في TextMatchingService |
| **similar_text + Levenshtein** | حساب تشابه الكلمات والنصوص |
| **Speech-to-Text (Gemini)** | تحويل صوت التسميع إلى نص |
| **MCQ Generation (AI)** | توليد أسئلة اختيار من متعدد ذكية |
| **Tajweed Parsing** | تحويل النص المشفّر إلى HTML ملوّن |
| **Rate Limiting** | حماية تسجيل الدخول (5 محاولات/دقيقة) |
| **Event Listener** | مزامنة اللغة بين الواجهة و Filament |
| **Task Scheduling** | إرسال الإشعارات كل ساعة |
| **Base64 Audio Encoding** | إرسال الصوت لـ API |

---

*تم إنشاء هذا التقرير في 10 يونيو 2026*
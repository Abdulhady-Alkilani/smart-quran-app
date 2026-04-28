# Project Master Documentation: Smart Quran Memorization & Tracking Platform

## 1. Project Overview

You are an expert Full-Stack Laravel Developer. Your task is to build a web application named "Smart Quran Memorization & Tracking Platform" (المنصة الذكية لحفظ القرآن الكريم ومتابعته).
The platform helps users memorize the Quran using Spaced Repetition System (SRS) and Artificial Intelligence for Speech-to-Text evaluation.

**Key Architecture Change:** The entire backend is built on **Laravel**. NO separate Python/FastAPI server. AI tasks (Speech-to-Text and Question Generation) will be handled via direct HTTP requests from Laravel to OpenAI APIs (Gemini or Whisper API & GPT-4 API) or similar AI services.

## 2. Tech Stack

- **Backend:** Laravel 12, PHP 8.2+
- **Database:** MySQL
- **Admin Panel:** FilamentPHP v3.3 -w (TALL stack admin)
- **User Frontend:** Blade Templates, Tailwind CSS, Alpine.js (for interactivity and Web Audio API handling).
- **Authentication:** Laravel Breeze (for users) + Filament Auth (for Admins).
- **External APIs:**
    - **Quran Data:** `api.alquran.cloud` (To fetch Surahs, Ayahs, Uthmani Text, Imlaei Text, and Reciter Audio URLs).
    - **AI Speech-to-Text:** Gemini or OpenAI Whisper API (or Groq Whisper API for faster responses).
    - **AI Text Generation:** Gemini or OpenAI GPT-4 API (for generating quiz questions).

## 3. Database Schema (Eloquent Models)

Create the following tables, models, and relationships:

- **Users:** `id`, `name`, `email`, `password`, `role` (enum: admin, student).
- **Profiles:** `user_id`, `avatar`, `phone`, `country`, `timezone`.
- **Surahs:** `id`, `number`, `name_ar`, `name_en`, `revelation_type`, `total_ayahs`.
- **Ayahs:** `id`, `surah_id`, `number_in_surah`, `number_in_quran`, `text_uthmani` (for display), `text_imlaei` (for AI matching), `audio_url`.
- **UserMemorizationProgress:** `user_id`, `ayah_id`, `status` (learning, memorized), `repetition_count`, `easiness_factor` (decimal), `interval_days`, `last_review_date`, `next_review_date`.
- **RecitationAttempts:** `user_id`, `ayah_id`, `audio_file_path`, `transcribed_text`, `similarity_score` (decimal), `mistakes_count`, `is_passed` (boolean).
- **GeneratedQuestions:** `surah_id`, `ayah_id`, `question_text`, `options` (JSON), `correct_answer`.
- **UserQuizAttempts:** `user_id`, `question_id`, `user_answer`, `is_correct` (boolean).

## 4. Execution Plan (A to Z)

_Agent: Please execute this plan step by step. Ask for my confirmation before moving to the next phase._

### Phase 1: Setup & Initialization

1.  Initialize a new Laravel project.
2.  Install **Laravel Breeze** (Blade stack) for basic user authentication.
3.  Install **FilamentPHP v3.3 -w** for the admin panel.
4.  Configure `.env` (Database, API Keys for Gemini or OpenAI).

### Phase 2: Database, Migrations & Seeders

1.  Generate all Models and Migrations based on the Schema above. Ensure correct Foreign Keys and Cascade Deletes.
2.  Create **Seeders**:
    - `RoleSeeder` & `AdminUserSeeder`: Create a default admin account.
    - `StudentSeeder`: Create dummy student accounts.
    - **CRITICAL:** Create a `QuranDataSeeder` or an Artisan Command (`php artisan quran:sync`). This command should fetch data from `http://api.alquran.cloud/v1/quran/quran-uthmani` and `http://api.alquran.cloud/v1/quran/ar.alafasy` (for audio) and populate the `surahs` and `ayahs` tables. DO NOT hit the external API on every page load; store it in the DB.

### Phase 3: Admin Panel (FilamentPHP)

Build the following Filament Resources and Widgets:

1.  **Dashboard Widgets:**
    - `StatsOverviewWidget`: Total Users, Total Ayahs Memorized, Total Recitation Attempts today.
    - `LatestRecitationsChart`: Line chart showing attempts over the last 7 days.
2.  **UserResource:** Manage users.
    - _Relation Manager:_ `MemorizationProgressRelationManager` (View what this specific user has memorized).
3.  **SurahResource:** View-only resource.
    - _Relation Manager:_ `AyahsRelationManager` (List all ayahs inside the surah).
4.  **RecitationAttemptResource:** View-only. Show user name, Ayah, similarity score, and an HTML `<audio>` player to listen to the attempt. Filter by `is_passed`.

### Phase 4: User Frontend (Blade + Tailwind CSS + Alpine.js)

Design modern, responsive, Islamic-themed UI for the student:

1.  **Dashboard (`user.dashboard`):** Show memorization stats, a list of "Ayahs due for review today", and recent activity.
2.  **Quran Browser (`quran.index` & `quran.show`):** Grid of Surahs. Clicking a Surah shows its Ayahs. Each Ayah has an `<audio>` button to play the official recitation, and a "Start Memorizing" button.
3.  **Spaced Repetition Review Page (`reviews.index`):** Fetch Ayahs from `UserMemorizationProgress` where `next_review_date <= today()`.

### Phase 5: Audio Recording & Uploading Logic (Alpine.js + Web Audio API)

1.  In the `recitation.create` view, use **Alpine.js** to manage the Web Audio API.
2.  **UI Elements:** "Start Recording" button, "Stop Recording" button, "Upload File" (for pre-recorded files), and "Submit" button.
3.  **JavaScript Logic (Alpine):**
    - Request `navigator.mediaDevices.getUserMedia({ audio: true })`.
    - Record using `MediaRecorder`.
    - Save chunks to a `Blob` (audio/webm or audio/wav).
    - Send the Blob via AJAX/Fetch API to a Laravel Controller endpoint.

### Phase 6: Core AI Logic (Laravel Controllers)

Implement `RecitationController@store`:

1.  Receive the audio file (recorded or uploaded). Store it locally.
2.  **Speech-to-Text:** Use Laravel for gemini or `Http::withToken(env('OPENAI_API_KEY'))` to send the audio file to `https://api.openai.com/v1/audio/transcriptions` (Whisper-1 model).
3.  **Matching Algorithm:** Compare the returned transcript with the Ayah's `text_imlaei`. Use PHP's built-in `levenshtein()` function to calculate the `similarity_score` percentage.
4.  If `similarity_score >= 90%`, mark as `is_passed = true`.

### Phase 7: Spaced Repetition System (SRS) Implementation

In the same controller, if the user passes the recitation, trigger the SRS logic (SuperMemo-2 algorithm inside PHP):

1.  Update `repetition_count += 1`.
2.  Calculate new `easiness_factor` based on similarity score (Quality 0-5).
3.  Calculate new `interval_days`.
4.  Set `next_review_date = now()->addDays($interval_days)`.
5.  Update `UserMemorizationProgress` table.

### Phase 8: Quiz Generation Gemini or (GPT-4 API)

Implement `QuizController`:

1.  If no questions exist for a Surah, use Laravel HTTP client to call Gemini ors OpenAI GPT-4 API.
2.  Prompt: _"Generate a multiple-choice question in Arabic to test the memorization of this Ayah: [Ayah Text]. Format response as JSON."_
3.  Save the generated question to `GeneratedQuestions` table.
4.  Display the quiz to the user using Alpine.js for interactivity.

---

**Let's start with Phase 1. Please execute the initialization, and show me the setup steps.**

<?php

namespace App\Services;

class TajweedService
{
    protected array $rules = [
        'h' => [
            'class' => 'ham_wasl',
            'type' => 'hamza-wasl',
            'color' => '#AAAAAA',
            'desc_ar' => 'همزة الوصل',
            'desc_en' => 'Hamzat ul Wasl',
            'category_ar' => 'همزات',
            'category_en' => 'Hamza Rules',
            'explanation_ar' => 'همزة الوصل هي همزة تُنطق عند البدء بالكلمة وتسقط عند الوصل. تُنطق بدون حركة وتُكتب ألفاً مجردة من الهمزة. تأتي في: أل التعريف، أمر الفعل الثلاثي، ماضي الخماسي والسداسي.',
            'explanation_en' => 'Hamzat ul Wasl is a connecting hamza that is pronounced when starting speech but dropped when continuing from a previous word. It appears in: the definite article "al", the imperative of trilateral verbs, and the past tense of five- and six-radical verbs.',
            'example_ar' => 'ٱلْحَمْدُ - تُنطق عند البدء: "أَلْحَمْدُ"، وتسقط عند الوصل: "وَلَٰكِنَّ لْحَمْدُ"',
            'example_en' => 'ٱلْحَمْدُ - Pronounced when starting: "Al-hamdu", dropped when connecting: "wa-laakin-l-hamdu"',
            'how_to_ar' => 'عند البدء بالكلام تُنطق بهمزة مكسورة، وعند الوصل تُسقط تماماً ويُبدأ بالحرف الذي بعدها مباشرة.',
            'how_to_en' => 'When starting speech, pronounce it with a kasrah. When connecting, drop it entirely and start with the next letter.',
        ],
        's' => [
            'class' => 'slnt',
            'type' => 'silent',
            'color' => '#AAAAAA',
            'desc_ar' => 'حرف ساكن (صامت)',
            'desc_en' => 'Silent Letter',
            'category_ar' => 'أحكام عامة',
            'category_en' => 'General Rules',
            'explanation_ar' => 'حرف ساكن لا يُنطق به عند القراءة، ويُحذف لفظاً مع بقاء كتابته. يُعرف أيضاً بالحرف المُخفى.',
            'explanation_en' => 'A silent letter that is not pronounced during recitation but remains written. Also known as a hidden letter.',
            'example_ar' => 'في كلمة "أَنَا" تُحذف الألف لفظاً عند الوقف فتُقرأ "أَنَ"',
            'example_en' => 'In the word "أَنَا", the alif is dropped in pronunciation at stop, read as "ana"',
            'how_to_ar' => 'لا تُنطق بهذا الحرف إطلاقاً، فقط تجاوزه إلى الحرف الذي يليه.',
            'how_to_en' => 'Do not pronounce this letter at all, simply skip to the next letter.',
        ],
        'l' => [
            'class' => 'lsm_shms',
            'type' => 'laam-shamsiyah',
            'color' => '#AAAAAA',
            'desc_ar' => 'لام التعريف الشمسية',
            'desc_en' => 'Laam Shamsiyyah (Solar Lam)',
            'category_ar' => 'أحكام اللام',
            'category_en' => 'Lam Rules',
            'explanation_ar' => 'لام التعريف الشمسية هي لام "ال" التعريف التي لا تُنطق بل تُدغم في الحرف الذي بعدها. سُميت شمسية لأن الحرف الذي بعدها يُشبه الشمس في الظهور والإظهار. الحروف الشمسية هي 14 حرفاً: ت ث د ذ ر ز س ش ص ض ط ظ ل ن.',
            'explanation_en' => 'The solar lam is the lam of the definite article "al" that is not pronounced but assimilated into the following letter. It is called "solar" because the following letter shines through. The 14 solar letters are: t, th, d, dh, r, z, s, sh, sad, dad, ta, dha, l, n.',
            'example_ar' => 'الشَّمْسُ تُقرأ "اشَّمْسُ" - اللام لا تُنطق والشين مشددة',
            'example_en' => 'الشَّمْسُ is read "ash-shamsu" - the lam is not pronounced and the shin is doubled',
            'how_to_ar' => 'لا تُنطق اللام، بل أُدغم الحرف الذي بعدها (أي شدّده) مع بقاء اللام مكتوبة.',
            'how_to_en' => 'Do not pronounce the lam. Instead, double (shadeed) the following letter while the lam remains written.',
        ],
        'n' => [
            'class' => 'madda_normal',
            'type' => 'madda-normal',
            'color' => '#537FFF',
            'desc_ar' => 'المد الطبيعي (حركتان)',
            'desc_en' => 'Normal Prolongation: 2 Vowels',
            'category_ar' => 'أحكام المد',
            'category_en' => 'Prolongation (Madd)',
            'explanation_ar' => 'المد الطبيعي هو إطالة الصوت بحرف المد (الألف الساكنة المفتوح ما قبلها، أو الواو الساكنة المضموم ما قبلها، أو الياء الساكنة المكسور ما قبلها) بمقدار حركتين. الحركة هي زمن قبض الإصبع أو بسطه، ويُقدَّر بنحو ثانية واحدة.',
            'explanation_en' => 'Normal prolongation is extending the voice with a madd letter (alif after fathah, waw after dammah, or ya after kasrah) for 2 vowel counts. A vowel count is approximately one second.',
            'example_ar' => 'قَالَ - تُمد الألف بمقدار حركتين. يُقَالُ - تُمد الياء بمقدار حركتين',
            'example_en' => 'قَالَ - extend the alif for 2 counts. يُقَالُ - extend the ya for 2 counts',
            'how_to_ar' => 'مُد الصوت بحرف المد بمقدار حركتين (حوالي ثانيتين) دون زيادة أو نقصان.',
            'how_to_en' => 'Extend the voice with the madd letter for 2 vowel counts (about 2 seconds) without increase or decrease.',
        ],
        'p' => [
            'class' => 'madda_permissible',
            'type' => 'madda-permissible',
            'color' => '#4050FF',
            'desc_ar' => 'المد الجائز (2 أو 4 أو 6 حركات)',
            'desc_en' => 'Permissible Prolongation: 2, 4, 6 Vowels',
            'category_ar' => 'أحكام المد',
            'category_en' => 'Prolongation (Madd)',
            'explanation_ar' => 'المد الجائز المنفصل يحدث عندما يأتي حرف المد في آخر كلمة وهمزة القطع في أول الكلمة التالية. يُسمى جائزاً لأن القارئ مخيّر بين المد 2 أو 4 أو 6 حركات. ويُسمى منفصلاً لأن السبب (الهمزة) منفصل عن حرف المد في كلمة أخرى.',
            'explanation_en' => 'Permissible separated prolongation occurs when a madd letter is at the end of a word and a hamzah qat\' (cutting hamza) is at the beginning of the next word. The reciter may extend 2, 4, or 6 counts. It is called "separated" because the cause (hamza) is in a separate word.',
            'example_ar' => 'يَا أَيُّهَا - يُمد الياء في "يَا" لأن بعدها همزة قطع في "أَيُّهَا"',
            'example_en' => 'يَا أَيُّهَا - extend the ya in "yaa" because a hamzah follows in "ayyuha"',
            'how_to_ar' => 'يمكنك المد حركتين أو 4 حركات أو 6 حركات. الاختيار الأكثر شيوعاً هو 4 حركات.',
            'how_to_en' => 'You may extend 2, 4, or 6 vowel counts. The most common choice is 4 counts.',
        ],
        'm' => [
            'class' => 'madda_necessary',
            'type' => 'madda-necessary',
            'color' => '#000EBC',
            'desc_ar' => 'المد اللازم (6 حركات)',
            'desc_en' => 'Necessary Prolongation: 6 Vowels',
            'category_ar' => 'أحكام المد',
            'category_en' => 'Prolongation (Madd)',
            'explanation_ar' => 'المد اللازم يحدث عندما يأتي بعد حرف المد همزة أو سكون لازم في نفس الكلمة. سُمي لازماً لأنه يجب مده دائماً بمقدار 6 حركات. ويكون في: الكلمات المشددة مثل "الطَّآمَّة" وفي الوقف على الحرف المشدد.',
            'explanation_en' => 'Necessary prolongation occurs when a hamzah or permanent sukoon follows a madd letter in the same word. It is called "necessary" because it must always be extended 6 counts. It occurs in doubled letters like "at-taaaammah".',
            'example_ar' => 'الطَّآمَّةُ - تُمد الألف 6 حركات لأن بعدها مشدد. آلْآنَ - تُمد الهمزة 6 حركات',
            'example_en' => 'الطَّآمَّةُ - extend the alif 6 counts because a doubled letter follows. آلْآنَ - extend the hamza 6 counts',
            'how_to_ar' => 'يجب المد بمقدار 6 حركات (حوالي 6 ثوانٍ) دائماً دون أقل أو أكثر.',
            'how_to_en' => 'Must extend exactly 6 vowel counts (about 6 seconds) always, no more no less.',
        ],
        'q' => [
            'class' => 'qlq',
            'type' => 'qalaqah',
            'color' => '#DD0008',
            'desc_ar' => 'القلقلة',
            'desc_en' => 'Qalaqah',
            'category_ar' => 'أحكام الوقف',
            'category_en' => 'Stopping Rules',
            'explanation_ar' => 'القلقلة هي إضطراب الحرف الساكن عند النطق به حتى يسمع له نبرة قوية. وتكون في أحرف خمسة تجمعها كلمة "قُطْبُ جَدٍ". وهي: القاف، الطاء، الباء، الجيم، الدال. وتكون في حالتين: عند السكون العارض (الوقف) وعند السكون الأصلي (في الوصل والوقف).',
            'explanation_en' => 'Qalaqah is the vibration/echo of a still letter when pronounced so a strong tone is heard. It applies to five letters collected in "Qutb Jadd": Qaf, Ta, Ba, Jim, Dal. It occurs at temporary sukoon (stopping) and permanent sukoon (in connection and stopping).',
            'example_ar' => 'خَلَقَ - عند الوقف على القاف تُقلقل. عَبَدَ - عند الوقف على الدال تُقلقل',
            'example_en' => 'خَلَقَ - when stopping on Qaf, apply qalaqah. عَبَدَ - when stopping on Dal, apply qalaqah',
            'how_to_ar' => 'عند النطق بالحرف الساكن من أحرف القلقلة، اضغط عليه ثم أطلقه بقوة لإنتاج نبرة مترددة.',
            'how_to_en' => 'When pronouncing a still qalaqah letter, press on it then release forcefully to produce a bouncing/echoing tone.',
        ],
        'o' => [
            'class' => 'madda_obligatory',
            'type' => 'madda-obligatory',
            'color' => '#2144C1',
            'desc_ar' => 'المد الواجب المتصل (4-5 حركات)',
            'desc_en' => 'Obligatory Prolongation: 4-5 Vowels',
            'category_ar' => 'أحكام المد',
            'category_en' => 'Prolongation (Madd)',
            'explanation_ar' => 'المد الواجب المتصل يحدث عندما تأتي الهمزة بعد حرف المد في نفس الكلمة. سُمي متصلاً لأن السبب (الهمزة) متصل بحرف المد في نفس الكلمة. وسمي واجباً لأن مدّه واجب عند جميع القراء بمقدار 4 أو 5 حركات.',
            'explanation_en' => 'Obligatory connected prolongation occurs when a hamzah follows a madd letter in the same word. It is called "connected" because the cause (hamzah) is connected to the madd letter in the same word, and "obligatory" because all reciters agree on extending it 4-5 counts.',
            'example_ar' => 'جَآءَ - تُمد الألف 4-5 حركات لأن الهمزة بعدها في نفس الكلمة. سُوٓءَ - تُمد الواو 4-5 حركات',
            'example_en' => 'جَآءَ - extend alif 4-5 counts because hamzah follows in same word. سُوٓءَ - extend waw 4-5 counts',
            'how_to_ar' => 'مُد الصوت بمقدار 4 أو 5 حركات (حوالي 4-5 ثوانٍ). الأفضل 5 حركات.',
            'how_to_en' => 'Extend the voice 4 or 5 vowel counts (about 4-5 seconds). 5 counts is preferred.',
        ],
        'c' => [
            'class' => 'ikhf_shfw',
            'type' => 'ikhafa-shafawi',
            'color' => '#D500B7',
            'desc_ar' => 'الإخفاء الشفوي',
            'desc_en' => "Ikhafa' Shafawi (Labial Concealment)",
            'category_ar' => 'أحكام الميم الساكنة',
            'category_en' => 'Still Mim Rules',
            'explanation_ar' => 'الإخفاء الشفوي يحدث عندما تأتي الميم الساكنة قبل حرف الباء. سُمي شفوياً لأن الميم والباء يُخرجان من الشفتين. وفيه تُخفى الميم الساكنة مع بقاء الغنة (النون الأنفية) بمقدار حركتين. أي تُنطق الميم بصورة介于 الإظهار والإدغام.',
            'explanation_en' => "Labial concealment occurs when a still mim comes before the letter Ba. It is called labial because both mim and ba are articulated from the lips. The still mim is concealed while maintaining ghunnah (nasal sound) for 2 counts - a state between clear pronunciation and full assimilation.",
            'example_ar' => 'وَمَا بَعْضُ - الميم الساكنة في "مَا" قبل الباء في "بَعْضُ" تُخفى مع الغنة',
            'example_en' => 'وَمَا بَعْضُ - the still mim in "maa" before ba in "ba\'dhu" is concealed with ghunnah',
            'how_to_ar' => 'انطق الميم بصورة مخفاة مع إبقاء الغنة (الصوت الأنفي) بمقدار حركتين، دون إطباق الشفتين إطباقاً كاملاً.',
            'how_to_en' => 'Pronounce the mim in a concealed manner while maintaining the nasal sound (ghunnah) for 2 counts, without fully closing the lips.',
        ],
        'f' => [
            'class' => 'ikhf',
            'type' => 'ikhafa',
            'color' => '#9400A8',
            'desc_ar' => 'الإخفاء الحقيقي',
            'desc_en' => "Ikhafa' (True Concealment)",
            'category_ar' => 'أحكام النون الساكنة والتنوين',
            'category_en' => 'Still Nun & Tanween Rules',
            'explanation_ar' => 'الإخفاء الحقيقي يحدث عندما تأتي النون الساكنة أو التنوين قبل أحد أحرف الإخفاء الخمسة عشر: ص ذ ث ك ج ش ق س د ط ز ف ت ض ظ. وفيه يُنطق بحرف النون أو التنوين بصورة بين الإظهار والإدغام مع بقاء الغنة بمقدار حركتين. سُمي إخفاءً لأن النون تُخفى عند النطق.',
            'explanation_en' => "True concealment occurs when a still nun or tanween comes before any of the 15 concealment letters: Sad, Dhal, Tha, Kaf, Jim, Shin, Qaf, Sin, Dal, Ta, Zay, Fa, Ta, Dad, Dha. The nun is pronounced in a state between clear and assimilated, with ghunnah for 2 counts.",
            'example_ar' => 'أَنتُمْ - النون قبل التاء تُخفى. كِتَابٌ كَرِيمٌ - التنوين قبل الكاف يُخفى',
            'example_en' => 'أَنتُمْ - the nun before ta is concealed. كِتَابٌ كَرِيمٌ - tanween before kaf is concealed',
            'how_to_ar' => 'انطق النون بصورة مخفاة (بين الإظهار والإدغام) مع إبقاء الغنة بمقدار حركتين. اجعل اللسان قريباً من مخرج النون لكن لا تُطبقه.',
            'how_to_en' => 'Pronounce the nun in a concealed manner (between clear and assimilated) with ghunnah for 2 counts. Keep the tongue near the nun articulation point but not touching.',
        ],
        'w' => [
            'class' => 'idghm_shfw',
            'type' => 'idgham-shafawi',
            'color' => '#58B800',
            'desc_ar' => 'الإدغام الشفوي (المتماثلين الصغير)',
            'desc_en' => 'Idgham Shafawi (Labial Assimilation)',
            'category_ar' => 'أحكام الميم الساكنة',
            'category_en' => 'Still Mim Rules',
            'explanation_ar' => 'الإدغام الشفوي يحدث عندما تأتي الميم الساكنة قبل ميم أخرى. وفيه تُدغم الميم الساكنة في الميم التي بعدها مع بقاء الغنة بمقدار حركتين. يُصبح الحرفان ميم واحدة مشددة مع الغنة.',
            'explanation_en' => 'Labial assimilation occurs when a still mim comes before another mim. The still mim is fully assimilated into the following mim with ghunnah for 2 counts. The two mims become one doubled mim with ghunnah.',
            'example_ar' => 'وَلَهُم مَّا - الميم الساكنة في "لَهُم" تُدغم في الميم التي بعدها فتصبح ميماً واحدة مشددة',
            'example_en' => 'وَلَهُم مَّا - the still mim in "lahum" is assimilated into the following mim, becoming one doubled mim',
            'how_to_ar' => 'أُدغم الميم الأولى في الثانية فتصبح ميم واحدة مشددة مع الغنة بمقدار حركتين.',
            'how_to_en' => 'Assimilate the first mim into the second, becoming one doubled mim with ghunnah for 2 counts.',
        ],
        'i' => [
            'class' => 'iqlb',
            'type' => 'iqlab',
            'color' => '#26BFFD',
            'desc_ar' => 'الإقلاب',
            'desc_en' => 'Iqlab (Conversion)',
            'category_ar' => 'أحكام النون الساكنة والتنوين',
            'category_en' => 'Still Nun & Tanween Rules',
            'explanation_ar' => 'الإقلاب يحدث عندما تأتي النون الساكنة أو التنوين قبل حرف الباء. وفيه تُقلب النون أو التنوين إلى ميم مخفاة مع الغنة بمقدار حركتين. سُمي إقلاباً لأن النون تُقلب وتتحول إلى ميم.',
            'explanation_en' => 'Conversion occurs when a still nun or tanween comes before the letter Ba. The nun/tanween is converted into a concealed mim with ghunnah for 2 counts. It is called "iqlab" (conversion) because the nun is flipped/converted into a mim.',
            'example_ar' => 'أَن بُورِكَ - النون قبل الباء تُقلب ميماً مخفاة. عَلِيمٌ بِمَا - التنوين قبل الباء يُقلب ميماً',
            'example_en' => 'أَن بُورِكَ - the nun before ba is converted to a concealed mim. عَلِيمٌ بِمَا - tanween before ba is converted to mim',
            'how_to_ar' => 'عند رؤية النون أو التنوين قبل الباء، انطقها ميماً مخفاة مع الغنة بمقدار حركتين. أغلق الشفتين قليلاً.',
            'how_to_en' => 'When seeing nun/tanween before ba, pronounce it as a concealed mim with ghunnah for 2 counts. Slightly close the lips.',
        ],
        'a' => [
            'class' => 'idgh_ghn',
            'type' => 'idgham-with-ghunnah',
            'color' => '#169777',
            'desc_ar' => 'الإدغام بغنة',
            'desc_en' => 'Idgham with Ghunnah',
            'category_ar' => 'أحكام النون الساكنة والتنوين',
            'category_en' => 'Still Nun & Tanween Rules',
            'explanation_ar' => 'الإدغام بغنة يحدث عندما تأتي النون الساكنة أو التنوين قبل أحد أحرف "يَرْمَلُون" (الياء، الراء، الميم، اللام، الواو، النون). وفيه تُدغم النون أو التنوين في الحرف الذي بعدها مع بقاء الغنة (الصوت الأنفي) بمقدار حركتين. يُوجد في أربعة أحرف: ي م و ن (يومن).',
            'explanation_en' => 'Assimilation with ghunnah occurs when a still nun or tanween comes before one of the letters in "Yarmaloon" (Ya, Ra, Mim, Lam, Waw, Nun). The nun is assimilated into the following letter while maintaining ghunnah (nasal sound) for 2 counts. It applies to 4 letters: y, m, w, n.',
            'example_ar' => 'مَن يَّعْمَلْ - النون قبل الياء تُدغم مع الغنة. جَنَّاتٍ تَجْرِي - التنوين قبل التاء',
            'example_en' => 'مَن يَّعْمَلْ - nun before ya is assimilated with ghunnah. جَنَّاتٍ تَجْرِي - tanween before ta',
            'how_to_ar' => 'أُدغم النون في الحرف الذي يليه من أحرف (ي و م ن) مع إبقاء الغنة بمقدار حركتين.',
            'how_to_en' => 'Assimilate the nun into the following letter from (y, w, m, n) while maintaining ghunnah for 2 counts.',
        ],
        'u' => [
            'class' => 'idgh_w_ghn',
            'type' => 'idgham-without-ghunnah',
            'color' => '#169200',
            'desc_ar' => 'الإدغام بلا غنة',
            'desc_en' => 'Idgham without Ghunnah',
            'category_ar' => 'أحكام النون الساكنة والتنوين',
            'category_en' => 'Still Nun & Tanween Rules',
            'explanation_ar' => 'الإدغام بلا غنة يحدث عندما تأتي النون الساكنة أو التنوين قبل حرفيّ الراء أو اللام. وفيه تُدغم النون أو التنوين في الحرف الذي بعدها بدون غنة. أي يُصبح الحرفان حرفاً واحداً مشدداً.',
            'explanation_en' => 'Assimilation without ghunnah occurs when a still nun or tanween comes before the letters Ra or Lam. The nun is fully assimilated into the following letter without ghunnah. The two letters become one doubled letter.',
            'example_ar' => 'مِن رَّبِّهِمْ - النون قبل الراء تُدغم بلا غنة. هُدًى لِّلْمُتَّقِينَ - التنوين قبل اللام يُدغم بلا غنة',
            'example_en' => 'مِن رَّبِّهِمْ - nun before ra is assimilated without ghunnah. هُدًى لِّلْمُتَّقِينَ - tanween before lam is assimilated without ghunnah',
            'how_to_ar' => 'أُدغم النون في الراء أو اللام مباشرة بدون أي غنة، فتصبح حرفاً واحداً مشدداً.',
            'how_to_en' => 'Assimilate the nun directly into ra or lam without any ghunnah, becoming one doubled letter.',
        ],
        'd' => [
            'class' => 'idgh_mus',
            'type' => 'idgham-mutajanisayn',
            'color' => '#A1A1A1',
            'desc_ar' => 'الإدغام المتماثلين',
            'desc_en' => 'Idgham Mutajanisayn (Identical Letters)',
            'category_ar' => 'أنواع الإدغام',
            'category_en' => 'Types of Idgham',
            'explanation_ar' => 'إدغام المتماثلين يحدث عندما يأتي حرفان متحدان في المخرج والصفة متجاوران، فيُدغم الأول في الثاني. مثل: د+د، ت+ت، ب+ب. يصبح الحرفان حرفاً واحداً مشدداً.',
            'explanation_en' => 'Assimilation of identical letters occurs when two letters with the same articulation point and characteristics are adjacent. The first is assimilated into the second. For example: d+d, t+t, b+b. The two letters become one doubled letter.',
            'example_ar' => 'وَدَّت طَّائِفَةٌ - الدال في "وَدَّت" تُدغم في الطاء المتقاربة. اضْرِب بَّعْدَ - الباء تُدغم في الباء',
            'example_en' => 'وَدَّت طَّائِفَةٌ - dal is assimilated into ta. اضْرِب بَّعْدَ - ba is assimilated into ba',
            'how_to_ar' => 'أُدغم الحرف الأول في الثاني إذا كانا من نفس المخرج، فيصبحان حرفاً واحداً مشدداً.',
            'how_to_en' => 'Assimilate the first letter into the second if they share the same articulation point, becoming one doubled letter.',
        ],
        'b' => [
            'class' => 'idgh_mus',
            'type' => 'idgham-mutaqaribayn',
            'color' => '#A1A1A1',
            'desc_ar' => 'الإدغام المتقاربين',
            'desc_en' => 'Idgham Mutaqaribayn (Close Letters)',
            'category_ar' => 'أنواع الإدغام',
            'category_en' => 'Types of Idgham',
            'explanation_ar' => 'إدغام المتقاربين يحدث عندما يأتي حرفان متقاربان في المخرج أو الصفة، فيُدغم الأول في الثاني. التقارب يعني أن المخرجين متجاوران. مثل: ل+ر، ق+ك، ط+ت.',
            'explanation_en' => 'Assimilation of close letters occurs when two letters are close in articulation point or characteristics. The first is assimilated into the second. Closeness means the articulation points are adjacent. For example: l+r, q+k, t+t.',
            'example_ar' => 'وَقَالُوا رَبَّنَا - اللام القريبة من الراء. أَلَمْ نَخْلُقكُّم - القاف والكاف متقاربان',
            'example_en' => 'وَقَالُوا رَبَّنَا - lam close to ra. أَلَمْ نَخْلُقكُّم - qaf and kaf are close letters',
            'how_to_ar' => 'أُدغم الحرف الأول في الثاني إذا كانا متقاربين في المخرج، فيصبحان حرفاً واحداً مشدداً.',
            'how_to_en' => 'Assimilate the first letter into the second if they are close in articulation, becoming one doubled letter.',
        ],
        'g' => [
            'class' => 'ghn',
            'type' => 'ghunnah',
            'color' => '#FF7E1E',
            'desc_ar' => 'الغنة (حركتان)',
            'desc_en' => 'Ghunnah: 2 Vowels',
            'category_ar' => 'أحكام عامة',
            'category_en' => 'General Rules',
            'explanation_ar' => 'الغنة هي صوت يخرج من الأنف (الخيشوم) يُرافق حرفي النون والميم. تُسمى أيضاً الخنخنة. وتكون واجبة بمقدار حركتين في: النون والميم المشددتين، والنون والميم المخفاتين، والإدغام بغنة. الحركة هي زمن قبض الإصبع أو بسطه تقريباً.',
            'explanation_en' => 'Ghunnah is a sound that comes from the nasal cavity (nasal passage) accompanying the letters Nun and Mim. It is also called nasalization. It is obligatory for 2 counts with: doubled nun and mim, concealed nun and mim, and assimilation with ghunnah. A vowel count is approximately the time of one finger bend.',
            'example_ar' => 'إِنَّ - النون المشددة فيها غنة حركتين. أَمَّا - الميم المشددة فيها غنة حركتين',
            'example_en' => 'إِنَّ - the doubled nun has 2 counts of ghunnah. أَمَّا - the doubled mim has 2 counts of ghunnah',
            'how_to_ar' => 'أخرج صوتاً أنفياً (من الخيشوم) مع نطق النون أو الميم بمقدار حركتين.',
            'how_to_en' => 'Produce a nasal sound from the nasal cavity while pronouncing nun or mim for 2 counts.',
        ],
    ];

    public function parse(string $text): string
    {
        if (empty($text)) {
            return '';
        }

        $pattern = '/\[([a-z]):?([^\[]*)\[([^\]]*)\]/u';

        $locale = app()->getLocale();
        $descKey = $locale === 'ar' ? 'desc_ar' : 'desc_en';

        $result = preg_replace_callback($pattern, function ($matches) use ($descKey) {
            $identifier = $matches[1];
            $content = $matches[3];

            if (!isset($this->rules[$identifier])) {
                return $content;
            }

            $rule = $this->rules[$identifier];

            return sprintf(
                '<tajweed class="%s" data-type="%s" data-desc="%s">%s</tajweed>',
                $rule['class'],
                $rule['type'],
                $rule[$descKey],
                htmlspecialchars($content)
            );
        }, $text);

        return $result;
    }

    public function getRules(): array
    {
        return $this->rules;
    }

    public function getRulesByCategory(): array
    {
        $locale = app()->getLocale();
        $catKey = $locale === 'ar' ? 'category_ar' : 'category_en';
        $categories = [];

        foreach ($this->rules as $key => $rule) {
            $cat = $rule[$catKey];
            if (!isset($categories[$cat])) {
                $categories[$cat] = [];
            }
            $categories[$cat][$key] = $rule;
        }

        return $categories;
    }
}

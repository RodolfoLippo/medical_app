$(document).ready(function () {
    const baseURL = "http://localhost/medical_app/src/";

    const languages = {
        es: {
            src: baseURL + "assets/flag/Spanish.png",
            lang: "es",
            alt: "Language",
            file: baseURL + "assets/ES-EN/es.json"
        },
        en: {
            src: baseURL + "assets/flag/English.png",
            lang: "en",
            alt: "Language",
            file: baseURL + "assets/ES-EN/en.json"
        }
    };

    function loadTranslations(lang) {
        if (!languages[lang]) {
            console.error('Invalid language in loadTranslations:', lang);
            return;
        }

        $.getJSON(languages[lang].file, function (data) {
            console.log('Loaded translations:', data); // Log the loaded translations
            $('[data-translate]').each(function () {
                const key = $(this).attr('data-translate');
                const keys = key.split('.');
                let text = data;
                for (let i = 0; i < keys.length; i++) {
                    if (text[keys[i]] === undefined) {
                        console.error(`Missing translation for key: ${key}`);
                        text = key; // Fallback to key if translation is missing
                        break;
                    }
                    text = text[keys[i]];
                }
                if (typeof text === 'string') {
                    $(this).text(text);
                } else {
                    console.error(`Translation for key: ${key} is not a string`);
                    $(this).text(key); // Fallback to key if the final text is not a string
                }
            });
        }).fail(function (jqxhr, textStatus, error) {
            console.error('Error loading translation file:', textStatus, error);
        });
    }

    function setLanguage(lang) {
        console.log('Setting language to:', lang);

        if (!languages[lang]) {
            console.error('Invalid language in setLanguage:', lang);
            return;
        }

        $('#currentLanguage').attr('src', languages[lang].src);
        $('#currentLanguage').attr('data-lang', languages[lang].lang);
        $('#currentLanguage').attr('alt', languages[lang].alt);

        loadTranslations(lang);

        localStorage.setItem('preferredLanguage', lang);

        $.ajax({
            url: baseURL + 'set_language.php',
            method: 'POST',
            data: { language: lang },
            success: function (response) {
                console.log('Language set to ' + lang);
                
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('Error setting language:', textStatus, errorThrown);
            }
        });
    }

    $('#languageDropdown').click(function () {
        const currentLang = $('#currentLanguage').attr('data-lang');
        console.log('Current language:', currentLang);
        const newLang = currentLang === 'es' ? 'en' : 'es';
        console.log('New language:', newLang);
        setLanguage(newLang);
    });

    const initialLang = localStorage.getItem('preferredLanguage') || 'es';
    setLanguage(initialLang);
});

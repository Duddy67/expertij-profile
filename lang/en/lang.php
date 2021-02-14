<?php

return [
    'profile' => [
	'civility' => 'Civility',
	'mr' => 'Mr',
	'mrs' => 'Mrs',
	'first_name' => 'First Name',
	'last_name' => 'Last Name',
	'birth_name' => 'Birth Name',
	'birth_date' => 'Birth Date',
	'birth_location' => 'Birth Location',
	'citizenship' => 'Citizenship',
	'street' => 'Street',
	'city' => 'City',
	'postcode' => 'Postcode',
	'country' => 'Country',
	'phone' => 'Phone',
	'email' => 'Email',
	'username' => 'User Name',
	'password' => 'Password',
	'password_confirmation' => 'Password Confirmation',
	'password_current' => 'Current Password',
    ],
    'action' => [
	'check_in_success' => ':count item(s) successfully checked-in.',
	'checked_out_item' => 'The ":name" item cannot be modified as it is currently checked out by a user.',
	'check_out_do_not_match' => 'The user checking out doesn\'t match the user who checked out the item. You are not permitted to use that link to directly access that page.',
	'delete_success' => 'The item has been successfully deleted.',
    ],
    'messages' => [
	'required_field' => 'This field is required',
	'skill_checkboxes' => 'At least one of both checkboxes (Interpreter or Translator) must be checked.',
    ],
    'account' => [
	'template' => 'Template',
	'template_desc' => 'The template to display when the user is logged out.',
	'hostedFields' => 'Hosted Fields',
	'hostedFields_desc' => '',
	'extraRegistrationFields_desc' => 'The name of a partial which provides extra registration fields on the behalf of another plugin. Only used with the register template.',
    ],
    'licence' => [
	'expert' => 'Expert',
	'ceseda' => 'CESEDA',
	'appeal_court' => 'Appeal court',
	'court' => 'Court',
	'interpreter' => 'Interpreter',
	'translator' => 'Translator',
	'cassation' => 'Cassation',
    ],
    'licences' => [
	'type' => 'Type',
	'since' => 'Since',
	'appeal_court_id' => 'Appeal Court',
	'court_id' => 'Court',
        'attestations' => [
	    'file' => 'Attestation',
	    'expiry_date' => 'Expiry Date',
	    'languages' => [
		'alpha_2' => 'Language',
		'interpreter' => 'Interpreter',
		'translator' => 'Translator',
	    ],
	],
    ],
    'citizenship' => [
	'AD' => 'Andorian',
	'AE' => 'Emirian',
	'AF' => 'Afghani',
	'AI' => 'Anguillan',
	'AM' => 'Armenian',
	'AO' => 'Angolian',
	'AQ' => 'Antarctic',
	'AR' => 'Argentine',
	'AS' => 'Austrian',
	'AU' => 'Australian',
	'AW' => 'Arubian',
	'BA' => 'Bangladeshi',
	'BB' => 'Barbadian',
	'BE' => 'Belgian',
	'BH' => 'Bahrainian',
	'BM' => 'Bermuda',
	'BO' => 'Bolivian',
	'BR' => 'Brazilian',
	'BS' => 'Bahameese',
	'BT' => 'Bhutanese',
	'BU' => 'Bulgarian',
	'BY' => 'Belarusian',
	'BZ' => 'Belizean',
	'CA' => 'Canadian',
	'CG' => 'Congolese',
	'CH' => 'Chinese',
	'CH' => 'Swiss',
	'CL' => 'Chilean',
	'CM' => 'Cambodian',
	'CM' => 'Cameroonian',
	'CO' => 'Columbian',
	'CR' => 'Czech',
	'CR' => 'Costa Rican',
	'CU' => 'Cuban',
	'CY' => 'Cypriot',
	'DE' => 'German',
	'DK' => 'Danish',
	'DM' => 'Dominican',
	'EC' => 'Ecuadorean',
	'EE' => 'Estonian',
	'EG' => 'Egyptian',
	'ET' => 'Ethiopian',
	'FI' => 'Finnish',
	'FJ' => 'Fijian',
	'FR' => 'French',
	'GB' => 'British',
	'GE' => 'Georgian',
	'GH' => 'Ghanaian',
	'GN' => 'Guinean',
	'GR' => 'Greek',
	'GY' => 'Guyanese',
	'HK' => 'Chinese',
	'HR' => 'Croatian',
	'HU' => 'Hungarian',
	'ID' => 'Indonesian',
	'IE' => 'Irish',
	'IN' => 'Indian',
	'IQ' => 'Iraqi',
	'IR' => 'Iranian',
	'IS' => 'Israeli',
	'IS' => 'Icelander',
	'IT' => 'Italian',
	'JM' => 'Jamaican',
	'JO' => 'Jordanian',
	'JP' => 'Japanese',
	'KE' => 'Kenyan',
	'KO' => 'Korean',
	'KW' => 'Kuwaiti',
	'KZ' => 'Kazakhstani',
	'KZ' => 'Kazakhstani',
	'LB' => 'Lebanese',
	'LK' => 'Sri Lankan',
	'LT' => 'Lithuanian',
	'LU' => 'Luxembourger',
	'MA' => 'Moroccan',
	'MC' => 'Monacan',
	'ME' => 'Mexican',
	'MM' => 'Mayanmarese',
	'MN' => 'Mongolian',
	'MO' => 'Macau',
	'MU' => 'Mauritian',
	'MV' => 'Maldivan',
	'MY' => 'Malaysian',
	'NA' => 'Namibian',
	'NG' => 'Nigerian',
	'NL' => 'Dutch',
	'NO' => 'Norwegian',
	'NP' => 'Nepalese',
	'NZ' => 'New Zealander',
	'OM' => 'Omani',
	'PA' => 'Panamanian',
	'PE' => 'Peruvian',
	'PH' => 'Filipino',
	'PK' => 'Pakistani',
	'PO' => 'Polish',
	'PT' => 'Portugees',
	'PY' => 'Paraguayan',
	'QA' => 'Qatari',
	'RO' => 'Romanian',
	'RU' => 'Russian',
	'SA' => 'Saudi Arabian',
	'SC' => 'Seychellois',
	'SE' => 'Swedish',
	'SG' => 'Singaporean',
	'SK' => 'Slovakian',
	'SN' => 'Senegalese',
	'SO' => 'Somali',
	'SP' => 'Spanish',
	'TH' => 'Thai',
	'TN' => 'Tunisian',
	'TR' => 'Turkish',
	'TW' => 'Taiwanese',
	'TZ' => 'Tanzanian',
	'UA' => 'Ukrainian',
	'UG' => 'Ugandan',
	'US' => 'American',
	'UY' => 'Uruguayan',
	'UZ' => 'Uzbekistani',
	'VE' => 'Venezuelan',
	'VN' => 'Vietnamese',
	'YE' => 'Yemeni',
	'ZA' => 'South African',
	'ZM' => 'Zambian',
	'ZW' => 'Zimbabwean',
    ],
    'language' => [
	'aa' => 'Afar',
	'ab' => 'Abkhazian',
	'af' => 'Afrikaans',
	'ak' => 'Akan',
	'sq' => 'Albanian',
	'am' => 'Amharic',
	'ar' => 'Arabic',
	'an' => 'Aragonese',
	'hy' => 'Armenian',
	'as' => 'Assamese',
	'av' => 'Avaric',
	'ae' => 'Avestan',
	'ay' => 'Aymara',
	'az' => 'Azerbaijani',
	'ba' => 'Bashkir',
	'bm' => 'Bambara',
	'eu' => 'Basque',
	'be' => 'Belarusian',
	'bn' => 'Bengali',
	'bh' => 'Bihari languages',
	'bi' => 'Bislama',
	'bs' => 'Bosnian',
	'br' => 'Breton',
	'bg' => 'Bulgarian',
	'my' => 'Burmese',
	'ca' => 'Catalan; Valencian',
	'ch' => 'Chamorro',
	'ce' => 'Chechen',
	'zh' => 'Chinese',
	'cu' => 'Church Slavic; Old Slavonic; Church Slavonic; Old Bulgarian; Old Church Slavonic',
	'cv' => 'Chuvash',
	'kw' => 'Cornish',
	'co' => 'Corsican',
	'cr' => 'Cree',
	'cs' => 'Czech',
	'da' => 'Danish',
	'dv' => 'Divehi; Dhivehi; Maldivian',
	'nl' => 'Dutch; Flemish',
	'dz' => 'Dzongkha',
	'en' => 'English',
	'eo' => 'Esperanto',
	'et' => 'Estonian',
	'ee' => 'Ewe',
	'fo' => 'Faroese',
	'fj' => 'Fijian',
	'fi' => 'Finnish',
	'fr' => 'French',
	'fy' => 'Western Frisian',
	'ff' => 'Fulah',
	'ka' => 'Georgian',
	'de' => 'German',
	'gd' => 'Gaelic; Scottish Gaelic',
	'ga' => 'Irish',
	'gl' => 'Galician',
	'gv' => 'Manx',
	'el' => 'Greek, Modern (1453-)',
	'gn' => 'Guarani',
	'gu' => 'Gujarati',
	'ht' => 'Haitian; Haitian Creole',
	'ha' => 'Hausa',
	'he' => 'Hebrew',
	'hz' => 'Herero',
	'hi' => 'Hindi',
	'ho' => 'Hiri Motu',
	'hr' => 'Croatian',
	'hu' => 'Hungarian',
	'ig' => 'Igbo',
	'is' => 'Icelandic',
	'io' => 'Ido',
	'ii' => 'Sichuan Yi; Nuosu',
	'iu' => 'Inuktitut',
	'ie' => 'Interlingue; Occidental',
	'ia' => 'Interlingua (International Auxiliary Language Association)',
	'id' => 'Indonesian',
	'ik' => 'Inupiaq',
	'it' => 'Italian',
	'jv' => 'Javanese',
	'ja' => 'Japanese',
	'kl' => 'Kalaallisut; Greenlandic',
	'kn' => 'Kannada',
	'ks' => 'Kashmiri',
	'kr' => 'Kanuri',
	'kk' => 'Kazakh',
	'km' => 'Central Khmer',
	'ki' => 'Kikuyu; Gikuyu',
	'rw' => 'Kinyarwanda',
	'ky' => 'Kirghiz; Kyrgyz',
	'kv' => 'Komi',
	'kg' => 'Kongo',
	'ko' => 'Korean',
	'kj' => 'Kuanyama; Kwanyama',
	'ku' => 'Kurdish',
	'lo' => 'Lao',
	'la' => 'Latin',
	'lv' => 'Latvian',
	'li' => 'Limburgan; Limburger; Limburgish',
	'ln' => 'Lingala',
	'lt' => 'Lithuanian',
	'lb' => 'Luxembourgish; Letzeburgesch',
	'lu' => 'Luba-Katanga',
	'lg' => 'Ganda',
	'mk' => 'Macedonian',
	'mh' => 'Marshallese',
	'ml' => 'Malayalam',
	'mi' => 'Maori',
	'mr' => 'Marathi',
	'ms' => 'Malay',
	'mg' => 'Malagasy',
	'mt' => 'Maltese',
	'mn' => 'Mongolian',
	'na' => 'Nauru',
	'nv' => 'Navajo; Navaho',
	'nr' => 'Ndebele, South; South Ndebele',
	'nd' => 'Ndebele, North; North Ndebele',
	'ng' => 'Ndonga',
	'ne' => 'Nepali',
	'nn' => 'Norwegian Nynorsk; Nynorsk, Norwegian',
	'nb' => 'BokmÃ¥l, Norwegian; Norwegian BokmÃ¥l',
	'no' => 'Norwegian',
	'ny' => 'Chichewa; Chewa; Nyanja',
	'oc' => 'Occitan (post 1500); Provençal',
	'oj' => 'Ojibwa',
	'or' => 'Oriya',
	'om' => 'Oromo',
	'os' => 'Ossetian; Ossetic',
	'pa' => 'Panjabi; Punjabi',
	'fa' => 'Persian',
	'pi' => 'Pali',
	'pl' => 'Polish',
	'pt' => 'Portuguese',
	'ps' => 'Pushto; Pashto',
	'qu' => 'Quechua',
	'rm' => 'Romansh',
	'ro' => 'Romanian; Moldavian; Moldovan',
	'rn' => 'Rundi',
	'ru' => 'Russian',
	'sg' => 'Sango',
	'sa' => 'Sanskrit',
	'si' => 'Sinhala; Sinhalese',
	'sk' => 'Slovak',
	'sl' => 'Slovenian',
	'se' => 'Northern Sami',
	'sm' => 'Samoan',
	'sn' => 'Shona',
	'sd' => 'Sindhi',
	'so' => 'Somali',
	'st' => 'Sotho, Southern',
	'es' => 'Spanish; Castilian',
	'sc' => 'Sardinian',
	'sr' => 'Serbian',
	'ss' => 'Swati',
	'su' => 'Sundanese',
	'sw' => 'Swahili',
	'sv' => 'Swedish',
	'ty' => 'Tahitian',
	'ta' => 'Tamil',
	'tt' => 'Tatar',
	'te' => 'Telugu',
	'tg' => 'Tajik',
	'tl' => 'Tagalog',
	'th' => 'Thai',
	'bo' => 'Tibetan',
	'ti' => 'Tigrinya',
	'to' => 'Tonga (Tonga Islands)',
	'tn' => 'Tswana',
	'ts' => 'Tsonga',
	'tk' => 'Turkmen',
	'tr' => 'Turkish',
	'tw' => 'Twi',
	'ug' => 'Uighur; Uyghur',
	'uk' => 'Ukrainian',
	'ur' => 'Urdu',
	'uz' => 'Uzbek',
	've' => 'Venda',
	'vi' => 'Vietnamese',
	'vo' => 'Volapük',
	'cy' => 'Welsh',
	'wa' => 'Walloon',
	'wo' => 'Wolof',
	'xh' => 'Xhosa',
	'yi' => 'Yiddish',
	'yo' => 'Yoruba',
	'za' => 'Zhuang; Chuang',
	'zu' => 'Zulu',
    ],
];

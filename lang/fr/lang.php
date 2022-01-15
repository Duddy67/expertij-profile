<?php

return [
    'profile' => [
	'civility' => 'Civilité',
	'mr' => 'Mr',
	'mrs' => 'Mme',
	'first_name' => 'Prénom',
	'last_name' => 'Nom',
	'birth_name' => 'Nom de naissance',
	'birth_date' => 'Date de naissance',
	'birth_location' => 'Lieu de naissance',
	'citizenship' => 'Nationalité',
	'street' => 'Adresse',
	'city' => 'Ville',
	'postcode' => 'Code postal',
	'country' => 'Pays',
	'phone' => 'Téléphone',
	'email' => 'Email',
	'honorary_member' => 'Membre d\'honneur',
	'username' => 'Nom d\'utilisateur',
	'password' => 'Mot de passe',
	'new_password' => 'Nouveau mot de passe',
	'password_confirmation' => 'Confirmer mot de passe',
	'new_password_confirmation' => 'Confirmer nouveau mot de passe',
	'password_current' => 'Mot de passe actuel',
	'details' => 'Détails',
	'licences' => 'Licences',
	'photo' => 'Photo',
    ],
    'action' => [
	'check_in_success' => ':count item(s) successfully checked-in.',
	'checked_out_item' => 'The ":name" item cannot be modified as it is currently checked out by a user.',
	'check_out_do_not_match' => 'The user checking out doesn\'t match the user who checked out the item. You are not permitted to use that link to directly access that page.',
	'delete_success' => 'L\'item a été supprimé avec succès.',
	'select' => '- Selectionner -',
	'register' => 'Inscription',
	'next' => 'Suivant >>',
	'previous' => '<< Précédant',
	'new_licence' => 'Nouvelle Licence',
	'save' => 'Sauvegarder',
	'delete' => 'Supprimer',
	'replace' => 'Remplacer',
	'delete_licence' => 'Supprimer Licence',
	'new_attestation' => 'Nouvelle Attestation',
	'delete_attestation' => 'Supprimer Attestation',
	'add_language' => 'Ajouter Language',
    ],
    'message' => [
	'required_field' => 'Ce champ est requis',
	'skill_checkboxes' => 'Au moins une des 2 cases à cocher (Interprète ou Traducteur) doit être cochée.',
	'delete_confirmation' => 'Vous êtes sur le point de supprimer un élément. Etes vous sûr(e) ?',
	'welcome_message' => 'Félicitation ! Votre candidature a été prise en compte. Un administrateur vous informera sur le suivit de votre dossier.',
    ],
    'account' => [
	'template' => 'Template',
	'template_desc' => 'Le template à afficher lorsque l\'utilisateur n\'est pas connecté.',
	'guestPlugin' => 'Plugin invité',
	'guestPlugin_desc' => '',
	'extraRegistrationFields_desc' => 'Le nom d\'un partial qui fournit des champs d\'inscription supplémentaires pour le compte d\'un autre plugin. Utilisé uniquement avec le template d\'inscription.',
    ],
    'licence' => [
	'expert' => 'Expert',
	'ceseda' => 'CESEDA',
	'appeal_court' => 'Cour d\'appel',
	'court' => 'Cour',
	'interpreter' => 'Interprète',
	'translator' => 'Traducteur',
	'cassation' => 'Cassation',
    ],
    'licences' => [
	'type' => 'Type',
	'since' => 'Depuis',
	'appeal_court_id' => 'Cour d\'appel',
	'court_id' => 'Cour',
        'attestations' => [
	    'file' => 'Attestation',
	    'expiry_date' => 'Date d\'expiration',
	    'languages' => [
		'alpha_2' => 'Language',
		'interpreter' => 'Interprète',
		'translator' => 'Traducteur',
		'cassation' => 'Cassation',
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
	'ca' => 'Catalan - Valencian',
	'ch' => 'Chamorro',
	'ce' => 'Chechen',
	'zh' => 'Chinese',
	'cu' => 'Church Slavic - Old Slavonic - Church Slavonic - Old Bulgarian - Old Church Slavonic',
	'cv' => 'Chuvash',
	'kw' => 'Cornish',
	'co' => 'Corsican',
	'cr' => 'Cree',
	'cs' => 'Czech',
	'da' => 'Danish',
	'dv' => 'Divehi - Dhivehi - Maldivian',
	'nl' => 'Dutch - Flemish',
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
	'gd' => 'Gaelic - Scottish Gaelic',
	'ga' => 'Irish',
	'gl' => 'Galician',
	'gv' => 'Manx',
	'el' => 'Greek, Modern (1453-)',
	'gn' => 'Guarani',
	'gu' => 'Gujarati',
	'ht' => 'Haitian - Haitian Creole',
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
	'ii' => 'Sichuan Yi - Nuosu',
	'iu' => 'Inuktitut',
	'ie' => 'Interlingue - Occidental',
	'ia' => 'Interlingua (International Auxiliary Language Association)',
	'id' => 'Indonesian',
	'ik' => 'Inupiaq',
	'it' => 'Italian',
	'jv' => 'Javanese',
	'ja' => 'Japanese',
	'kl' => 'Kalaallisut - Greenlandic',
	'kn' => 'Kannada',
	'ks' => 'Kashmiri',
	'kr' => 'Kanuri',
	'kk' => 'Kazakh',
	'km' => 'Central Khmer',
	'ki' => 'Kikuyu - Gikuyu',
	'rw' => 'Kinyarwanda',
	'ky' => 'Kirghiz - Kyrgyz',
	'kv' => 'Komi',
	'kg' => 'Kongo',
	'ko' => 'Korean',
	'kj' => 'Kuanyama - Kwanyama',
	'ku' => 'Kurdish',
	'lo' => 'Lao',
	'la' => 'Latin',
	'lv' => 'Latvian',
	'li' => 'Limburgan - Limburger - Limburgish',
	'ln' => 'Lingala',
	'lt' => 'Lithuanian',
	'lb' => 'Luxembourgish - Letzeburgesch',
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
	'nv' => 'Navajo - Navaho',
	'nr' => 'Ndebele, South - South Ndebele',
	'nd' => 'Ndebele, North - North Ndebele',
	'ng' => 'Ndonga',
	'ne' => 'Nepali',
	'nn' => 'Norwegian Nynorsk - Nynorsk, Norwegian',
	'nb' => 'BokmÃ¥l, Norwegian - Norwegian BokmÃ¥l',
	'no' => 'Norwegian',
	'ny' => 'Chichewa - Chewa - Nyanja',
	'oc' => 'Occitan (post 1500) - Provençal',
	'oj' => 'Ojibwa',
	'or' => 'Oriya',
	'om' => 'Oromo',
	'os' => 'Ossetian - Ossetic',
	'pa' => 'Panjabi - Punjabi',
	'fa' => 'Persian',
	'pi' => 'Pali',
	'pl' => 'Polish',
	'pt' => 'Portuguese',
	'ps' => 'Pushto - Pashto',
	'qu' => 'Quechua',
	'rm' => 'Romansh',
	'ro' => 'Romanian - Moldavian - Moldovan',
	'rn' => 'Rundi',
	'ru' => 'Russian',
	'sg' => 'Sango',
	'sa' => 'Sanskrit',
	'si' => 'Sinhala - Sinhalese',
	'sk' => 'Slovak',
	'sl' => 'Slovenian',
	'se' => 'Northern Sami',
	'sm' => 'Samoan',
	'sn' => 'Shona',
	'sd' => 'Sindhi',
	'so' => 'Somali',
	'st' => 'Sotho, Southern',
	'es' => 'Spanish - Castilian',
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
	'ug' => 'Uighur - Uyghur',
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
	'za' => 'Zhuang - Chuang',
	'zu' => 'Zulu',
    ],
];

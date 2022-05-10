<?php

return [
    'profile' => [
	'title' => 'Profil',
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
	'professional_status' => 'Statut professionnel',
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
	'sign_in' => 'Se connecter',
	'not_registered_yet' => 'Pas encore adhérent(e) ?',
	'already_registered' => 'Déjà adhérent(e) ?',
	'enter_your' => 'Entrez votre ',
	'stay_logged_in' => 'Rester connecté(e)',
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
    'javascript' => [
	'alert_inputCodeOfEthics' => 'Vous devez certifier avoir pris connaissance du code de déontologie.',
	'alert_inputStatuses' => 'Vous devez certifier avoir pris connaissance des statuts de l\'association.',
	'alert_inputInternalRues' => 'Vous devez certifier avoir pris connaissance du réglement intérieur.',
    ],
    'citizenship' => [
        'AF' => 'Afghanistan',
        'ZA' => 'Afrique du Sud',
        'AX' => 'Åland, Îles',
        'AL' => 'Albanie',
        'DZ' => 'Algérie',
        'DE' => 'Allemagne',
        'AD' => 'Andorre',
        'AO' => 'Angola',
        'AI' => 'Anguilla',
        'AQ' => 'Antarctique',
        'AG' => 'Antigua et Barbuda',
        'AN' => 'Antilles néerlandaises',
        'SA' => 'Arabie Saoudite',
        'AR' => 'Argentine',
        'AM' => 'Arménie',
        'AW' => 'Aruba',
        'AU' => 'Australie',
        'AT' => 'Autriche',
        'AZ' => 'Azerbaïdjan',
        'BS' => 'Bahamas',
        'BH' => 'Bahrein',
        'BD' => 'Bangladesh',
        'BB' => 'Barbade',
        'BY' => 'Bélarus',
        'BE' => 'Belgique',
        'BZ' => 'Bélize',
        'BJ' => 'Bénin',
        'BM' => 'Bermudes',
        'BT' => 'Bhoutan',
        'BO' => 'Bolivie (État plurinational de)' ,
        'BA' => 'Bosnie-Herzégovine',
        'BW' => 'Botswana' ,
        'BR' => 'Brésil',
        'BN' => 'Brunéi Darussalam',
        'BG' => 'Bulgarie',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'CV' => 'Cabo Verde',
        'KY' => 'Caïmans, Iles',
        'KH' => 'Cambodge',
        'CM' => 'Cameroun',
        'CA' => 'Canada',
        'CL' => 'Chili',
        'CN' => 'Chine',
        'CX' => 'Christmas, île',
        'CY' => 'Chypre',
        'CC' => 'Cocos/Keeling (Îles)',
        'CO' => 'Colombie',
        'KM' => 'Comores',
        'CG' => 'Congo',
        'CD' => 'Congo, République démocratique du',
        'CK' => 'Cook, Iles',
        'KR' => 'Corée, République de',
        'KP' => 'Corée, République populaire démocratique de',
        'CR' => 'Costa Rica',
        'CI' => 'Côte d\'Ivoire',
        'HR' => 'Croatie',
        'CU' => 'Cuba' ,
        'DK' => 'Danemark',
        'DJ' => 'Djibouti',
        'DO' => 'Dominicaine, République',
        'DM' => 'Dominique',
        'EG' => 'Egypte',
        'SV' => 'El Salvador',
        'AE' => 'Emirats arabes unis',
        'EC' => 'Equateur',
        'ER' => 'Erythrée',
        'ES' => 'Espagne',
        'EE' => 'Estonie',
        'US' => 'Etats-Unis d\'Amérique',
        'ET' => 'Ethiopie',
        'FK' => 'Falkland/Malouines (Îles)',
        'FO' => 'Féroé, îles',
        'FJ' => 'Fidji',
        'FI' => 'Finlande',
        'FR' => 'France',
        'GA' => 'Gabon',
        'GM' => 'Gambie',
        'GE' => 'Géorgie' ,
        'GH' => 'Ghana',
        'GI' => 'Gibraltar',
        'GR' => 'Grèce',
        'GD' => 'Grenade',
        'GL' => 'Groenland',
        'GP' => 'Guadeloupe',
        'GU' => 'Guam',
        'GT' => 'Guatemala',
        'GG' => 'Guernesey',
        'GN' => 'Guinée',
        'GW' => 'Guinée-Bissau',
        'GQ' => 'Guinée équatoriale',
        'GY' => 'Guyana',
        'GF' => 'Guyane française',
        'HT' => 'Haïti',
        'HN' => 'Honduras',
        'HK' => 'Hong Kong',
        'HU' => 'Hongrie',
        'IM' => 'Île de Man',
        'UM' => 'Îles mineures éloignées des Etats-Unis',
        'VG' => 'Îles vierges britanniques',
        'VI' => 'Îles vierges des Etats-Unis',
        'IN' => 'Inde',
        'IO' => 'Indien (Territoire britannique de l\'océan)',
        'ID' => 'Indonésie',
        'IR' => 'Iran, République islamique',
        'IQ' => 'Iraq',
        'IE' => 'Irlande',
        'IS' => 'Islande',
        'IL' => 'Israël',
        'IT' => 'Italie',
        'JM' => 'Jamaïque',
        'JP' => 'Japon',
        'JE' => 'Jersey',
        'JO' => 'Jordanie',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenya',
        'KG' => 'Kirghizistan',
        'KI' => 'Kiribati',
        'KW' => 'Koweït',
        'LA' => 'Lao, République démocratique populaire',
        'LS' => 'Lesotho',
        'LV' => 'Lettonie',
        'LB' => 'Liban',
        'LR' => 'Libéria',
        'LY' => 'Libye',
        'LI' => 'Liechtenstein',
        'LT' => 'Lituanie',
        'LU' => 'Luxembourg',
        'MO' => 'Macao',
        'MK' => 'Macédoine, ex-République yougoslave de',
        'MG' => 'Madagascar',
        'MY' => 'Malaisie',
        'MW' => 'Malawi',
        'MV' => 'Maldives',
        'ML' => 'Mali',
        'MT' => 'Malte',
        'MP' => 'Mariannes du nord, Iles',
        'MA' => 'Maroc',
        'MH' => 'Marshall, Iles',
        'MQ' => 'Martinique',
        'MU' => 'Maurice',
        'MR' => 'Mauritanie',
        'YT' => 'Mayotte',
        'MX' => 'Mexique',
        'FM' => 'Micronésie, Etats Fédérés de',
        'MD' => 'Moldova, République de',
        'MC' => 'Monaco',
        'MN' => 'Mongolie',
        'ME' => 'Monténégro',
        'MS' => 'Montserrat',
        'MZ' => 'Mozambique',
        'MM' => 'Myanmar',
        'NA' => 'Namibie',
        'NR' => 'Nauru',
        'NP' => 'Népal',
        'NI' => 'Nicaragua',
        'NE' => 'Niger',
        'NG' => 'Nigéria',
        'NU' => 'Niue',
        'NF' => 'Norfolk, Ile',
        'NO' => 'Norvège',
        'NC' => 'Nouvelle-Calédonie',
        'NZ' => 'Nouvelle-Zélande',
        'OM' => 'Oman',
        'UG' => 'Ouganda',
        'UZ' => 'Ouzbékistan',
        'PK' => 'Pakistan',
        'PW' => 'Palaos',
        'PS' => 'Palestine, Etat de',
        'PA' => 'Panama',
        'PG' => 'Papouasie-Nouvelle-Guinée',
        'PY' => 'Paraguay',
        'NL' => 'Pays-Bas',
        'PE' => 'Pérou',
        'PH' => 'Philippines',
        'PN' => 'Pitcairn',
        'PL' => 'Pologne',
        'PF' => 'Polynésie française',
        'PR' => 'Porto Rico',
        'PT' => 'Portugal',
        'QA' => 'Qatar',
        'SY' => 'République arabe syrienne',
        'CF' => 'République centrafricaine',
        'RE' => 'Réunion',
        'RO' => 'Roumanie',
        'GB' => 'Royaume-Uni de Grande-Bretagne et d\'Irlande du Nord',
        'RU' => 'Russie, Fédération de',
        'RW' => 'Rwanda',
        'EH' => 'Sahara occidental',
        'BL' => 'Saint-Barthélemy',
        'KN' => 'Saint-Kitts-et-Nevis',
        'SM' => 'Saint-Marin',
        'MF' => 'Saint-Martin (partie française)' ,
        'PM' => 'Saint-Pierre-et-Miquelon',
        'VC' => 'Saint-Vincent-et-les-Grenadines',
        'SH' => 'Sainte-Hélène, Ascension et Tristan da Cunha',
        'LC' => 'Sainte-Lucie',
        'SB' => 'Salomon, Iles',
        'WS' => 'Samoa',
        'AS' => 'Samoa américaines',
        'ST' => 'Sao Tomé-et-Principe',
        'SN' => 'Sénégal',
        'RS' => 'Serbie',
        'SC' => 'Seychelles',
        'SL' => 'Sierra Leone',
        'SG' => 'Singapour',
        'SK' => 'Slovaquie',
        'SI' => 'Slovénie',
        'SO' => 'Somalie',
        'SD' => 'Soudan',
        'SS' => 'Soudan du Sud',
        'LK' => 'Sri Lanka',
        'SE' => 'Suède',
        'CH' => 'Suisse',
        'SR' => 'Suriname',
        'SJ' => 'Svalbard et île Jan Mayen',
        'SZ' => 'Swaziland',
        'TJ' => 'Tadjikistan',
        'TW' => 'Taïwan, Province de Chine',
        'TZ' => 'Tanzanie, République unie de',
        'TD' => 'Tchad' ,
        'CZ' => 'Tchèque, République',
        'TF' => 'Terres australes françaises',
        'TH' => 'Thaïlande',
        'TL' => 'Timor-Leste',
        'TG' => 'Togo',
        'TK' => 'Tokelau',
        'TO' => 'Tonga',
        'TT' => 'Trinité-et-Tobago',
        'TN' => 'Tunisie',
        'TM' => 'Turkménistan',
        'TC' => 'Turks-et-Caïcos (Îles)',
        'TR' => 'Turquie',
        'TV' => 'Tuvalu',
        'UA' => 'Ukraine',
        'UY' => 'Uruguay',
        'VU' => 'Vanuatu',
        'VE' => 'Venezuela (République bolivarienne du)',
        'VN' => 'Viet Nam',
        'WF' => 'Wallis et Futuna',
        'YE' => 'Yémen',
        'ZM' => 'Zambie',
        'ZW' => 'Zimbabwe'
    ],
    'language' => [
	'aa' => 'Afar',
	'ab' => 'Abkhaze',
	'ae' => 'Avestique',
	'af' => 'Afrikaans',
	'ak' => 'Akan',
	'am' => 'Amharique',
	'an' => 'Aragonais',
	'ar' => 'Arabe',
	'as' => 'Assamais',
	'av' => 'Avar',
	'ay' => 'Aymara',
	'az' => 'Azéri',
	'ba' => 'Bachkir',
	'be' => 'Biélorusse',
	'bg' => 'Bulgare',
	'bh' => 'Bihari',
	'bi' => 'Bichelamar',
	'bm' => 'Bambara',
	'bn' => 'Bengali',
	'bo' => 'Tibétain',
	'br' => 'Breton',
	'bs' => 'Bosnien',
	'ca' => 'Catalan',
	'ce' => 'Tchétchène',
	'ch' => 'Chamorro',
	'co' => 'Corse',
	'cr' => 'Cri',
	'cs' => 'Tchèque',
	'cu' => 'Vieux-slave',
	'cv' => 'Tchouvache',
	'cy' => 'Gallois',
	'da' => 'Danois',
	'de' => 'Allemand',
	'dv' => 'Maldivien',
	'dz' => 'Dzongkha',
	'ee' => 'Ewe',
	'el' => 'Grec moderne',
	'en' => 'Anglais',
	'eo' => 'Espéranto',
	'es' => 'Espagnol',
	'et' => 'Estonien',
	'eu' => 'Basque',
	'fa' => 'Persan',
	'ff' => 'Peul',
	'fi' => 'Finnois',
	'fj' => 'Fidjien',
	'fo' => 'Féroïen',
	'fr' => 'Français',
	'fy' => 'Frison occidental',
	'ga' => 'Irlandais',
	'gd' => 'Écossais',
	'gl' => 'Galicien',
	'gn' => 'Guarani',
	'gu' => 'Gujarati',
	'gv' => 'Mannois',
	'ha' => 'Haoussa',
	'he' => 'Hébreu',
	'hi' => 'Hindi',
	'ho' => 'Hiri motu',
	'hr' => 'Croate',
	'ht' => 'Créole haïtien',
	'hu' => 'Hongrois',
	'hy' => 'Arménien',
	'hz' => 'Héréro',
	'ia' => 'Interlingua',
	'id' => 'Indonésien',
	'ie' => 'Occidental',
	'ig' => 'Igbo',
	'ii' => 'Yi',
	'ik' => 'Inupiak',
	'io' => 'Ido',
	'is' => 'Islandais',
	'it' => 'Italien',
	'iu' => 'Inuktitut',
	'ja' => 'Japonais',
	'jv' => 'Javanais',
	'ka' => 'Géorgien',
	'kg' => 'Kikongo',
	'ki' => 'Kikuyu',
	'kj' => 'Kuanyama',
	'kk' => 'Kazakh',
	'kl' => 'Groenlandais',
	'km' => 'Khmer',
	'kn' => 'Kannada',
	'ko' => 'Coréen',
	'kr' => 'Kanouri',
	'ks' => 'Cachemiri',
	'ku' => 'Kurde',
	'kv' => 'Komi',
	'kw' => 'Cornique',
	'ky' => 'Kirghiz',
	'la' => 'Latin',
	'lb' => 'Luxembourgeois',
	'lg' => 'Ganda',
	'li' => 'Limbourgeois',
	'ln' => 'Lingala',
	'lo' => 'Lao',
	'lt' => 'Lituanien',
	'lu' => 'Luba',
	'lv' => 'Letton',
	'mg' => 'Malgache',
	'mh' => 'Marshallais',
	'mi' => 'Maori de Nouvelle-Zélande',
	'mk' => 'Macédonien',
	'ml' => 'Malayalam',
	'mn' => 'Mongol',
	'mo' => 'Moldave',
	'mr' => 'Marathi',
	'ms' => 'Malais',
	'mt' => 'Maltais',
	'my' => 'Birman',
	'na' => 'Nauruan',
	'nb' => 'Norvégien Bokmål',
	'nd' => 'Sindebele',
	'ne' => 'Népalais',
	'ng' => 'Ndonga',
	'nl' => 'Néerlandais',
	'nn' => 'Norvégien Nynorsk',
	'no' => 'Norvégien',
	'nr' => 'Nrebele',
	'nv' => 'Navajo',
	'ny' => 'Chichewa',
	'oc' => 'Occitan',
	'oj' => 'Ojibwé',
	'om' => 'Oromo',
	'or' => 'Oriya',
	'os' => 'Ossète',
	'pa' => 'Pendjabi',
	'pi' => 'Pali',
	'pl' => 'Polonais',
	'ps' => 'Pachto',
	'pt' => 'Portugais',
	'qu' => 'Quechua',
	'rc' => 'Créole Réunionnais',
	'rm' => 'Romanche',
	'rn' => 'Kirundi',
	'ro' => 'Roumain',
	'ru' => 'Russe',
	'rw' => 'Kinyarwanda',
	'sa' => 'Sanskrit',
	'sc' => 'Sarde',
	'sd' => 'Sindhi',
	'se' => 'Same du Nord',
	'sg' => 'Sango',
	'sh' => 'Serbo-croate',
	'si' => 'Cingalais',
	'sk' => 'Slovaque',
	'sl' => 'Slovène',
	'sm' => 'Samoan',
	'sn' => 'Shona',
	'so' => 'Somali',
	'sq' => 'Albanais',
	'sr' => 'Serbe',
	'ss' => 'Swati',
	'st' => 'Sotho du Sud',
	'su' => 'Soundanais',
	'sv' => 'Suédois',
	'sw' => 'Swahili',
	'ta' => 'Tamoul',
	'te' => 'Télougou',
	'tg' => 'Tadjik',
	'th' => 'Thaï',
	'ti' => 'Tigrigna',
	'tk' => 'Turkmène',
	'tl' => 'Tagalog',
	'tn' => 'Tswana',
	'to' => 'Tongien',
	'tr' => 'Turc',
	'ts' => 'Tsonga',
	'tt' => 'Tatar',
	'tw' => 'Twi',
	'ty' => 'Tahitien',
	'ug' => 'Ouïghour',
	'uk' => 'Ukrainien',
	'ur' => 'Ourdou',
	'uz' => 'Ouzbek',
	've' => 'Venda',
	'vi' => 'Vietnamien',
	'vo' => 'Volapük',
	'wa' => 'Wallon',
	'wo' => 'Wolof',
	'xh' => 'Xhosa',
	'yi' => 'Yiddish',
	'yo' => 'Yoruba',
	'za' => 'Zhuang',
	'zh' => 'Chinois',
	'zu' => 'Zoulou',
    ],
];

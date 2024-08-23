<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'Polje :attribute mora biti prihvaćeno.',
    'accepted_if' => 'Polje :attribute mora biti prihvaćeno kada je :other = :value.',
    'active_url' => 'Polje :attribute mora biti validan URL.',
    'after' => 'Polje :attribute mora biti datum posle :date.',
    'after_or_equal' => 'Polje :attribute mora biti datum poslije ili jednak datumu :date.',
    'alpha' => 'Polje :attribute može sadržati samo slova.',
    'alpha_dash' => 'Polje :attribute može sadržati samo slova, brojeve, crtice i donje crte.',
    'alpha_num' => 'Polje :attribute može sadržati samo slova i brojeve.',
    'array' => 'Polje :attribute mora biti niz.',
    'ascii' => 'Polje :attribute može sadržati samo jedno-bajtne alfanumeričke karaktere i simbole.',
    'before' => 'Polje :attribute mora biti datum prije :date.',
    'before_or_equal' => 'Polje :attribute mora biti datum prije ili jednak datumu :date.',
    'between' => [
        'array' => 'Polje :attribute mora imati između :min i :max stavki.',
        'file' => 'Polje :attribute mora biti između :min i :max kilobajta.',
        'numeric' => 'Polje :attribute mora biti između :min i :max.',
        'string' => 'Polje :attribute mora imati između :min i :max karaktera.',
    ],
    'boolean' => 'Polje :attribute mora biti tačno ili netačno.',
    'can' => 'Polje :attribute sadrži neovlašćenu vrednost.',
    'confirmed' => 'Potvrda za polje :attribute se ne poklapa.',
    'contains' => 'Polju :attribute nedostaje obavezna vrijednost.',
    'current_password' => 'Unijeta lozinka je netačna.',
    'date' => 'Polje :attribute mora biti validan datum.',
    'date_equals' => 'Polje :attribute mora biti datum jednak datumu :date.',
    'date_format' => 'Polje :attribute mora odgovarati formatu :format.',
    'decimal' => 'Polje :attribute mora imati sledeći broj decimala: :decimal .',
    'declined' => 'Polje :attribute mora biti odbijeno.',
    'declined_if' => 'Polje :attribute mora biti odbijeno kada je :other = :value.',
    'different' => 'Polja :attribute i :other moraju biti različiti.',
    'digits' => 'Polje :attribute mora imati :digits cifara.',
    'digits_between' => 'Polje :attribute mora imati između :min i :max cifara.',
    'dimensions' => 'Polje :attribute ima neispravne dimenzije slike.',
    'distinct' => 'Polje :attribute ima duplikat vrijednost.',
    'doesnt_end_with' => 'Polje :attribute ne smije završavati sa jednom od sledećih vrijednosti: :values.',
    'doesnt_start_with' => 'Polje :attribute ne sijme počinjati sa jednom od sledećih vrijednosti: :values.',
    'email' => 'Polje :attribute mora biti validna email adresa.',
    'ends_with' => 'Polje :attribute mora završavati sa jednom od sledećih vrijednosti: :values.',
    'enum' => 'Izabrana vrijednost :attribute je neispravna.',
    'exists' => 'Izabrana vrijednost :attribute je neispravna.',
    'extensions' => 'Polje :attribute mora imati jednu od sledećih ekstenzija: :values.',
    'file' => 'Polje :attribute mora biti datoteka.',
    'filled' => 'Polje :attribute mora imati vrijednost.',
    'gt' => [
        'array' => 'Polje :attribute mora imati više od :value stavki.',
        'file' => 'Polje :attribute mora biti veće od :value kilobajta.',
        'numeric' => 'Polje :attribute mora biti veće od :value.',
        'string' => 'Polje :attribute mora imati više od :value karaktera.',
    ],
    'gte' => [
        'array' => 'Polje :attribute mora imati :value stavki ili više.',
        'file' => 'Polje :attribute mora biti veće ili jednako :value kilobajta.',
        'numeric' => 'Polje :attribute mora biti veće ili jednako :value.',
        'string' => 'Polje :attribute mora imati :value ili više karaktera.',
    ],
    'hex_color' => 'Polje :attribute mora biti validna heksadecimalna boja.',
    'image' => 'Polje :attribute mora biti slika.',
    'in' => 'Izabrana vrijednost za :attribute je neispravna.',
    'in_array' => 'Polje :attribute mora postojati u :other.',
    'integer' => 'Polje :attribute mora biti cio broj.',
    'ip' => 'Polje :attribute mora biti validna IP adresa.',
    'ipv4' => 'Polje :attribute mora biti validna IPv4 adresa.',
    'ipv6' => 'Polje :attribute mora biti validna IPv6 adresa.',
    'json' => 'Polje :attribute mora biti validan JSON string.',
    'list' => 'Polje :attribute mora biti lista.',
    'lowercase' => 'Polje :attribute mora sadržati samo mala slova.',
    'lt' => [
        'array' => 'Polje :attribute mora imati manje od :value stavki.',
        'file' => 'Polje :attribute mora biti manje od :value kilobajta.',
        'numeric' => 'Polje :attribute mora biti manje od :value.',
        'string' => 'Polje :attribute mora imati manje od :value karaktera.',
    ],
    'lte' => [
        'array' => 'Polje :attribute ne smije imati više od :value stavki.',
        'file' => 'Polje :attribute mora biti manje ili jednako :value kilobajta.',
        'numeric' => 'Polje :attribute mora biti manje ili jednako :value.',
        'string' => 'Polje :attribute mora imati manje ili jednako :value karaktera.',
    ],
    'mac_address' => 'Polje :attribute mora biti validna MAC adresa.',
    'max' => [
        'array' => 'Polje :attribute ne smije imati više od :max stavki.',
        'file' => 'Polje :attribute ne smije biti veće od :max kilobajta.',
        'numeric' => 'Polje :attribute ne smije biti veće od :max.',
        'string' => 'Polje :attribute ne smije imati više od :max karaktera.',
    ],
    'max_digits' => 'Polje :attribute ne smije imati više od :max cifara.',
    'mimes' => 'Polje :attribute mora biti datoteka tipa: :values.',
    'mimetypes' => 'Polje :attribute mora biti datoteka tipa: :values.',
    'min' => [
        'array' => 'Polje :attribute mora imati najmanje :min stavki.',
        'file' => 'Polje :attribute mora imati najmanje :min kilobajta.',
        'numeric' => 'Polje :attribute mora biti najmanje :min.',
        'string' => 'Polje :attribute mora imati najmanje :min karaktera.',
    ],
    'min_digits' => 'Polje :attribute mora imati najmanje :min cifara.',
    'missing' => 'Polje :attribute mora biti izostavljeno.',
    'missing_if' => 'Polje :attribute mora biti izostavljeno kada je :other = :value.',
    'missing_unless' => 'Polje :attribute mora biti izostavljeno osim ako :other nije :value.',
    'missing_with' => 'Polje :attribute mora biti izostavljeno kada je :values prisutan.',
    'missing_with_all' => 'Polje :attribute mora biti izostavljeno kada su :values prisutni.',
    'multiple_of' => 'Polje :attribute mora biti višekratnik vrednosti :value.',
    'not_in' => 'Izabrana vrijednost :attribute je neispravna.',
    'not_regex' => 'Format polja :attribute je neispravan.',
    'numeric' => 'Polje :attribute mora biti broj.',
    'password' => [
        'letters' => 'Polje :attribute mora sadržati najmanje jedno slovo.',
        'mixed' => 'Polje :attribute mora sadržati najmanje jedno veliko i jedno malo slovo.',
        'numbers' => 'Polje :attribute mora sadržati najmanje jedan broj.',
        'symbols' => 'Polje :attribute mora sadržati najmanje jedan simbol.',
        'uncompromised' => 'Unijeti :attribute se pojavio u kršenju podataka. Molimo odaberite drugačiji :attribute.',
    ],
    'present' => 'Polje :attribute mora biti prisutno.',
    'present_if' => 'Polje :attribute mora biti prisutno kada je :other = :value.',
    'present_unless' => 'Polje :attribute mora biti prisutno osim ako :other nije :value.',
    'present_with' => 'Polje :attribute mora biti prisutno kada je :values prisutan.',
    'present_with_all' => 'Polje :attribute mora biti prisutno kada su :values prisutni.',
    'prohibited' => 'Polje :attribute je zabranjeno.',
    'prohibited_if' => 'Polje :attribute je zabranjeno kada je :other = :value.',
    'prohibited_unless' => 'Polje :attribute je zabranjeno osim ako :other nije u :values.',
    'prohibits' => 'Polje :attribute zabranjuje prisustvo :other.',
    'regex' => 'Format polja :attribute je neispravan.',
    'required' => 'Polje :attribute je obavezno.',
    'required_array_keys' => 'Polje :attribute mora sadržati stavke za: :values.',
    'required_if' => 'Polje :attribute je obavezno kada je :other = :value.',
    'required_if_accepted' => 'Polje :attribute je obavezno kada je :other prihvaćen.',
    'required_if_declined' => 'Polje :attribute je obavezno kada je :other odbijen.',
    'required_unless' => 'Polje :attribute je obavezno osim ako :other nije u :values.',
    'required_with' => 'Polje :attribute je obavezno kada je :values prisutan.',
    'required_with_all' => 'Polje :attribute je obavezno kada su :values prisutni.',
    'required_without' => 'Polje :attribute je obavezno kada :values nije prisutan.',
    'required_without_all' => 'Polje :attribute je obavezno kada nijedan od :values nije prisutan.',
    'same' => 'Polje :attribute mora se podudarati sa :other.',
    'size' => [
        'array' => 'Polje :attribute mora sadržati :size stavki.',
        'file' => 'Polje :attribute mora biti :size kilobajta.',
        'numeric' => 'Polje :attribute mora biti :size.',
        'string' => 'Polje :attribute mora imati :size karaktera.',
    ],
    'starts_with' => 'Polje :attribute mora početi sa jednom od sledećih vrijednosti: :values.',
    'string' => 'Polje :attribute mora biti string.',
    'timezone' => 'Polje :attribute mora biti validna vremenska zona.',
    'unique' => ':attribute je već zauzet.',
    'uploaded' => ':attribute nije uspio da se otpremi.',
    'uppercase' => 'Polje :attribute mora sadržati samo velika slova.',
    'url' => 'Polje :attribute mora biti validan URL.',
    'ulid' => 'Polje :attribute mora biti validan ULID.',
    'uuid' => 'Polje :attribute mora biti validan UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];

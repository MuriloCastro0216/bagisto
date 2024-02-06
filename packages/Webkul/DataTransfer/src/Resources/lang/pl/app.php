<?php

return [
    'importers'  => [
        'products'  => [
            'title'      => 'Produkty',

            'validation' => [
                'errors' => [
                    'duplicate-url-key'        => 'Klucz URL: \'%s\' został już wygenerowany dla produktu o SKU: \'%s\'.',
                    'invalid-attribute-family' => 'Nieprawidłowa wartość dla kolumny rodziny atrybutów (rodzina atrybutów nie istnieje?)',
                    'invalid-type'             => 'Typ produktu jest nieprawidłowy lub nieobsługiwany',
                    'sku-not-found'            => 'Produkt o podanym SKU nie został znaleziony',
                ],
            ],
        ],

        'customers' => [
            'title'      => 'Klienci',

            'validation' => [
                'errors' => [
                    'duplicate-email'        => 'Email : \'%s\' występuje więcej niż raz w pliku importu.',
                    'duplicate-phone'        => 'Telefon : \'%s\' występuje więcej niż raz w pliku importu.',
                    'invalid-customer-group' => 'Grupa klientów jest nieprawidłowa lub nieobsługiwana',
                    'email-not-found'        => 'Email : \'%s\' nie zostało znalezione w systemie.',
                ],
            ],
        ],
    ],

    'validation' => [
        'errors' => [
            'column-empty-headers' => 'Kolumny numer "%s" mają puste nagłówki.',
            'column-name-invalid'  => 'Nieprawidłowe nazwy kolumn: "%s".',
            'column-not-found'     => 'Nie znaleziono wymaganych kolumn: %s.',
            'column-numbers'       => 'Liczba kolumn nie odpowiada liczbie wierszy w nagłówku.',
            'invalid-attribute'    => 'Nagłówek zawiera nieprawidłowe atrybuty: "%s".',
            'system'               => 'Wystąpił nieoczekiwany błąd systemu.',
            'wrong-quotes'         => 'Użyto znaków pojedynczych cudzysłowów zamiast prostych cudzysłowów.',
        ],
    ],
];

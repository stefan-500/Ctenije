<!DOCTYPE html>
<html lang="sr">

<head>
    <meta charset="UTF-8">
    <title>{{ __('Potvrda porudžbine') }}</title>
    <style>
        /* Basic email styling similar to Tailwind */
        body {
            font-family: Arial, sans-serif;
            color: #333;
            background-color: #f9fafb;
            margin: 0;
            padding: 20px;
        }

        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 20px;
        }

        h1 {
            font-size: 24px;
            font-weight: 600;
            color: #111827;
            text-align: center;
        }

        h2 {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 12px;
        }

        p {
            margin-bottom: 16px;
            line-height: 1.6;
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            color: #3b82f6;
            margin-bottom: 30px;
        }

        .table-wrapper {
            margin-top: 24px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background-color: #f3f4f6;
            font-weight: 600;
            color: #374151;
        }

        td {
            color: #4b5563;
        }

        .total {
            font-weight: 600;
            font-size: 1.25em;
            margin-top: 20px;
            text-align: right;
        }

        .footer {
            margin-top: 40px;
            font-size: 12px;
            text-align: center;
            color: #9ca3af;
        }

        .contact {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <!-- Logo -->
        <div class="logo">Ctenije</div>

        <!-- Order Confirmation Heading -->
        <h1>{{ __('Hvala na Vašoj porudžbini!') }}</h1>

        <!-- Greeting -->
        <p>{{ __('Poštovani') }}
            {{ $porudzbina->user_id ? $porudzbina->user->ime : $porudzbina->guestDeliveryData->ime }},</p>

        <p>{{ __('Vaša porudžbina je uspiješno primljena i biće uskoro obrađena.') }}</p>

        <!-- Order Details -->
        <h2>{{ __('Detalji porudžbine:') }}</h2>
        <p><strong>{{ __('Broj porudžbine:') }}</strong> {{ $porudzbina->id }}</p>
        <p><strong>{{ __('Datum i vrijeme:') }}</strong>
            {{ \Carbon\Carbon::parse($porudzbina->datum)->format('d.m.Y H:i') }}</p>
        <p><strong>{{ __('Adresa isporuke:') }}</strong> {{ $porudzbina->adresa_isporuke }}</p>

        <!-- Order Items Table -->
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>{{ __('Šifra') }}</th>
                        <th>{{ __('Artikal') }}</th>
                        <th>{{ __('Cijena') }}</th>
                        <th>{{ __('Količina') }}</th>
                        <th>{{ __('Ukupna cijena') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($porudzbina->stavkePorudzbine as $stavka)
                        <tr>
                            <td>{{ $stavka->artikal->id }}
                            <td>{{ $stavka->artikal->naziv }}</td>
                            <td>{{ formatirajCijenu($stavka->artikal->akcijska_cijena ?? $stavka->artikal->cijena) }}
                                EUR</td>
                            <td>{{ $stavka->kolicina }}</td>
                            <td>{{ formatirajCijenu($stavka->ukupna_cijena) }} EUR</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Total Cost -->
        <p class="total">{{ __('Ukupno za naplatu:') }} {{ formatirajCijenu($porudzbina->ukupno) }} EUR</p>

        <!-- Contact Section -->
        <div class="contact">
            <p>{{ __('Ukoliko imate bilo kakva pitanja, slobodno nas kontaktirajte.') }}</p>
        </div>

        <!-- Closing -->
        <p>{{ __('Srdačan pozdrav,') }}<br>{{ config('app.name') }}</p>

        <!-- Footer -->
        <div class="footer">
            <p>{{ __('Ovo je automatski generisana poruka, molimo ne odgovarajte na ovaj email.') }}</p>
        </div>
    </div>
</body>

</html>

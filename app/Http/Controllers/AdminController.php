<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Porudzbina;
use App\Models\Artikal;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\VrstaArtikala;
use App\Models\ArtikalSlika;
use Illuminate\Support\Facades\DB;
use App\Models\Knjiga;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        $ukupnoKorisnika = User::where('ovlascenje', 'Korisnik')->count();
        $ukupnoPorudzbina = Porudzbina::count();
        $ukupnoArtikala = Artikal::count();
        $ukupnoMenadzera = User::where('ovlascenje', 'Menadzer')->count();

        return view('admin.index', compact('ukupnoKorisnika', 'ukupnoPorudzbina', 'ukupnoArtikala', 'ukupnoMenadzera'));
    }

    public function sviKorisnici()
    {
        $korisnici = User::where('ovlascenje', 'Korisnik')->get();

        return view('admin.korisnici.index', compact('korisnici'));
    }

    // Method to handle deletion
    public function obrisiKorisnika(User $user)
    {

        $user->delete();

        return redirect('/admin/korisnici/index')->with('success', __('Korisnik uspiješno obrisan.'));
    }

    public function sviMenadzeri()
    {
        $menadzeri = User::where('ovlascenje', 'Menadzer')->get();

        return view('admin.menadzeri.index', compact('menadzeri'));
    }

    public function obrisiMenadzera(User $user)
    {
        $user->delete();

        return redirect('/admin/menadzeri/index')->with('success', __('Menadžer uspiješno obrisan.'));
    }

    public function dodajMenadzera()
    {
        return view('admin.menadzeri.dodaj');
    }

    public function sacuvajMenadzera(Request $request)
    {
        $request->validate([
            'ime' => [
                'required',
                'string',
                'regex:/^[A-Z]{1}[a-zA-Z]{3,15}$/'
            ],
            'prezime' => [
                'required',
                'string',
                'regex:/^[A-Z]{1}[a-zA-Z]{3,15}$/'
            ],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:' . User::class
            ],
            'adresa' => [
                'required',
                'string',
                'regex:/^[a-zA-Z]{1,15}(\s[a-zA-Z]{1,15})?(\s[a-zA-Z]{1,12})?(\s[a-zA-Z0-9]{1,20})?\s[a-zA-Z0-9]{1,3}\,\s[0-9]{5}\s[a-zA-z]{3,13}$/'
            ],
            'tel' => [
                'required',
                'string',
                'regex:/^\+3816([0-9]){6,9}$/'
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->max(16)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
        ]);

        User::create([
            'ime' => $request->ime,
            'prezime' => $request->prezime,
            'email' => $request->email,
            'tel' => $request->tel,
            'adresa' => $request->adresa,
            'password' => Hash::make($request->password),
            'ovlascenje' => 'Menadzer',
        ]);

        return redirect('/admin/menadzeri/index')->with('success', 'Menadžer uspiješno kreiran!');
    }

    public function sviArtikli()
    {
        $artikli = Artikal::all();

        foreach ($artikli as $artikal) {
            $artikal->cijena = formatirajCijenu($artikal->cijena);
            if ($artikal->akcijska_cijena != null) {
                $artikal->akcijska_cijena = formatirajCijenu($artikal->akcijska_cijena);
            }
        }

        return view('admin.artikli.index', compact('artikli'));
    }

    public function obrisiArtikal(Artikal $artikal)
    {
        $artikal->delete();

        return redirect('/admin/artikli/index')->with('success', __('Artikal uspiješno obrisan.'));
    }

    public function dodajArtikal()
    {
        $vrsteArtikala = VrstaArtikala::all();

        return view('admin.artikli.dodaj', compact('vrsteArtikala'));
    }

    public function sacuvajArtikal(Request $request)
    {
        $data = $request->validate([
            // Artikal
            'naziv' => 'required|string|max:70',
            'opis' => 'required|string',
            'cijena' => 'required|integer|min:0|max:10000',
            'akcijska_cijena' => 'nullable|integer|min:0|max:10000',
            'dostupna_kolicina' => 'required|integer|min:0|max:50',

            // Knjiga
            'isbn' => 'required|string|size:13|unique:knjigas,isbn',
            'autor' => 'required|string|max:40',
            'izdavac' => 'required|string|max:100',
            'izdanje' => 'required|string|min:4|max:4',
            'br_stranica' => 'required|string|max:4',
            'pismo' => 'required|in:Ćirilica,Latinica',

            // Vrsta Artikla
            'vrsta_artikala' => 'required|array',
            'vrsta_artikala.*' => 'exists:vrsta_artikalas,id',

            // Slika Artikla
            'slika' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Transakcija zbog osiguranja integriteta podataka
        DB::transaction(function () use ($data, $request) {
            $artikal = Artikal::create([
                'naziv' => $data['naziv'],
                'opis' => $data['opis'],
                'cijena' => $data['cijena'],
                'akcijska_cijena' => $data['akcijska_cijena'],
                'dostupna_kolicina' => $data['dostupna_kolicina'],
            ]);

            $knjiga = Knjiga::create([
                'isbn' => $data['isbn'],
                'autor' => $data['autor'],
                'izdavac' => $data['izdavac'],
                'izdanje' => $data['izdanje'],
                'br_stranica' => $data['br_stranica'],
                'pismo' => $data['pismo'],
                'artikal_id' => $artikal->id,
            ]);

            // Pivot tabela
            $artikal->vrsteArtikla()->attach($data['vrsta_artikala']);

            if ($request->hasFile('slika')) {
                $slikaArtikla = $request->file('slika');

                // Kreiranje naziva fajla
                $nazivFajla = time() . '_' . uniqid() . '.' . $slikaArtikla->getClientOriginalExtension();

                // Cuvanje fajla na 'storage/app/public/img'
                // 'public' je disk, definisan u config/filesystems.php
                $path = $slikaArtikla->storeAs('img', $nazivFajla, 'public');

                ArtikalSlika::create([
                    'naziv_fajla' => $nazivFajla,
                    'artikal_id' => $artikal->id,
                ]);
            }
        });

        return redirect('/admin/artikli/index')->with('success', __('Knjiga uspiješno dodata.'));
    }

    public function izmijeniArtikal(Artikal $artikal)
    {
        // Za odabir nove vrste artikla
        $vrsteArtikala = VrstaArtikala::all();

        return view('admin.artikli.izmijeni', compact('artikal', 'vrsteArtikala'));
    }

    public function azurirajArtikal(Request $request, Artikal $artikal)
    {
        $data = $request->validate([
            // Artikal
            'naziv' => 'required|string|max:70',
            'opis' => 'required|string',
            'cijena' => 'required|integer|min:0|max:10000',
            'akcijska_cijena' => 'nullable|integer|min:0|max:10000',
            'dostupna_kolicina' => 'required|integer|min:0|max:50',

            // Knjiga
            'autor' => 'required|string|max:40',
            'izdavac' => 'required|string|max:100',
            'izdanje' => 'required|string|min:4|max:4',
            'br_stranica' => 'required|string|max:4',
            'pismo' => 'required|in:Ćirilica,Latinica',

            // Vrsta Artikla
            'vrsta_artikala' => 'required|array',
            'vrsta_artikala.*' => 'exists:vrsta_artikalas,id',

            // Slika Artikla
            'slika' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Optional
        ]);

        DB::transaction(function () use ($data, $request, $artikal) {
            $artikal->update([
                'naziv' => $data['naziv'],
                'opis' => $data['opis'],
                'cijena' => $data['cijena'],
                'akcijska_cijena' => $data['akcijska_cijena'],
                'dostupna_kolicina' => $data['dostupna_kolicina'],
            ]);

            $artikal->knjiga->update([
                'autor' => $data['autor'],
                'izdavac' => $data['izdavac'],
                'izdanje' => $data['izdanje'],
                'br_stranica' => $data['br_stranica'],
                'pismo' => $data['pismo'],
            ]);

            // Azuriranje pivot tabele
            $artikal->vrsteArtikla()->sync($data['vrsta_artikala']);

            if ($request->hasFile('slika')) {
                $slikaArtikla = $request->file('slika');

                // Kreiranje naziva fajla (Create file name)
                $nazivFajla = time() . '_' . uniqid() . '.' . $slikaArtikla->getClientOriginalExtension();

                // Cuvanje fajla (Save the file) to 'storage/app/public/img'
                $slikaArtikla->storeAs('img', $nazivFajla, 'public');

                // Ako artikal vec ima sliku, stara slika se brise
                if ($artikal->artikalSlike->isNotEmpty()) {
                    $oldSlika = $artikal->artikalSlike->first();
                    Storage::disk('public')->delete('img/' . $oldSlika->naziv_fajla);
                    $oldSlika->delete();
                }

                ArtikalSlika::create([
                    'naziv_fajla' => $nazivFajla,
                    'artikal_id' => $artikal->id,
                ]);
            }
        });

        return redirect('/admin/artikli/index')->with('success', __('Knjiga uspiješno ažurirana.'));
    }

    public function svePorudzbine()
    {
        $porudzbine = Porudzbina::all();

        foreach ($porudzbine as $porudzbina) {
            $porudzbina->ukupno = formatirajCijenu($porudzbina->ukupno);
        }

        return view('admin.porudzbine.index', compact('porudzbine'));
    }

    public function prikaziPorudzbinu(Porudzbina $porudzbina)
    {
        // Ucitavanje stavki porudzbine sa povezanim artiklima (ukljucujuci soft-deleted artikle)
        $porudzbina->load('stavkePorudzbine.artikal');

        $porudzbina->ukupno = formatirajCijenu($porudzbina->ukupno);

        // Format the item prices
        foreach ($porudzbina->stavkePorudzbine as $stavka) {
            $stavka->ukupna_cijena = formatirajCijenu($stavka->ukupna_cijena);
        }

        return view('admin.porudzbine.porudzbina', compact('porudzbina'));
    }
}

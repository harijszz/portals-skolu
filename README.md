# Skolu Portāls

Latviešu skolēnu atzīmju uzskaites un mēneša stipendijas aprēķināšanas aplikācija, kas izbuilta ar Laravel 13.

## Funkcijas

- Reģistrācija, login un lietotāju datu izolācija
- Priekšmetu un atzīmju izveidošana, rediģēšana un dzēšana
- Svērto vidējo aprēķinu un What-if kalkulators
- Atlasīta mēneša vidējais, kas aprēķināts no visām datētajām atzīmēm bez svara ieskaites
- Lineāra stipendijas aprēķināšana un 12 mēnešu vēsture
- Faker demo dati: 10 skolēni, 12 mācību priekšmeti katram un atzīmes sešiem mēnešiem
- Kontroldarbu tēmas sagatavotas nākotnes AI konsultanta integrācijai

## Stipendijas diapazoni

| Mēneša vidējais | Stipendija |
| --- | ---: |
| zem 4,00 | 0 € |
| 4,00–4,99 | 16–30 € |
| 5,00–5,99 | 31–50 € |
| 6,00–7,99 | 51–80 € |
| 8,00–8,99 | 81–100 € |
| 9,00–10,00 | 101–120 € |

Summa tiek lineāri aprēķināta starp diapazona apakšējo un augšējo robežu. Vidējais vispirms tiek noapaļināts uz divām decimāldaļām, bet atzīmju svari neietekmē stipendijas aprēķinu.

## Demo piekļuve

- E-pasts: `skolens@example.com`
- Parole: `password`

## Lokālā palaišana

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
npm ci
npm run build
php artisan serve
```

Lai izveidotu vai atsvaidzinātu demo datus, izmanto:

```bash
php artisan migrate:fresh --seed
```

## Pārbaudes

```bash
composer test
./vendor/bin/pint --test
npm run build
```

## Tehnoloģijas

- Laravel 13
- PHP 8.3+
- SQLite
- FakerPHP
- Blade un Tailwind CSS
- PHPUnit un Vite

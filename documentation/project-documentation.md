# Projekt dokumentáció

## 5.1 Projektstruktúra

A projekt Laravel alkalmazásként épül fel, a megszokott könyvtárstruktúrával.

`EpochDatabase`
- `artisan` # parancssori eszköz a Laravelhez
- `composer.json` # PHP csomagfüggőségek és autoload beállítások
- `app/` # alkalmazás fő kódja
  - `Console/` # egyéni Artisan parancsok
  - `Enums/` # felsorolt típusok és enumerációk
  - `Http/` # vezérlők, middleware, request-ek és resource-ok
    - `Controllers/`
    - `Middleware/`
    - `Requests/`
    - `Resources/`
  - `Mail/` # email osztályok
  - `Models/` # Eloquent modellek
  - `Observers/` # model observer-ek
  - `Policies/` # jogosultságpolicy-k
  - `Providers/` # szolgáltatásregisztrálók
  - `Rules/` # egyedi validációs szabályok
  - `Services/` # üzleti logikát kiszolgáló szolgáltatások
- `bootstrap/` # alkalmazás bootstrap és cache beállítások
- `config/` # Laravel és csomag konfigurációs fájlok
- `database/` # migrációk, seed-ek és egyéb adatbázis erőforrások
  - `migrations/`
  - `seeders/`
  - `factories/`
- `public/` # publikus web gyökér, ahol az `index.php` található
- `resources/` # nézetek és frontend szintű erőforrások
- `routes/` # alkalmazás útvonalfájljai
  - `api.php`
  - `web.php`
  - `auth.php`
- `storage/` # futás közbeni fájlok, cache, naplók
- `tests/` # backend tesztek
  - `Feature/`
  - `Unit/`
- `vendor/` # Composer által telepített függőségek

A backend szerkezet központi elemei:

- `app/Http/Controllers/` - API és auth vezérlők
- `app/Http/Middleware/` - egyedi middleware logika
- `app/Models/` - adatmodellek és Eloquent entitások
- `database/migrations/` - sémaváltozások és táblalétrehozások
- `routes/` - API, web és auth útvonalak
- `tests/` - PHP Unit tesztek és funkcionális tesztek

Példa Director-specifikus fájlokra:

- `app/Models/Director.php` - a Director entitás adatmodellje.
- `app/Http/Controllers/DirectorController.php` - a rendezőkhöz tartozó CRUD műveletek.
- `routes/api.php` - `Route::apiResource('directors', DirectorController::class)` a publikus és admin útvonalakhoz.

## 5.2 Útvonalkezelés

A projekt útvonalait a `routes/` könyvtár kezeli, ahol külön fájlok vannak az API, web és autentikációs végpontokhoz.

- `routes/web.php` - webes és preview route-ok.
  - `/` - egyszerű válasz a Laravel verzióval, például `{"Laravel": "11.x.x"}`.
  - `/mail-preview/booking/{booking}` - emailpreview a foglalási visszaigazoláshoz, amely betölti a foglalás adatait és megjeleníti a `BookingConfirmation` mail osztályt.
- `routes/api.php` - az API végepontok deklarációja.
  - Publikus erőforrások lekérése (`index`, `show`) middleware nélkül, hogy bárki hozzáférhessen.
  - Felhasználói és admin műveletek `sanctum.cookie` middleware csoportban, biztosítva az autentikációt.
- `routes/auth.php` - autentikációhoz és jelszókezeléshez szükséges route-ok.
  - Regisztráció, bejelentkezés, jelszóemlékeztető, jelszó-visszaállítás, email ellenőrzés, kijelentkezés.
  - A `guest` middleware biztosítja, hogy csak vendég felhasználók férhessenek hozzá ezekhez.

Az `api.php` fájl `sanctum.cookie` middleware-t használ a védett végpontoknál, és külön admin prefix is létre van hozva:

- `admin/*` - adminisztrációs CRUD végpontok, ahol a felhasználók teljes hozzáféréssel rendelkeznek az erőforrásokhoz.

Példa egy route csoportra:

```php
Route::middleware('sanctum.cookie')->group(function() {
    Route::get('/user/me', [UserController::class, 'me']);
    Route::post('/movies/{movie}/comments', [CommentController::class, 'store']);
});
```

Director-specifikus útvonalak:

```php
Route::apiResource('directors', DirectorController::class)->only(['index', 'show']);
Route::middleware('sanctum.cookie')->prefix('admin')->group(function () {
    Route::apiResource('directors', DirectorController::class)->except(['index', 'show']);
});
```

Ez biztosítja, hogy a rendezők listája és részletei publikusak legyenek, míg az admin CRUD műveletek csak hitelesített felhasználók számára érhetőek el.

## 5.3 Kontrollerek

A projektben a vezérlők az `app/Http/Controllers/` könyvtárban találhatók, és RESTful API tervezést követnek.

Főbb kontrollerek:

- `UserController` - felhasználói profil kezelése, saját adatok módosítása, szerepkör frissítése adminisztrátorok számára.
- `BookingController` - foglalások zárolása (lockSeats), jegyek frissítése (updateSeats), fizetés (checkout), törlés (cancel).
- `CommentController` - filmkommentek létrehozása és törlése, felhasználói interakciók kezelése.
- `ProfileWatchlistController` - figyelőlista elemek hozzáadása és eltávolítása.
- `MovieController` - filmek lekérése és hasonló filmek keresése algoritmus alapján.
- `ScreeningController` - vetítések listázása és részleteinek megjelenítése.
- `SeatMapController` - vetítéshez tartozó helyek térképének listázása, foglalás előtti ellenőrzéshez.
- `Auth` vezérlők (`LoginController`, `RegisteredUserController`, `PasswordResetLinkController`, `NewPasswordController`, `VerifyEmailController`) - teljes autentikációs folyamat kezelése, beleértve a token alapú bejelentkezést és email ellenőrzést.

A controller felépítése RESTful: `apiResource` és `resource` útvonalak kezelik a CRUD műveleteket. Például a `MovieController` támogatja az `index`, `show`, `store`, `update`, `destroy` metódusokat.

Példa egy controller metódusra:

```php
public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
    ]);

    $movie = Movie::create($validated);
    return response()->json($movie, 201);
}
```

Ez biztosítja az adatok validálását és a megfelelő HTTP válaszokat.

Director példa egy controllerben:

```php
public function update(Request $request, Director $director)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'bio' => 'nullable|string',
    ]);

    $director->update($validated);
    return response()->json($director);
}
```

Ez a módszer lehetővé teszi a rendezők frissítését és a változtatások visszaküldését JSON formátumban.

## 5.4 Modellek és adatbázis kezelés

Az `app/Models/` könyvtárban találhatók az alkalmazás entitásai, amelyek Eloquent ORM-et használnak az adatbázis műveletekhez.

Fontos modellek:

- `User`, `Profile`, `Password` - felhasználói entitások, ahol a `User` a fő autentikációs modell, `Profile` a kiegészítő adatokhoz, `Password` a jelszó-visszaállításhoz.
- `Movie`, `Genre`, `Director`, `CastMember`, `Era`, `Language`, `Media` - médiatartalom és filmadatok, kapcsolatokkal (pl. `Movie` belongsToMany `Genre`).
- `Cinema`, `Auditorium`, `Seat`, `SeatType`, `Screening`, `ScreeningType` - mozis vetítési struktúra, hierarchikus kapcsolatokkal.
- `Booking`, `BookingSeat`, `BookingTicket`, `BookingProduct`, `Payment` - foglalási és fizetési modell, összetett kapcsolatokkal a jegyek és termékek kezeléséhez.
- `Comment`, `Achievement`, `ProfileAchievement`, `ProfileWatchlist` - felhasználói interakciók és jutalmak, observer-ekkel (pl. `CommentObserver`).

Az adatbázis kezelése Eloquent ORM-en keresztül történik, amely támogatja a kapcsolatokat, query builder-t és automatikus timestamp-eket. A `config/auth.php` beállításai szerint a `users` provider az `App\Models\User` modellt használja.

A `AppServiceProvider` inicializálja az email jelszó-visszaállítási URL-t és regisztrálja a `CommentObserver`-t, amely automatikusan kezeli a kommentekhez kapcsolódó eseményeket.

Példa egy modell kapcsolatra:

```php
class Movie extends Model
{
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'movie_genres');
    }
}
```

Ez lehetővé teszi a filmek és műfajok közötti sok-sok kapcsolat kezelését.

Director modell példa:

```php
class Director extends Model
{
    public function movies()
    {
        return $this->hasMany(Movie::class);
    }
}
```

A `Movie` modell pedig így kapcsolódhat:

```php
class Movie extends Model
{
    public function director()
    {
        return $this->belongsTo(Director::class);
    }
}
```

Ez egyértelműen definiálja, hogy egy rendező több filmet készíthet, és egy film egy rendezőhöz tartozik.

## 5.5 Migrációk

A `database/migrations/` könyvtárban találhatók a táblák létrehozását biztosító migrációk, amelyek verziókövetett adatbázis sémaváltozásokat biztosítanak.

A projekt migrációi lefedik:

- Általános táblák: `users`, `profiles`, `passwords`, `personal_access_tokens` - alapvető felhasználói és autentikációs adatok.
- Filmadatok: `eras`, `cinemas`, `auditoria`, `genres`, `languages`, `directors`, `cast_members`, `movies`, `movie_genres`, `movie_casts` - teljes filmadatbázis struktúra.
- Vetítések és helyek: `screening_types`, `screenings`, `seat_types`, `seats` - dinamikus vetítési és helyfoglalási rendszer.
- Foglalások: `bookings`, `booking_seats`, `booking_tickets`, `booking_products`, `payments` - összetett foglalási folyamat.
- Felhasználói aktivitás: `profile_watchlists`, `comments`, `news`, `achievements`, `profile_achievements`, `media` - közösségi és jutalmazási funkciók.

Migráció futtatása:

```bash
php artisan migrate
```

Az adatbázis sémát a Laravel migrációs rendszer kezeli, így a változások verzionálva és reprodukálhatóan telepíthetők. Például egy migráció hozzáadhatja a foreign key-eket:

```php
Schema::table('movies', function (Blueprint $table) {
    $table->foreignId('era_id')->constrained('eras');
});
```

Ez biztosítja az adatok integritását és kapcsolatát.

Director migráció példa:

```php
Schema::create('directors', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('bio')->nullable();
    $table->timestamps();
});

Schema::table('movies', function (Blueprint $table) {
    $table->foreignId('director_id')->nullable()->constrained('directors');
});
```

Ez azt jelenti, hogy a rendezőket külön táblában tároljuk, és a `movies` tábla referenciát tartalmaz a `directors` tábla felé.

## 5.6 Middleware és jogosultságkezelés

A projekt több middleware-t és jogosultságellenőrzést használ a biztonságos API hozzáférés és felhasználói jogosultságok kezeléséhez.

### Custom middleware-ek

- `App\Http\Middleware\UseCookieTokenForSanctum`
  - Ez a middleware biztosítja a Laravel Sanctum autentikációt cookie-k vagy Bearer tokenek használatával.
  - Először ellenőrzi a `Bearer` token jelenlétét a kérés fejlécében. Ha nincs, akkor az `auth_token` cookie-t keresi.
  - Ha egyik sem található vagy érvénytelen, 401-es "Unauthenticated" hibát ad vissza JSON formátumban.
  - Ha érvényes token található, lekéri a hozzá tartozó felhasználót és beállítja a kérés felhasználóját, lehetővé téve a védett erőforrások elérését.

- `App\Http\Middleware\EnsureEmailIsVerified`
  - Ellenőrzi, hogy a bejelentkezett felhasználó email címe igazolt-e.
  - Ha a felhasználó nem igazolta az emailjét, 409-es "Your email address is not verified." hibát ad vissza.
  - Ez biztosítja, hogy csak igazolt felhasználók férhessenek hozzá bizonyos funkciókhoz.

### Route middleware használata

- `sanctum.cookie` – Ez a Laravel Sanctum middleware védi az API hívásokat. Cookie-kat használ az autentikációhoz, amelyeket a frontend automatikusan küld minden kéréssel. Ez biztosítja a CSRF védelmet és az állapotmentes autentikációt SPA-k számára.
- `guest` – Megakadályozza, hogy bejelentkezett felhasználók hozzáférjenek vendég csak oldalakhoz, mint például a bejelentkezés vagy regisztráció oldalak.
- `throttle` és `signed` – Az email ellenőrzés és értesítések biztonságához használják. A `throttle:6,1` például 6 percenként 1 kérést engedélyez, megakadályozva az abuse-ot. A `signed` middleware ellenőrzi, hogy az URL aláírt-e, biztosítva az integritást.

### Cookie-k működése

A projekt Laravel Sanctum-ot használ, amely cookie-alapú autentikációt biztosít SPA-k számára. A cookie-k automatikusan küldésre kerülnek minden kéréssel a frontendről, így nincs szükség Bearer tokenek manuális kezelésére. Ez különösen hasznos CORS környezetben, ahol a cookie-k biztosítják a biztonságos kommunikációt. A `sanctum.cookie` middleware ellenőrzi ezeket a cookie-kat és autentikálja a felhasználót. Ha a cookie hiányzik vagy érvénytelen, a middleware 401-es hibát ad vissza.

### Throttle működése

A throttle middleware korlátozza a kérések számát egy adott időablakban, megakadályozva a brute force támadásokat és az erőforrások túlzott használatát. Például a `throttle:6,1` beállítás 6 percenként maximum 1 kérést engedélyez egy adott végponthoz. Ez különösen fontos az érzékeny műveleteknél, mint a jelszó-visszaállítás vagy email ellenőrzés. Ha a limitet meghaladja, a middleware 429-es "Too Many Requests" hibát ad vissza. A throttle konfigurálható a `config/cache.php` és `config/queue.php` fájlokban.

### Jogosultságkezelés (Policies)

A `app/Policies/` könyvtár különféle jogosultságpolicy-kat tartalmaz, amelyek a modell alapú jogosultságellenőrzést támogatják. Ezek a policy-k meghatározzák, hogy egy adott felhasználó milyen műveleteket hajthat végre egy modellen (például olvasás, írás, frissítés, törlés). A policy-k automatikusan használhatók a vezérlőkben vagy a Blade sablonokban a `@can` direktívával. Ez biztosítja a finom szemcsés jogosultságkezelést, ahol például csak az admin felhasználók módosíthatják bizonyos erőforrásokat.

Director jogosultság példa:

```php
class DirectorPolicy
{
    public function update(User $user, Director $director)
    {
        return $user->role === 'admin';
    }
}
```

Ez lehetővé teszi, hogy csak adminok szerkeszthessék a rendezők adatait, míg a publikus `show` és `index` végpontok bárki számára elérhetők maradnak.

## 5.7 API végpontok

A `routes/api.php` alapján a projekt az alábbi logikai végpontokat kínálja, csoportosítva funkcionalitás szerint.

Publikus lekérdezések (middleware nélkül):

- `GET /api/eras`, `GET /api/eras/{id}` - korszakok listázása és részletei.
- `GET /api/cinemas`, `GET /api/cinemas/{id}` - mozik és részleteik.
- `GET /api/auditoriums`, `GET /api/auditoriums/{id}` - termek és kapacitásuk.
- `GET /api/news`, `GET /api/news/{id}` - hírek és frissítések.
- `GET /api/movies`, `GET /api/movies/{id}` - filmkatalógus és részletek.
- `GET /api/movies/{id}/similar` - hasonló filmek ajánlása algoritmus alapján.
- `GET /api/screenings`, `GET /api/screenings/{id}` - aktuális vetítések.
- `GET /api/genres`, `GET /api/genres/{id}` - műfajok.
- `GET /api/languages`, `GET /api/languages/{id}` - nyelvek.
- `GET /api/castMembers`, `GET /api/castMembers/{id}` - színészek és stáb.
- `GET /api/directors`, `GET /api/directors/{id}` - rendezők.
- `GET /api/achievements`, `GET /api/achievements/{id}` - jutalmak.
- `GET /api/ticketTypes`, `GET /api/ticketTypes/{id}` - jegytípusok és árak.
- `GET /api/productTypes`, `GET /api/productTypes/{id}` - termékkategóriák.
- `GET /api/screeningTypes`, `GET /api/screeningTypes/{id}` - vetítési típusok (2D, 3D, stb.).
- `GET /api/seatTypes`, `GET /api/seatTypes/{id}` - helytípusok.
- `GET /api/seats`, `GET /api/seats/{id}` - egyedi helyek.
- `GET /api/screenings/{screening}/seats` - vetítéshez tartozó helytérkép.

Felhasználói műveletek (`sanctum.cookie`):

- `GET /api/user/me` - saját profil adatok.
- `PATCH /api/user/me` és `POST /api/user/me` (profil frissítés, fájlfeltöltés támogatás) - profil módosítása.
- `POST /api/movies/{movie}/comments` - komment hozzáadása.
- `DELETE /api/movies/{movie}/comments` - komment törlése.
- `POST /api/movies/{movie}/watchlist` - film hozzáadása a figyelőlistához.
- `DELETE /api/profileWatchlists/{profileWatchlist}` - eltávolítás a figyelőlistából.
- `POST /api/bookings/lock` - helyek zárolása foglaláshoz.
- `PUT /api/bookings/{booking}/seats` - jegyek frissítése.
- `POST /api/bookings/{booking}/checkout` - fizetés és véglegesítés.
- `POST /api/bookings/{booking}/cancel` - foglalás törlése.

Admin műveletek (`sanctum.cookie` prefix `admin`):

- Teljes CRUD erőforrások: `eras`, `cinemas`, `auditoriums`, `news`, `movies`, `screenings`, `genres`, `languages`, `castMembers`, `directors`, `users`, `achievements`, `ticketTypes`, `bookingTickets`, `productTypes`, `bookingProducts`, `screeningTypes`, `seatTypes`, `seats`, `comments`, `bookings`, `profileWatchlists`.
- `PATCH /api/admin/user/{user}/role` - felhasználói szerepkör módosítása.

Autentikációs végpontok (`routes/auth.php`):

- `POST /register` - új felhasználó regisztráció.
- `POST /login` - bejelentkezés és token visszaadása.
- `POST /forgot-password` - jelszó-visszaállítás link küldése.
- `POST /reset-password` - új jelszó beállítása.
- `GET /verify-email/{id}/{hash}` - email ellenőrzés aláírt linkkel.
- `POST /email/verification-notification` - új ellenőrző email küldése.
- `POST /logout` - kijelentkezés és token érvénytelenítése.

Példa egy API válaszra:

```json
{
  "data": {
    "id": 1,
    "title": "Sample Movie",
    "description": "A great movie",
    "genres": ["Action", "Drama"]
  }
}
```

Ez biztosítja a konzisztens JSON válaszokat.

Director példa API válaszra:

```json
{
  "data": {
    "id": 5,
    "name": "Christopher Nolan",
    "bio": "British-American filmmaker known for complex narratives.",
    "movies": [
      {"id": 12, "title": "Inception"},
      {"id": 24, "title": "Interstellar"}
    ]
  }
}
```

Ez a szerkezet jól tükrözi a rendezők és kapcsolódó filmek közötti kapcsolatot.

## 5.8 Hibakezelés

A projekt alapvetően a Laravel hibakezelőjére támaszkodik, amely strukturált módon kezeli a kivételeket és hibákat.

Kiemelt hibakezelési elemek:

- `UseCookieTokenForSanctum` middleware 401-es hibát ad, ha az autentikációs token hiányzik vagy érvénytelen.
- `EnsureEmailIsVerified` middleware 409-es hibát ad, ha a felhasználó emailje nincs ellenőrizve.
- `routes/auth.php`-ban lévő `throttle` middleware megvédi a jelszó- és email műveleteket a túlzott kérésektől.
- A `AppServiceProvider` a jelszó-visszaállítás URL-jét is kezeli, így az erről szóló hibák rendezett módon továbbíthatók a frontend felé.

A `app/Exceptions/Handler.php` standard Laravel kezelést biztosít az API JSON hibaválaszaihoz, például:

```php
public function render($request, Throwable $exception)
{
    if ($request->is('api/*')) {
        return response()->json(['error' => 'Something went wrong'], 500);
    }
    return parent::render($request, $exception);
}
```

Ez biztosítja, hogy az API hívások konzisztens JSON hibaválaszokat kapjanak, míg a web kérések hagyományos HTML oldalakat.

Példa egy validációs hibára:

```json
{
  "message": "The title field is required.",
  "errors": {
    "title": ["The title field is required."]
  }
}
```

A hibakezelés támogatja a különböző kivétel típusokat, mint `ValidationException`, `AuthenticationException`, stb.

Director-specifikus hibapélda:

- Ha egy nem admin felhasználó próbál módosítani egy rendezőt, a policy általában 403-as `Forbidden` választ eredményez.
- Ha egy nem létező rendezőhöz kérjük a részleteket, 404-es `Not Found` hibát ad vissza a rendszer.
- Ha a `sanctum.cookie` auth hiányzik, a `DirectorController`-ben történő admin művelet 401-es `Unauthenticated` hibát kap.

## 5.9 Biztonsági megoldások

A projekt több biztonsági réteget is használ az adatok és felhasználók védelméhez.

- Laravel Sanctum token alapú autentikáció cookie és bearer token támogatással, amely CSRF védelmet biztosít SPA-k számára.
- `sanctum.cookie` middleware a védett végpontoknál, automatikus cookie kezelés.
- `guest` middleware az auth útvonalaknál, hogy a bejelentkezett felhasználók ne férhessenek hozzá vendég csak műveletekhez.
- Email-ellenőrzés `signed` és `throttle:6,1` middleware-rel, aláírt URL-ekkel és korlátozott kérésekkel.
- Jelszó-visszaállítás `ResetPassword::createUrlUsing()` segítségével frontend URL generálása biztonságos formában.
- Throttle middleware az érzékeny végpontokhoz (`password.email`, email értesítések, verifikáció), megakadályozva az abuse-ot.
- Policy fájlok az `app/Policies/` mappában a modellekhez kapcsolódó jogosultságokhoz, finom szemcsés hozzáférés-ellenőrzéssel.

További biztonsági elemek:

- Adatbázis migrációk foreign key-ekkel biztosítják az adatok integritását.
- Validációs szabályok a `Requests` osztályokban megakadályozzák a rossz adatok bevitelét.
- HTTPS kényszerítés és secure cookie-k használata production környezetben.
- Rate limiting a `config/cache.php`-ban konfigurálva.

Példa egy policy-ra:

```php
class MoviePolicy
{
    public function update(User $user, Movie $movie)
    {
        return $user->role === 'admin';
    }
}
```

Ez biztosítja, hogy csak adminok módosíthassák a filmeket.

Director biztonsági példa:

- `Route::middleware(['sanctum.cookie', 'can:update,director'])->patch('/api/admin/directors/{director}', [DirectorController::class, 'update']);`
- Ez a kombináció a cookie-alapú autentikációt és a policy alapú jogosultság-ellenőrzést egyszerre használja.
- A `DirectorController` admin műveletei így csak hitelesített és megfelelő szerepkörű felhasználók számára érhetőek el.

### Szerepkörök (Roles) konkrét definiálása és Policy osztályok működésének részletezése

#### Szerepkörök (Roles) definiálása

A `User` modell `role` mezője egy karakterlánc, amely a felhasználó jogosultságait határozza meg. A projekt jelenleg az alábbi szerepköröket támogatja:

- `admin` - adminisztrátor, teljes hozzáféréssel rendelkezik az összes erőforráshoz, módosíthatja a filmeket, felhasználókat, foglalásokat.
- `user` - hétköznapi felhasználó, képes hirdetéseket böngészni, megjegyzéseket írni, foglalásokat létrehozni.
- Egyéb speciális szerepkörök lehetnek (pl. `moderator`, `staff`), amelyeket a projekt igénye szerint lehet bővíteni.

A szerepkör ellenőrzése minden engedélyezési logikában végigmegy:

```php
$user->role === 'admin'
```

#### Policy osztályok működésének részletezése

A Policy osztályok az `app/Policies/` mappában találhatók, és minden modellhez egy vagy több policy létezik. Ezek az osztályok szintetikus metódusokat tartalmaznak, amelyek meghatározzák, hogy egy adott felhasználó milyen akciókat hajt végre:

**Alapvető Policy metódusok:**

- `view()` - olvasási jogosultság ellenőrzése (GET kérések).
- `create()` - új erőforrás létrehozásának jogosultsága.
- `update()` - meglévő erőforrás módosításának jogosultsága.
- `delete()` - erőforrás törlésének jogosultsága.
- `restore()` - soft deleted erőforrás visszaállításának jogosultsága.
- `forceDelete()` - véglegesen törlött erőforrás visszaállításának jogosultsága.

**Policy metódusok szignatúrája:**

```php
public function update(User $user, Movie $movie): bool
{
    return $user->role === 'admin';
}
```

Ahogy látható, a metódusok paraméterként kapják az aktuális felhasználót (`$user`) és az érintett erőforrást (legyen az `$movie`, `$director`, stb.). Az `authorize()` gate-eket és a route middleware `can` direktívét használva a Laravel automatikusan csekkolja ezeket a policy metódusokat.

**Policy használata kontrollerben és route-ban:**

Route példa:

```php
Route::middleware('sanctum.cookie')->patch('/admin/movies/{movie}', function (Movie $movie) {
    $this->authorize('update', $movie); // Egyéni authorization
    // Frissítés logika
});
```

Vagy route middleware-el:

```php
Route::middleware(['sanctum.cookie', 'can:update,movie'])->patch('/admin/movies/{movie}', [MovieController::class, 'update']);
```

**Policy fejlesztői folyamat:**

1. Policy osztály létrehozása: `php artisan make:policy MoviePolicy --model=Movie`
2. Jogosultság metódusok implementálása (pl. `update()`, `delete()`)
3. AppServiceProvider-ben a policy regisztrálása (opcionális, Laravel automatikusan felfedezi)
4. Controller vagy route-ban az `authorize()` hívása vagy `can` middleware használata

**Director Policy teljes példa:**

```php
class DirectorPolicy
{
    public function view(User $user, Director $director): bool
    {
        return true; // Mindenki láthatja a rendezőket
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Director $director): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Director $director): bool
    {
        return $user->role === 'admin';
    }
}
```

Ez biztosítja, hogy a rendezők listája és részletei publikusak, de csak adminok hozhatnak létre, módosíthatnak vagy törölhetnek rendezőket.

**Policy és adatok kombinálása:**

Szerepeknél gyakran kombináljuk az adatvezérelt jogosultságokat is:

```php
public function update(User $user, Comment $comment): bool
{
    return $user->id === $comment->user_id || $user->role === 'admin';
}
```

Ez a policy lehetővé teszi, hogy a felhasználó a saját megjegyzéseit módosítsa, vagy az admin bármilyen megjegyzést.

## 5.10 Backend tesztelés

A tesztek a `tests/` könyvtárban találhatók, PHPUnit-ot használva a Laravel tesztelési keretrendszerrel.

- `tests/Feature/` - funkcionális tesztek, amelyek teljes kéréseket és válaszokat tesztelnek, például API végpontokat.
- `tests/Unit/` - unit tesztek, amelyek egyedi osztályokat vagy metódusokat tesztelnek izoláltan.
- `tests/TestCase.php` - alapvető tesztelési konfiguráció, amely beállítja a Laravel környezetet.

A projekt jelenlegi állapotában alap példatesztek állnak rendelkezésre, így a tesztkörnyezet gyorsan elindítható.

Teszt futtatása:

```bash
vendor/bin/phpunit
```

vagy

```bash
php artisan test
```

Példa egy egyszerű feature tesztre:

```php
class ExampleTest extends TestCase
{
    public function test_the_application_returns_a_successful_response()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
```

A tesztek támogatják a database seeding-et, factory-kat és mock objektumokat. A `phpunit.xml` konfigurálja a tesztkörnyezetet, beleértve a memóriakorlátokat és adatbázis kapcsolatokat.

A tesztelés biztosítja a kód minőségét és megakadályozza a regressziókat.

Director endpoint teszt példa:

```php
class DirectorApiTest extends TestCase
{
    public function test_can_get_director_list()
    {
        Director::factory()->count(3)->create();

        $response = $this->getJson('/api/directors');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }

    public function test_admin_can_update_director()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $director = Director::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')
                         ->patchJson("/api/admin/directors/{$director->id}", [
                             'name' => 'Updated Name',
                         ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Updated Name']);
    }
}
```

Ez a példa bemutatja, hogyan lehet automatizáltan ellenőrizni a rendezők listázását és admin frissítését egy Laravellel működő API-ban.

## 6.1 MovieCast és MovieGenre modellek magyarázata

### MovieGenre modell

A MovieGenre modell a filmek és műfajok közötti kapcsolatot reprezentálja, amelyet a felhasználók a filmkatalógus böngészésekor használnak. A modell célja, hogy egy film több műfajhoz tartozhasson, és egy műfaj több filmhez kapcsolódhasson, biztosítva a rugalmas kategorizálást és kereshetőséget. Ez egy pivot tábla a Movie és a Genre modellek közötti kapcsolat kezelésére.

**Attribútumok:**
- `movie_id`: A film azonosítója
- `genre_id`: A műfaj azonosítója

**Objektumkapcsolatok:**
- Egy a többhöz kapcsolat a Movie modellel (belongsTo)
- Egy a többhöz kapcsolat a Genre modellel (belongsTo)

Ezen struktúra célja, hogy rugalmasan kezelje a filmek műfaji besorolását, biztosítsa a keresési funkciók hatékonyságát és támogassa a felhasználói preferenciák alapján történő ajánlásokat.

### MovieCast modell

A MovieCast modell a filmek és színészek/stábtagok közötti kapcsolatot reprezentálja, amely a film részleteinek megjelenítésénél használatos. A modell célja, hogy egy film több színészhez vagy stábtaghoz kapcsolódhasson különböző szerepekkel, és egy színész több filmben is szerepelhessen. Ez egy pivot tábla a Movie és a CastMember modellek közötti kapcsolat kezelésére, kiegészítve szerep információval.

**Attribútumok:**
- `movie_id`: A film azonosítója
- `cast_member_id`: A színész/stábtag azonosítója
- `role`: A szereplő szerepe vagy pozíciója (pl. "Director", "Actor", "Producer")

**Objektumkapcsolatok:**
- Egy a többhöz kapcsolat a Movie modellel (belongsTo)
- Egy a többhöz kapcsolat a CastMember modellel (belongsTo)

Ezen struktúra célja, hogy részletesen nyilvántartsa a filmek szereplőit és stábját, támogassa a casting információk megjelenítését és biztosítsa a keresési funkciók bővíthetőségét.

## 6.2 Token lejárati idő, visszavonási mechanizmus és logout folyamat

### Token lejárati idő

A Laravel Sanctum tokenek alapértelmezett lejárati ideje nincs beállítva, ami azt jelenti, hogy a tokenek végtelenül érvényesek maradnak, amíg manuálisan nem vonják vissza őket. Ez azonban konfigurálható a `config/sanctum.php` fájlban az `expiration` mezővel, ahol percekben adható meg a lejárat (pl. 525600 perc egy évre). Ha egy token lejár, automatikusan érvénytelen lesz, és a felhasználónak újra be kell jelentkeznie. A lejárat ellenőrzése minden kérésnél történik a middleware-ben.

### Visszavonási mechanizmus

A tokenek visszavonása a `PersonalAccessToken` modell `delete()` metódusával történik. Ez lehet manuális (pl. felhasználó kijelentkezésekor) vagy automatikus (lejáratkor). A visszavonás azonnali, és a token többé nem használható autentikációra. Például egy adminisztrátor visszavonhatja egy felhasználó összes tokenjét a biztonság érdekében.

### Logout folyamat

A logout folyamat során a frontend küld egy `POST /logout` kérést, amely a `LoginController::destroy()` metódust hívja meg. Ez a metódus törli az aktuális access tokent a `currentAccessToken()->delete()` hívással. A válasz után a frontendnek el kell távolítania a cookie-kat vagy tokent a helyi tárolóból. Ez biztosítja, hogy a token ne legyen újrahasználható, és a felhasználó biztonságosan kijelentkezhessen.

## 6.3 Részletes elemzés a státuszkódok használatáról

A projekt HTTP státuszkódokat használ a válaszok konzisztens és szabványos jelzésére. Ezek az RFC 7231 és kapcsolódó specifikációk alapján vannak implementálva.

- **200 OK**: Sikeres művelet, például GET kérések válaszai (pl. film lista lekérése).
- **201 Created**: Új erőforrás létrehozása, például új film vagy foglalás hozzáadásakor.
- **401 Unauthorized**: Autentikációs hiba, amikor hiányzik vagy érvénytelen a token (pl. `UseCookieTokenForSanctum` middleware).
- **403 Forbidden**: Jogosultsági hiba, amikor a felhasználónak nincs joga a művelethez (pl. policy visszautasítás).
- **404 Not Found**: Az erőforrás nem található, például nem létező film vagy felhasználó lekérésekor.
- **409 Conflict**: Ütközés, például nem igazolt email cím esetén (`EnsureEmailIsVerified`).
- **422 Unprocessable Entity**: Validációs hiba, amikor a bemenet nem felel meg a szabályoknak.
- **429 Too Many Requests**: Throttle korlátozás túllépése, például túl sok jelszó-visszaállítási kérés.
- **500 Internal Server Error**: Szerver oldali hiba, általános kivételkezeléskor.

Ezek a kódok biztosítják a frontend számára a pontos hibaazonosítást és megfelelő felhasználói visszajelzést.

## 6.4 Részletes bemutatást a service rétegről

A `app/Services/` könyvtár tartalmazza az üzleti logika kiszolgáló szolgáltatásokat, amelyek elválasztják a vezérlőktől a komplex műveleteket. Ezek az osztályok felelősek a külső API hívásokért, email küldésért, adatfeldolgozásért és egyéb nem közvetlenül adatbázis műveletekért.

Például egy `EmailService` osztály kezelheti a foglalási visszaigazoló emaileket, míg egy `PaymentService` integrálhatja a fizetési gateway-eket. A szolgáltatások dependency injection-nel kerülnek a vezérlőkbe, biztosítva a tesztelhetőséget és a modularitást. Ez a réteg csökkenti a vezérlők méretét és javítja a kód újrafelhasználhatóságát.

### MediaService részletes bemutatása

A `MediaService` a media tartalom kezelését végzi a rendszerben. Feladata lehet a médiatípusok validálása, a feltöltési URL-ek előkészítése, a képek és videók metaadatainak feldolgozása, valamint a média entitások kapcsolódó modellekhez történő csatolása. Egy `MediaService` például az alábbi műveleteket végezheti:

- fájlok mentése a tárolóba (`storage/app/public` vagy külső CDN)
- média metaadatok frissítése (`type`, `url`, `alt_text`, `size`)
- média objektumok kapcsolása filmekhez, hírekhez vagy profilokhoz
- különböző média típusokhoz tartozó validációk és transzformációk végrehajtása

Példa `MediaService` metódusokra:

```php
public function attachMediaToMovie(Movie $movie, array $mediaData)
{
    foreach ($mediaData as $item) {
        $media = Media::create([
            'type' => $item['type'],
            'url' => $item['url'],
            'alt_text' => $item['alt_text'] ?? null,
        ]);

        $movie->media()->save($media);
    }

    return $movie->load('media');
}
```

Ez a szolgáltatás lehetővé teszi, hogy a média kezelés logikája ne a controllerben legyen, hanem egy egyszerűen tesztelhető, újrafelhasználható rétegben.

### Tranzakciókezelés és izolációs szint

A rendszerben az adatbázis műveletek biztonsága és konzisztenciája érdekében a komplex, több lépéses folyamatok esetén explicit tranzakciókat használunk, például a `DB::transaction()` vagy `DB::beginTransaction()` / `DB::commit()` / `DB::rollBack()` hívásokkal. Ez különösen fontos olyan esetekben, mint a foglalás létrehozása, ahol egyszerre több táblát módosítunk (`bookings`, `booking_seats`, `booking_tickets`, `payments`).

A tranzakciós stratégia lényege:
- minden összetett, több táblát érintő művelet atomikusan történik,
- hiba esetén visszagördítjük az összes részleges módosítást (`rollBack`),
- siker esetén egyetlen `commit` véglegesíti a teljes folyamatot.

A projekt alapértelmezett adatbázisa MySQL, amelynél a szerveroldali izolációs szintet használjuk. A MySQL telepítésnél ez az `REPEATABLE READ`, ami garantálja, hogy egy tranzakción belül végrehajtott többszöri olvasás ugyanazt az adatot adja vissza, még ha más tranzakciók közben módosul is az adat. Ez a szint csökkenti az ún. nem ismételhető olvasások (non-repeatable reads) esélyét, de nem zárja ki teljesen a fantom olvasást.

A Laravel saját részről nem állítja be automatikusan az izolációs szintet, így az az adatbázis szerver konfigurációjától függ. Ha szükséges, explicit módon is megadható a `SET TRANSACTION ISOLATION LEVEL` utasítással egy `DB::statement()` híváson keresztül.


## 6.5 Részletes bemutatása a HttpClient életciklusnak és dependency injection konfigurációnak

### HttpClient életciklus

A Laravel HttpClient (`Illuminate\Support\Facades\Http`) teljes életciklusa a kérés előkészítésétől a válasz feldolgozásáig tart. Először a facade vagy injektált példány konfigurálása történik (headers, timeout, base URL). Ezután a kérés küldése (`get()`, `post()`, stb.), ahol a middleware-ek és retry logika alkalmazódik. A válasz megérkezésekor automatikusan JSON dekódolásra kerül, és kivételeket dobhat hálózati hibák esetén. Végül a válasz objektum használható státusz és tartalom ellenőrzésére.

### Dependency Injection konfiguráció

A HttpClient DI konfigurációja a `AppServiceProvider`-ben történik, ahol egy singleton vagy binding regisztrálható. Például:

```php
$this->app->singleton(HttpClient::class, function ($app) {
    return Http::withHeaders(['Authorization' => 'Bearer ' . config('services.api_key')]);
});
```

Ez biztosítja, hogy minden injektált HttpClient példány előre konfigurált legyen, javítva a konzisztenciát és csökkentve a boilerplate kódot.

## 6.6 Elmélés a middleware pipeline-ról

A middleware pipeline Laravelben egy láncolat, ahol minden kérés végigmegy a definiált middleware-eken. A pipeline két fázisból áll: request előtt (auth, throttle) és response után (logging, CORS). A middleware-ek sorrendje kritikus – például a `sanctum.cookie` előtt fut a `throttle`, hogy a korlátozás autentikáció nélkül is alkalmazódjon. Ez a pipeline biztosítja a moduláris feldolgozást, ahol minden middleware módosíthatja a kérést vagy választ, lehetővé téve a cross-cutting concerns (biztonság, logging) központosított kezelését.

## 6.7 Media modell és kapcsolatai

A `Media` modell a projekt multiplemediás tartalmát kezel, amely képek, videók és egyéb médiatartalmak tárolási és kezelésén felelős. Bár a modell egyszerűnek tűnhet, rendkívül fontos a filmadatok, hírek, és felhasználói profilok vizuális megjelenítésében.

### Media modell szerkezete és attribútumai

A `Media` modell az alábbi mezőket tartalmazza:

- `id` - elsődleges kulcs, egyedi azonosító (INTEGER, AUTO_INCREMENT)
- `type` - a média típusa, pl.: `image`, `video`, `thumbnail` stb. (VARCHAR, kötelező). Ez a mező adja meg, hogy milyen költségvetés vagy feldolgozási logika szükséges.
- `url` - a média teljes elérési útja vagy URL-je (VARCHAR, kötelező). Ez lehet helyi fájlútvonal (`storage/app/public/...`) vagy CDN URL.
- `alt_text` - alternatív szöveg, amely a képek sehol nem megjelenítésénél használható (TEXT, opcionális). Hasznos az akadálymentességhez (accessibility).
- `size` - a média fájl mérete bájtban (INTEGER, opcionális). Hasznos a sávszélesség nyomon követésére.
- `mediable_id` - a kapcsolódó modell azonosítója (INTEGER, Foreign Key). Ez a mező azt jelöli, hogy mely entitáshoz (film, hír, profil) tartozik a média.
- `mediable_type` - a kapcsolódó modell típusa (VARCHAR, Polymorphic relation). Ez jelöli a modell nevét (pl. "App\\Models\\Movie").
- `created_at`, `updated_at` - timestamp-ek, az adatbázis automatikusan kezeli (TIMESTAMP).

### Polymorphic kapcsolatok (Polimorf relációk)

A Media modell a **polymorphic relationships** (polimorf kapcsolatok) egy remek példája. Ez azt jelenti, hogy egy Media modell több különböző képi típusú modellhez kapcsolódhat:

- Egy Film több Média melléklettel rendelkezhet (plakátok, trailer képkockák).
- Egy Hírnek több képe lehet.
- Egy Profil avatarral és háttérképpel rendelkezhet.
- Egy kommenthez csatolható egy kép.

**Polymorphic kapcsolat lebontása:**

A `mediable_id` és `mediable_type` mezők kombinációja azonosítja az anya-entitást:

```
mediable_id = 5
mediable_type = "App\Models\Movie"
```

Ez azt jelenti, hogy az id=5 azonosítójú Film entitáshoz tartozik az adott médiafájl.

### Eloquent kapcsola definíciói

**Movie modellben a kapcsolat (egyik oldal):**

```php
class Movie extends Model
{
    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }
}
```

**News modellben a kapcsolat (másik oldal):**

```php
class News extends Model
{
    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }
}
```

**Media modellben az inverz kapcsolat (Media oldaláról):**

```php
class Media extends Model
{
    public function mediable()
    {
        return $this->morphTo();
    }
}
```

A `morphMany()` jelöli, hogy egy entitásnak több média lehet, míg a `morphTo()` az inverz kapcsolat, amely meghatározza, hogy a Media melyik entitáshoz tartozik.

### Média kezelési praktikák

**Média feltöltés és csatolás:**

```php
$movie = Movie::find(1);

$media = Media::create([
    'type' => 'image',
    'url' => 'storage/movies/movie-1-poster.jpg',
    'alt_text' => 'Inception movie poster',
    'size' => 256000,
]);

$movie->media()->save($media);
```

Ez a kódsor létrehoz egy media rékordet és azt egy filmhez csatolja.

**Média lekérdezés:**

```php
$movie = Movie::with('media')->find(1);

foreach ($movie->media as $media) {
    echo $media->url; // az egyes média URL-jét megjeleníti
}
```

**Média típus szerinti szűrés (pl. csak plakátok):**

```php
$posters = $movie->media()->where('type', 'image')->get();
```

### Relációs adatbázis tábla (migration)

```php
Schema::create('media', function (Blueprint $table) {
    $table->id();
    $table->string('type'); // 'image', 'video', 'thumbnail'
    $table->string('url');
    $table->text('alt_text')->nullable();
    $table->unsignedInteger('size')->nullable();
    $table->unsignedBigInteger('mediable_id');
    $table->string('mediable_type');
    $table->timestamps();
    
    // Index a gyors lekérdezésekhez
    $table->index(['mediable_id', 'mediable_type']);
});
```

Ez a tábla struktúra biztosítja a hatékony polimorf kapcsolatkezelést és az indexeléssel felgyorsított lekérdezéseket.

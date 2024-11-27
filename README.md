# Fuvarozó Rendszer

Egyszerű fuvarozó rendszer Laravel keretrendszerben, amely lehetővé teszi a cég adminisztrátora új munkákat hozhat létre, és azokat fuvarozókhoz rendelheti. Minden fuvarozónak egy járműve lehet, és több munkát végezhet egy időben. A feladat fő célja a backend funkcionalitás kidolgozása, a frontend lehet egyszerű, minimalista, az adminisztrációs műveletek kezelése a fő hangsúly. 

## Funkciók

### Felhasználói szerepkörök

- **Adminisztrátor**: Munkák kezelése és fuvarozókhoz rendelése
- **Fuvarozó**: Kiosztott munkák megtekintése és státusz frissítése

### Főbb entitások

#### Fuvarozó
- Név
- E-mail cím
- Jelszó

#### Jármű
- Márka
- Típus
- Rendszám
- Fuvarozó kapcsolat

#### Munka
- Kiindulási cím
- Érkezési cím
- Címzett neve
- Címzett telefonszáma
- Státusz (Kiosztva, Folyamatban, Elvégezve, Sikertelen)
- Fuvarozó kapcsolat

### Adminisztrátor funkciói
- Munkák létrehozása: Az adminisztrátor létrehozhat új fuvarfeladatokat, melyek tartalmazzák a kiindulási címet, érkezési címet, címzett nevét és elérhetőségét.
- Munkák módosítása: Munkák adatai (pl. címek, címzett) módosíthatók az adminisztrátor által.
- Munkák törlése: Adminisztrátor törölhet munkákat a rendszerből.
- Munkák fuvarozókhoz rendelése: Az adminisztrátor a létrehozott munkákat fuvarozókhoz rendelheti.

### Fuvarozó funkciói
- Munkák megtekintése: Fuvarozók megtekinthetik a nekik kiosztott munkákat, azok státuszát, valamint a címzett adatait.
- Munkák státuszának módosítása: A fuvarozó a neki kiosztott munka státuszát tudja frissíteni:
    - Kiosztva
    - Folyamatban
    - Elvégezve
    - Sikertelen (pl. a címzett nem volt elérhető)

## Technikai követelmények

### Backend követelmények (Laravel)
- Laravel keretrendszer használata
- Munkák létrehozása, módosítása, törlése
- Munkák hozzárendelése fuvarozókhoz
- Fuvarozók regisztrációja és hitelesítése (pl. Laravel Auth használatával)
- Fuvarozók státusz frissítési lehetősége a kiosztott munkák esetén

### Frontend követelmények
- Egyszerű és minimalista frontend megoldás
- Nem szükséges komplex design vagy interaktív felületek kialakítása, de a funkciók használata legyen egyértelmű (pl. React.js vagy akár egyszerű blade sablon is elegendő)

### Pontos követelmények

- Laravel 10.x verzió használata
- Adatbázis: MySQL vagy SQLite

### Opcionális funkciók
- Státusz alapú szűrés: Az adminisztrátor szűrheti a munkákat a státuszuk szerint (pl. csak folyamatban lévő munkák megjelenítése).
- Értesítések: Az adminisztrátor értesítést kaphat, ha egy munka sikertelen lett.
- API végpontok: Készíts egy egyszerű REST API-t a munkák létrehozására, módosítására, valamint a fuvarozók státuszának frissítésére.

## Telepítés és futtatás

1. **Környezeti változók beállítása**:
    - Másold az `.env.example` fájlt `.env` néven.
    - Állítsd be a megfelelő adatbázis kapcsolatokat az `.env` fájlban.

2. **Függőségek telepítése**:
    ```bash
    composer install
    npm install
    ```

3. **Adatbázis migrációk és seederek futtatása**:
    ```bash
    php artisan migrate --seed
    ```

4. **Fejlesztői szerver indítása**:
    ```bash
    php artisan serve
    npm run dev
    ```

5. **Hozzáférés a rendszerhez**:
    - Nyisd meg a böngészőt és navigálj a `http://localhost:8000` címre.

6. **Adminisztrátor bejelentkezés**:
    - Email: `admin@delivery.com`
    - Jelszó: `password`

7. **Fuvarozó bejelentkezés**:
    - Email: `sam@mail.com`
    - Jelszó: `sam12345`



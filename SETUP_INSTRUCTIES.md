# 🛒 Webwinkel Setup Instructies

## Snelstart (Aanbevolen)

### 1. MySQL Starten (XAMPP)
```
1. Open: C:\xamppp\start_xampp.exe (of XAMPP Control Panel)
2. Klik "Start" bij de MySQL module
3. Wacht tot het status "Running" toont (groen)
```

### 2. Database Initialiseren
```
Open in browser: http://localhost/webwinkel/setup.php
- Klik de knop "Database Aanmaken en Setup"
- Systeem maakt automatisch alles aan
- Je ziet bevestiging als succesvol
```

### 3. Webwinkel Gebruiken
```
- Winkel: http://localhost/webwinkel/
- Admin Panel: http://localhost/webwinkel/admin.php
  (Login: admin / admin123)
```

---

## Handmatige Setup (Alternatief)

### Via phpMyAdmin (Gemakkelijker)
```
1. Open: http://localhost/phpmyadmin/
2. Klik op "Databases" tab
3. Maak database "webwinkel" aan met charset "utf8mb4"
4. Selecteer database "webwinkel"
5. Ga naar "Import" tab
6. Upload: webwinkel/database.sql
7. Klik "Go"
```

### Via Commando Regel (Geavanceerd)
```
# MySQL aanzetten (XAMPP)
C:\xamppp\mysql\bin\mysql.exe -u root

# Commands in MySQL:
CREATE DATABASE webwinkel CHARACTER SET utf8mb4;
USE webwinkel;
SOURCE C:/xamppp/htdocs/webwinkel/database.sql;
```

---

## 🔧 Problemen Oplossen

### Probleem: "Kan geen verbinding maken (SQLSTATE[HY000])"
**Oorzaak:** MySQL server draait niet
**Oplossing:**
1. Open XAMPP Control Panel
2. Klik "Start" bij MySQL
3. Wacht tot het groen staat
4. Vernieuw de webpagina (F5)

### Probleem: "Port 3306 already in use"
**Oorzaak:** Ander programma bezet MySQL poort
**Oplossing:**
1. Sluit XAMPP af
2. Open Task Manager (Ctrl+Shift+Esc)
3. Zoek MySQL processen en beëindig ze
4. Start XAMPP opnieuw

### Probleem: Admin login werkt niet
**Oorzaak:** Session of cookies probleem
**Oplossing:**
1. Wis browser cookies/cache (Ctrl+Shift+Delete)
2. Open admin.php opnieuw in private/incognito tabblad
3. Login: admin / admin123

### Probleem: Producten van database verschijnen niet
**Oorzaak:** Database leeg of connection error
**Oplossing:**
1. Voer setup.php opnieuw uit
2. Check database via phpMyAdmin op data
3. Bekijk browser console (F12) op errors

---

## 📋 Database Structuur

### Tabel: producten
```
- id (Primary Key)
- naam (Productnaam)
- prijs (Decimal: 10,2)
- categorie (Bijv: "Hardware", "Boeken")
- type (physical/digital/discount)
- gewicht (Voor verzending)
- bestandsgrootte (Voor digitale producten)
- korting_procent (Voor discount producten)
- beschrijving (Text)
- actief (Boolean)
- aangemaakt_op / bijgewerkt_op (Timestamps)
```

### Tabel: orders
```
- id (Primary Key)
- order_id (Unieke order reference)
- voornaam/achternaam (Klant info)
- email/telefoon
- adres/postcode/plaats
- subtotaal/verzendkosten/totaal
- status (verwerkt/verzonden/bezorgd)
- aangemaakt_op (Timestamp)
```

### Tabel: order_items
```
- id (Primary Key)
- order_id (FK -> orders)
- product_id (FK -> producten)
- hoeveelheid (Quantity)
- prijs (Prijs op moment van bestelling)
```

---

## 🔑 Admin Credentials (Standaard)
```
Gebruikersnaam: admin
Wachtwoord: admin123
```

⚠️ **LET OP:** Dit is voor development! In productie:
- Wijzig het wachtwoord in config/database.php
- Voeg password hashing toe (bcrypt/password_hash)
- Zet admin.php achter authentication

---

## 🎯 Functies Beschikbaar

✅ **Winkel:**
- Producten katalogus
- Zoeken & Filteren (naam, categorie, prijs)
- Winkelwagen toevoegen/verwijderen/bijwerken
- Checkout met validation

✅ **Bestel Systeem:**
- Order bevestiging pagina
- Order geschiedenis per sessie
- Formvalidatie (email, postcode, telefoon)

✅ **Admin Panel:**
- Product management (CRUD)
- Product types (Physical/Digital/Discount)
- Bulk weergave alle producten

✅ **Database:**
- Persistent product storage
- Order tracking
- Sessie-gebaseerde orderhistory

---

## 📂 Bestandsstructuur

```
webwinkel/
├── index.php                    # Winkel homepage
├── admin.php                    # Admin panel
├── cart.php                     # Wagen pagina
├── checkout.php                 # Checkout proces
├── bevestiging.php              # Order bevestiging
├── orderhistory.php             # Order geschiedenis
├── setup.php                    # Database setup tool ⭐
├── config/
│   └── database.php             # Database config & functions
├── classes/
│   ├── Product.php              # Base product class
│   ├── PhysicalProduct.php      # Fysieke producten
│   ├── DigitalProduct.php       # Digitale producten
│   ├── DiscountProduct.php      # Korting producten
│   ├── DatabaseProduct.php      # Database producten
│   └── ShoppingCart.php         # Winkelwagen klasse
├── includes/
│   └── init.php                 # Session & classes init
├── css/
│   └── style.css                # Alle styling
└── database.sql                 # Database schema
```

---

## 🚀 Volgende Stappen

1. **Setup volltooid?** → Ga naar http://localhost/webwinkel/
2. **Producten toevoegen?** → Ga naar admin.php en login
3. **Producten bewerken?** → Admin panel "Wijzigen" knop
4. **Orders bekijken?** → Plaats test order → orderhistory.php

---

## 📞 Support Tips

- **Browser console** (F12): Controleer op JavaScript errors
- **PHP errors**: Check Apache error log in c:\xamppp\apache\logs\error.log
- **Database errors**: Controleer via phpMyAdmin
- **Connection test**: Voer setup.php uit voor diagnostic

---

Veel succes met je webwinkel! 🎉
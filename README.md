# Intrapost PHP Client

Fluent PHP client for the [Intrapost API](https://api.intrapost.nl) — create shipments, generate labels, track parcels, and manage daily mail orders for PostNL, DHL, and GLS.

## Requirements

- PHP 8.2 or higher
- [Composer](https://getcomposer.org/)
- An Intrapost API key and account number (request via [Intrapost](https://www.intrapost.nl))

## Installation

```bash
composer require budgetlens/intrapost
```

## Configuration

### Standalone PHP

```php
use Budgetlens\Intrapost\IntrapostClient;

$client = new IntrapostClient(
    apiKey: 'your-api-key',
    accountNumber: 'your-account-number',
);
```

### Laravel

The package includes auto-discovery, so the service provider and facade are registered automatically.

**Publish the config file:**

```bash
php artisan vendor:publish --tag=intrapost-config
```

**Add to your `.env`:**

```dotenv
INTRAPOST_API_KEY=your-api-key
INTRAPOST_ACCOUNT_NUMBER=your-account-number
INTRAPOST_BASE_URL=https://api.intrapost.nl
INTRAPOST_TIMEOUT=30
```

**Use via dependency injection or facade:**

```php
use Budgetlens\Intrapost\IntrapostClient;
use Budgetlens\Intrapost\Laravel\Facades\Intrapost;

// Dependency injection
public function __construct(private IntrapostClient $client) {}

// Facade
Intrapost::mailPiece()->create()-> ...
```

## Welke API gebruik je wanneer?

De Intrapost API is opgedeeld in vier resources. Hieronder staat wanneer je welke gebruikt:

### Mail Piece vs. Track & Trace

Dit is het belangrijkste onderscheid:

| | Mail Piece | Track & Trace |
|---|---|---|
| **Wat** | Brieven en ongeregistreerde poststukken | Pakketten met track & trace |
| **Wanneer** | Reguliere post versturen (brieven, kaarten, kleine poststukken tot 2kg) | Pakketten versturen die gevolgd moeten worden |
| **Tracking** | Geen volledige track & trace — alleen registratie en label | Volledige track & trace met VZ-code en trackinglink |
| **Gewicht** | In grammen (1-2000g) | In kilogrammen |
| **Producten** | Standard, FixedDays | 12 producttypen (standaard, verzekerd, aangetekend, brievenbuspakket, avondlevering, etc.) |
| **Extra opties** | Beperkt (afmetingen, projectcode) | Uitgebreid (afmetingen, afhaalpunt, customs, handtekening, leeftijdscheck, etc.) |

**Vuistregel:** Verstuur je een brief of ongeregistreerd poststuk? Gebruik `mailPiece()`. Verstuur je een pakket dat gevolgd moet worden? Gebruik `trackTrace()`.

### Overzicht van alle calls

| Methode | Gebruik wanneer... |
|---|---|
| **Mail Piece** | |
| `mailPiece()->create()` | Je een nieuw poststuk wilt registreren en een label wilt genereren |
| `mailPiece()->order()` | Je de dagelijkse bestelling van geregistreerde poststukken wilt ophalen (verzamelorder) |
| `mailPiece()->getLabel()` | Je het label van een eerder aangemaakt poststuk opnieuw wilt ophalen |
| **Track & Trace** | |
| `trackTrace()->create()` | Je een nieuw pakket wilt aanmelden met track & trace (de meest gebruikte call) |
| `trackTrace()->createMailboxParcel()` | Je specifiek een brievenbuspakket wilt aanmaken met ZPL-label |
| `trackTrace()->createLabels()` | Je labels voor meerdere zendingen in 1 bestand wilt genereren |
| `trackTrace()->getRetourLabel()` | Je een retourlabel wilt aanmaken voor een bestaande zending |
| `trackTrace()->cancel()` | Je een zending wilt annuleren (voordat deze is verwerkt) |
| `trackTrace()->search()` | Je zendingen wilt zoeken op datum, postcode, referentie, etc. |
| `trackTrace()->getFromId()` | Je zendingen wilt ophalen op basis van hun Piece ID (max. 50 per keer) |
| `trackTrace()->getFromVz()` | Je zendingen wilt ophalen op basis van hun VZ-code (max. 50 per keer) |
| **Order (Daily Mail)** | |
| `order()->createDailyMailOption1()` | Dagelijkse postbestelling aanmaken (optie 1) |
| `order()->createDailyMailOption2()` | Dagelijkse postbestelling aanmaken (optie 2) |
| `order()->createDailyMailOption3()` | Dagelijkse postbestelling aanmaken (optie 3) |
| **Utility** | |
| `utility()->lookupAddress()` | Je een adres wilt valideren/opzoeken op basis van postcode + huisnummer |
| `utility()->productCodes()` | Je wilt opvragen welke productcodes beschikbaar zijn voor jouw account |
| `utility()->pickupPointsForAddress()` | Je afhaalpunten wilt zoeken bij een adres (voor `ParcelViaPickupLocation`) |
| `utility()->dropoffPointsForInternationalAddress()` | Je inleverpunten wilt zoeken voor internationale zendingen |

### Typische flow

```
1. [Optioneel] utility()->lookupAddress()     — Valideer het afleveradres
2. [Optioneel] utility()->pickupPointsForAddress() — Zoek afhaalpunt (als klant dat wil)
3. trackTrace()->create() of mailPiece()->create() — Maak de zending aan, ontvang label
4. [Optioneel] trackTrace()->search()          — Zoek/volg zendingen
5. [Optioneel] trackTrace()->getRetourLabel()  — Genereer retourlabel indien nodig
6. [Optioneel] trackTrace()->cancel()          — Annuleer indien nodig
```

## Usage

### Mail Pieces

**Create a mail piece:**

```php
use Budgetlens\Intrapost\Enums\MailPieceProduct;

$response = $client->mailPiece()->create()
    ->product(MailPieceProduct::Standard)
    ->weight(250)
    ->reference('ORDER-001')
    ->to('John Doe', '1234AB', '10', 'NL')
    ->send();

$response->shipmentId;   // "SHP-..."
$response->vzCode;       // Track & trace code
$response->labelData;    // Base64 label PDF
```

**Shorthand product methods:**

```php
$client->mailPiece()->create()->standard()-> ...    // MailPieceProduct::Standard
$client->mailPiece()->create()->fixedDays()-> ...   // MailPieceProduct::FixedDays
```

**Order mail pieces:**

```php
$response = $client->mailPiece()->order('ORDER-001');
```

**Get a label:**

```php
$response = $client->mailPiece()->getLabel('SHP-123');
$response->labelData; // Base64 encoded label
```

### Track & Trace Parcels

**Create a shipment:**

```php
use Budgetlens\Intrapost\Enums\TrackTraceProduct;

$response = $client->trackTrace()->create()
    ->standardParcel()
    ->weight(2.5)
    ->reference('REF-001')
    ->to('Jane Doe', '5678CD', '25', 'NL')
    ->dimensions(30, 20, 15)
    ->sendMailToRecipient()
    ->send();
```

**Available product shorthand methods:**

```php
->standardParcel()    // StandardParcel
->insuredParcel()     // InsuredParcel
->registeredParcel()  // RegisteredParcel
->mailboxParcel()     // MailboxParcel
->eveningDelivery()   // StandardParcelWithEveningDelivery
->withAgeCheck()      // StandardParcelWithAgeCheck
->withSignature()     // StandardParcelSignature
->pickupLocation()    // ParcelViaPickupLocation
```

**Create a mailbox parcel:**

```php
$response = $client->trackTrace()->createMailboxParcel()
    ->weight(0.8)
    ->to('Jane Doe', '5678CD', '25', 'NL')
    ->send();
```

**Generate labels:**

```php
use Budgetlens\Intrapost\Enums\LabelFormatType;

$response = $client->trackTrace()->createLabels(
    shipmentIds: ['SHP-001', 'SHP-002'],
    format: LabelFormatType::Pdf150x100,
);
```

**Get a return label:**

```php
$response = $client->trackTrace()->getRetourLabel()
    ->shipmentId('SHP-123')
    ->send();
```

**Cancel a shipment:**

```php
$response = $client->trackTrace()->cancel('SHP-123');
```

**Search shipments:**

```php
$response = $client->trackTrace()->search()
    ->dateRange('2025-01-01', '2025-01-31')
    ->zipcode('1234AB')
    ->includeHistory()
    ->get();

foreach ($response->shipments as $shipment) {
    $shipment->shipmentId;
    $shipment->status;
    $shipment->vzCode;
}
```

**Look up by ID or VZ code:**

```php
$response = $client->trackTrace()->getFromId(['PIECE-001'], includeHistory: true);
$response = $client->trackTrace()->getFromVz(['3STEST123456789'], includeHistory: true);
```

### Pickup at a Service Point

```php
use Budgetlens\Intrapost\Enums\CarrierType;

$response = $client->trackTrace()->create()
    ->pickupLocation()
    ->pickupAt(CarrierType::PostNL, 'LOCATION-ID', 'NL')
    ->to('Jane Doe', '5678CD', '25', 'NL')
    ->send();
```

### International Shipments with Customs

```php
use Budgetlens\Intrapost\DTOs\CustomsInfo;
use Budgetlens\Intrapost\DTOs\CustomsProduct;

$customs = new CustomsInfo(
    invoiceNumber: 'INV-2025-001',
    products: [
        new CustomsProduct(
            description: 'T-shirt',
            quantity: 2,
            weight: 0.3,
            value: 29.95,
            hsCode: '6109100010',
            countryOfOrigin: 'NL',
        ),
    ],
);

$response = $client->trackTrace()->create()
    ->standardParcel()
    ->to('John Smith', '10001', '100', 'US', city: 'New York')
    ->customsInfo($customs)
    ->send();
```

### Daily Mail Orders

```php
$response = $client->order()->createDailyMailOption1()
    // ... configure order
    ->send();
```

### Utilities

**Address lookup:**

```php
$response = $client->utility()->lookupAddress('1234AB', 10);
$response->street;
$response->city;
```

**Product codes:**

```php
$response = $client->utility()->productCodes();
```

**Pickup & drop-off points:**

```php
$points = $client->utility()->pickupPointsForAddress()
    ->zipcode('1234AB')
    ->countryCode('NL')
    ->get();

$points = $client->utility()->dropoffPointsForInternationalAddress()
    ->zipcode('1234AB')
    ->countryCode('NL')
    ->get();
```

## Supported Carriers

| Carrier | Enum |
|---------|------|
| PostNL  | `CarrierType::PostNL` |
| DHL     | `CarrierType::DHL` |
| GLS     | `CarrierType::GLS` |

## Label Formats

| Format | Enum |
|--------|------|
| ZPL Zebra 150x100mm | `LabelFormatType::ZplZebra150x100` |
| PDF 150x100mm | `LabelFormatType::Pdf150x100` |

## Error Handling

The client throws specific exceptions for different error scenarios:

```php
use Budgetlens\Intrapost\Exceptions\IntrapostAuthenticationException;
use Budgetlens\Intrapost\Exceptions\IntrapostApiException;
use Budgetlens\Intrapost\Exceptions\IntrapostException;

try {
    $response = $client->trackTrace()->create()
        ->standardParcel()
        ->to('Jane Doe', '5678CD', '25', 'NL')
        ->send();
} catch (IntrapostAuthenticationException $e) {
    // Invalid API key (401/403)
} catch (IntrapostApiException $e) {
    // API returned validation errors
    $e->getErrors(); // array of error messages
} catch (IntrapostException $e) {
    // Network or other errors
}
```

## Testing

```bash
composer test
```

## Code Style

```bash
# Check for violations
composer lint

# Auto-fix
composer fix
```

## License

MIT License. See [LICENSE](LICENSE) for details.

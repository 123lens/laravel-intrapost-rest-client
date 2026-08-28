# Intrapost PHP Client

Fluent PHP client for the [Intrapost API](https://api.intrapost.nl): create shipments, generate labels, track parcels, and manage daily mail orders for PostNL, DHL, and GLS.

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

## Which API do you use when?

The Intrapost API is split into four resources. Below is a guide on when to use which one.

### Mail Piece vs. Track & Trace

This is the most important distinction:

| | Mail Piece | Track & Trace |
|---|---|---|
| **What** | Letters and unregistered mail items | Parcels with track & trace |
| **When** | Sending regular mail (letters, cards, small mail items up to 2kg) | Sending parcels that need to be tracked |
| **Tracking** | No full track & trace, only registration and a label | Full track & trace with VZ code and tracking link |
| **Weight** | In grams (1-2000g) | In kilograms |
| **Products** | Standard, FixedDays | 12 product types (standard, insured, registered, mailbox parcel, evening delivery, etc.) |
| **Extra options** | Limited (dimensions, project code) | Extensive (dimensions, pickup point, customs, signature, age check, etc.) |

**Rule of thumb:** sending a letter or unregistered mail item? Use `mailPiece()`. Sending a parcel that needs to be tracked? Use `trackTrace()`.

### Overview of all calls

| Method | Use when... |
|---|---|
| **Mail Piece** | |
| `mailPiece()->create()` | You want to register a new mail piece and generate a label |
| `mailPiece()->order()` | You want to retrieve the daily order of registered mail pieces (collective order) |
| `mailPiece()->getLabel()` | You want to retrieve the label of a previously created mail piece again |
| **Track & Trace** | |
| `trackTrace()->create()` | You want to register a new parcel with track & trace (the most used call) |
| `trackTrace()->createMailboxParcel()` | You specifically want to create a mailbox parcel with a ZPL label |
| `trackTrace()->createLabels()` | You want to generate labels for multiple shipments in a single file |
| `trackTrace()->getRetourLabel()` | You want to create a return label for an existing shipment |
| `trackTrace()->cancel()` | You want to cancel a shipment (before it has been processed) |
| `trackTrace()->search()` | You want to search shipments by date, zipcode, reference, etc. |
| `trackTrace()->getFromId()` | You want to retrieve shipments by their Piece ID (max. 50 at a time) |
| `trackTrace()->getFromVz()` | You want to retrieve shipments by their VZ code (max. 50 at a time) |
| **Order (Daily Mail)** | |
| `order()->createDailyMailOption1()` | Create a daily mail order (option 1) |
| `order()->createDailyMailOption2()` | Create a daily mail order (option 2) |
| `order()->createDailyMailOption3()` | Create a daily mail order (option 3) |
| **Utility** | |
| `utility()->lookupAddress()` | You want to validate/look up an address by zipcode + house number |
| `utility()->productCodes()` | You want to request which product codes are available for your account |
| `utility()->pickupPointsForAddress()` | You want to search pickup points near an address (for `ParcelViaPickupLocation`) |
| `utility()->dropoffPointsForInternationalAddress()` | You want to search drop-off points for international shipments |

### Typical flow

```
1. [Optional] utility()->lookupAddress()            : validate the delivery address
2. [Optional] utility()->pickupPointsForAddress()   : find a pickup point (if the customer wants one)
3. trackTrace()->create() or mailPiece()->create()  : create the shipment, receive the label
4. [Optional] trackTrace()->search()                : search/track shipments
5. [Optional] trackTrace()->getRetourLabel()        : generate a return label if needed
6. [Optional] trackTrace()->cancel()                : cancel if needed
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

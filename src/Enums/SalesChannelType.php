<?php

declare(strict_types=1);

namespace Budgetlens\Intrapost\Enums;

enum SalesChannelType: int
{
    case Magento = 0;
    case Lightspeed = 1;
    case Shopify = 2;
    case WooCommerce = 3;
    case BolCom = 4;
    case Amazon = 5;
    case Etsy = 6;
    case PrestaShop = 7;
    case OpenCart = 8;
    case BigCommerce = 9;
    case Wix = 10;
    case Squarespace = 11;
    case Ecwid = 12;
    case Volusion = 13;
    case ThreeDCart = 14;
    case Weebly = 15;
    case Miva = 16;
    case ZenCart = 17;
    case OsCommerce = 18;
    case VirtueMart = 19;
    case XCart = 20;
    case CsCart = 21;
    case Gambio = 22;
    case Oxid = 23;
    case Plentymarkets = 24;
    case JTLShop = 25;
    case Shopware = 26;
    case Modified = 27;
    case Drupal = 28;
    case SEOshop = 29;
    case CCVShop = 30;
    case Mijnwebwinkel = 31;
    case Strato = 32;
    case Jimdo = 33;
    case Twelve = 34;
    case LogiVert = 35;
    case Channable = 36;
    case ChannelEngine = 37;
    case Picqer = 38;
    case Sendcloud = 39;
    case Montaportal = 40;
    case Exact = 41;
    case Other = 42;
    case Wish = 43;
    case Blokker = 44;
    case Kaufland = 45;
    case Zalando = 46;
    case eBay = 47;
    case Marktplaats = 48;
    case Cdiscount = 49;
    case Fnac = 50;
    case Bol = 51;
    case Wehkamp = 52;
    case Fonq = 53;
    case ManoMano = 54;
}

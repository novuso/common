<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Value\Money;

use Novuso\System\Type\Enum;

/**
 * Class Currency
 *
 * @method static AED
 * @method static AFN
 * @method static ALL
 * @method static AMD
 * @method static ANG
 * @method static AOA
 * @method static ARS
 * @method static AUD
 * @method static AWG
 * @method static AZN
 * @method static BAM
 * @method static BBD
 * @method static BDT
 * @method static BGN
 * @method static BHD
 * @method static BIF
 * @method static BMD
 * @method static BND
 * @method static BOB
 * @method static BOV
 * @method static BRL
 * @method static BSD
 * @method static BTN
 * @method static BWP
 * @method static BYR
 * @method static BZD
 * @method static CAD
 * @method static CDF
 * @method static CHE
 * @method static CHF
 * @method static CHW
 * @method static CLF
 * @method static CLP
 * @method static CNY
 * @method static COP
 * @method static COU
 * @method static CRC
 * @method static CUC
 * @method static CUP
 * @method static CVE
 * @method static CZK
 * @method static DJF
 * @method static DKK
 * @method static DOP
 * @method static DZD
 * @method static EGP
 * @method static ERN
 * @method static ETB
 * @method static EUR
 * @method static FJD
 * @method static FKP
 * @method static GBP
 * @method static GEL
 * @method static GHS
 * @method static GIP
 * @method static GMD
 * @method static GNF
 * @method static GTQ
 * @method static GYD
 * @method static HKD
 * @method static HNL
 * @method static HRK
 * @method static HTG
 * @method static HUF
 * @method static IDR
 * @method static ILS
 * @method static INR
 * @method static IQD
 * @method static IRR
 * @method static ISK
 * @method static JMD
 * @method static JOD
 * @method static JPY
 * @method static KES
 * @method static KGS
 * @method static KHR
 * @method static KMF
 * @method static KPW
 * @method static KRW
 * @method static KWD
 * @method static KYD
 * @method static KZT
 * @method static LAK
 * @method static LBP
 * @method static LKR
 * @method static LRD
 * @method static LSL
 * @method static LTL
 * @method static LVL
 * @method static LYD
 * @method static MAD
 * @method static MDL
 * @method static MGA
 * @method static MKD
 * @method static MMK
 * @method static MNT
 * @method static MOP
 * @method static MRO
 * @method static MUR
 * @method static MVR
 * @method static MWK
 * @method static MXN
 * @method static MXV
 * @method static MYR
 * @method static MZN
 * @method static NAD
 * @method static NGN
 * @method static NIO
 * @method static NOK
 * @method static NPR
 * @method static NZD
 * @method static OMR
 * @method static PAB
 * @method static PEN
 * @method static PGK
 * @method static PHP
 * @method static PKR
 * @method static PLN
 * @method static PYG
 * @method static QAR
 * @method static RON
 * @method static RSD
 * @method static RUB
 * @method static RWF
 * @method static SAR
 * @method static SBD
 * @method static SCR
 * @method static SDG
 * @method static SEK
 * @method static SGD
 * @method static SHP
 * @method static SLL
 * @method static SOS
 * @method static SRD
 * @method static SSP
 * @method static STD
 * @method static SVC
 * @method static SYP
 * @method static SZL
 * @method static THB
 * @method static TJS
 * @method static TMT
 * @method static TND
 * @method static TOP
 * @method static TRY
 * @method static TTD
 * @method static TWD
 * @method static TZS
 * @method static UAH
 * @method static UGX
 * @method static USD
 * @method static USN
 * @method static UYI
 * @method static UYU
 * @method static UZS
 * @method static VEF
 * @method static VND
 * @method static VUV
 * @method static WST
 * @method static XAF
 * @method static XAG
 * @method static XAU
 * @method static XBA
 * @method static XBB
 * @method static XBC
 * @method static XBD
 * @method static XCD
 * @method static XDR
 * @method static XOF
 * @method static XPD
 * @method static XPF
 * @method static XPT
 * @method static XSU
 * @method static XTS
 * @method static XUA
 * @method static XXX
 * @method static YER
 * @method static ZAR
 * @method static ZMW
 * @method static ZWL
 */
final class Currency extends Enum
{
    /**
     * UAE Dirham
     */
    public const AED = 'AED';

    /**
     * Afghani
     */
    public const AFN = 'AFN';

    /**
     * Lek
     */
    public const ALL = 'ALL';

    /**
     * Armenian Dram
     */
    public const AMD = 'AMD';

    /**
     * Netherlands Antillean Guilder
     */
    public const ANG = 'ANG';

    /**
     * Kwanza
     */
    public const AOA = 'AOA';

    /**
     * Argentine Peso
     */
    public const ARS = 'ARS';

    /**
     * Australian Dollar
     */
    public const AUD = 'AUD';

    /**
     * Aruban Florin
     */
    public const AWG = 'AWG';

    /**
     * Azerbaijanian Manat
     */
    public const AZN = 'AZN';

    /**
     * Convertible Mark
     */
    public const BAM = 'BAM';

    /**
     * Barbados Dollar
     */
    public const BBD = 'BBD';

    /**
     * Taka
     */
    public const BDT = 'BDT';

    /**
     * Bulgarian Lev
     */
    public const BGN = 'BGN';

    /**
     * Bahraini Dinar
     */
    public const BHD = 'BHD';

    /**
     * Burundi Franc
     */
    public const BIF = 'BIF';

    /**
     * Bermudian Dollar
     */
    public const BMD = 'BMD';

    /**
     * Brunei Dollar
     */
    public const BND = 'BND';

    /**
     * Boliviano
     */
    public const BOB = 'BOB';

    /**
     * Mvdol
     */
    public const BOV = 'BOV';

    /**
     * Brazilian Real
     */
    public const BRL = 'BRL';

    /**
     * Bahamian Dollar
     */
    public const BSD = 'BSD';

    /**
     * Ngultrum
     */
    public const BTN = 'BTN';

    /**
     * Pula
     */
    public const BWP = 'BWP';

    /**
     * Belarussian Ruble
     */
    public const BYR = 'BYR';

    /**
     * Belize Dollar
     */
    public const BZD = 'BZD';

    /**
     * Canadian Dollar
     */
    public const CAD = 'CAD';

    /**
     * Congolese Franc
     */
    public const CDF = 'CDF';

    /**
     * WIR Euro
     */
    public const CHE = 'CHE';

    /**
     * Swiss Franc
     */
    public const CHF = 'CHF';

    /**
     * WIR Franc
     */
    public const CHW = 'CHW';

    /**
     * Unidad de Fomento
     */
    public const CLF = 'CLF';

    /**
     * Chilean Peso
     */
    public const CLP = 'CLP';

    /**
     * Yuan Renminbi
     */
    public const CNY = 'CNY';

    /**
     * Colombian Peso
     */
    public const COP = 'COP';

    /**
     * Unidad de Valor Real
     */
    public const COU = 'COU';

    /**
     * Costa Rican Colon
     */
    public const CRC = 'CRC';

    /**
     * Peso Convertible
     */
    public const CUC = 'CUC';

    /**
     * Cuban Peso
     */
    public const CUP = 'CUP';

    /**
     * Cabo Verde Escudo
     */
    public const CVE = 'CVE';

    /**
     * Czech Koruna
     */
    public const CZK = 'CZK';

    /**
     * Djibouti Franc
     */
    public const DJF = 'DJF';

    /**
     * Danish Krone
     */
    public const DKK = 'DKK';

    /**
     * Dominican Peso
     */
    public const DOP = 'DOP';

    /**
     * Algerian Dinar
     */
    public const DZD = 'DZD';

    /**
     * Egyptian Pound
     */
    public const EGP = 'EGP';

    /**
     * Nakfa
     */
    public const ERN = 'ERN';

    /**
     * Ethiopian Birr
     */
    public const ETB = 'ETB';

    /**
     * Euro
     */
    public const EUR = 'EUR';

    /**
     * Fiji Dollar
     */
    public const FJD = 'FJD';

    /**
     * Falkland Islands Pound
     */
    public const FKP = 'FKP';

    /**
     * Pound Sterling
     */
    public const GBP = 'GBP';

    /**
     * Lari
     */
    public const GEL = 'GEL';

    /**
     * Ghana Cedi
     */
    public const GHS = 'GHS';

    /**
     * Gibraltar Pound
     */
    public const GIP = 'GIP';

    /**
     * Dalasi
     */
    public const GMD = 'GMD';

    /**
     * Guinea Franc
     */
    public const GNF = 'GNF';

    /**
     * Quetzal
     */
    public const GTQ = 'GTQ';

    /**
     * Guyana Dollar
     */
    public const GYD = 'GYD';

    /**
     * Hong Kong Dollar
     */
    public const HKD = 'HKD';

    /**
     * Lempira
     */
    public const HNL = 'HNL';

    /**
     * Kuna
     */
    public const HRK = 'HRK';

    /**
     * Gourde
     */
    public const HTG = 'HTG';

    /**
     * Forint
     */
    public const HUF = 'HUF';

    /**
     * Rupiah
     */
    public const IDR = 'IDR';

    /**
     * New Israeli Sheqel
     */
    public const ILS = 'ILS';

    /**
     * Indian Rupee
     */
    public const INR = 'INR';

    /**
     * Iraqi Dinar
     */
    public const IQD = 'IQD';

    /**
     * Iranian Rial
     */
    public const IRR = 'IRR';

    /**
     * Iceland Krona
     */
    public const ISK = 'ISK';

    /**
     * Jamaican Dollar
     */
    public const JMD = 'JMD';

    /**
     * Jordanian Dinar
     */
    public const JOD = 'JOD';

    /**
     * Yen
     */
    public const JPY = 'JPY';

    /**
     * Kenyan Shilling
     */
    public const KES = 'KES';

    /**
     * Som
     */
    public const KGS = 'KGS';

    /**
     * Riel
     */
    public const KHR = 'KHR';

    /**
     * Comoro Franc
     */
    public const KMF = 'KMF';

    /**
     * North Korean Won
     */
    public const KPW = 'KPW';

    /**
     * Won
     */
    public const KRW = 'KRW';

    /**
     * Kuwaiti Dinar
     */
    public const KWD = 'KWD';

    /**
     * Cayman Islands Dollar
     */
    public const KYD = 'KYD';

    /**
     * Tenge
     */
    public const KZT = 'KZT';

    /**
     * Kip
     */
    public const LAK = 'LAK';

    /**
     * Lebanese Pound
     */
    public const LBP = 'LBP';

    /**
     * Sri Lanka Rupee
     */
    public const LKR = 'LKR';

    /**
     * Liberian Dollar
     */
    public const LRD = 'LRD';

    /**
     * Loti
     */
    public const LSL = 'LSL';

    /**
     * Lithuanian Litas
     */
    public const LTL = 'LTL';

    /**
     * Latvian Lats
     */
    public const LVL = 'LVL';

    /**
     * Libyan Dinar
     */
    public const LYD = 'LYD';

    /**
     * Moroccan Dirham
     */
    public const MAD = 'MAD';

    /**
     * Moldovan Leu
     */
    public const MDL = 'MDL';

    /**
     * Malagasy Ariary
     */
    public const MGA = 'MGA';

    /**
     * Denar
     */
    public const MKD = 'MKD';

    /**
     * Kyat
     */
    public const MMK = 'MMK';

    /**
     * Tugrik
     */
    public const MNT = 'MNT';

    /**
     * Pataca
     */
    public const MOP = 'MOP';

    /**
     * Ouguiya
     */
    public const MRO = 'MRO';

    /**
     * Mauritius Rupee
     */
    public const MUR = 'MUR';

    /**
     * Rufiyaa
     */
    public const MVR = 'MVR';

    /**
     * Kwacha
     */
    public const MWK = 'MWK';

    /**
     * Mexican Peso
     */
    public const MXN = 'MXN';

    /**
     * Mexican Unidad de Inversion (UDI)
     */
    public const MXV = 'MXV';

    /**
     * Malaysian Ringgit
     */
    public const MYR = 'MYR';

    /**
     * Mozambique Metical
     */
    public const MZN = 'MZN';

    /**
     * Namibia Dollar
     */
    public const NAD = 'NAD';

    /**
     * Naira
     */
    public const NGN = 'NGN';

    /**
     * Cordoba Oro
     */
    public const NIO = 'NIO';

    /**
     * Norwegian Krone
     */
    public const NOK = 'NOK';

    /**
     * Nepalese Rupee
     */
    public const NPR = 'NPR';

    /**
     * New Zealand Dollar
     */
    public const NZD = 'NZD';

    /**
     * Rial Omani
     */
    public const OMR = 'OMR';

    /**
     * Balboa
     */
    public const PAB = 'PAB';

    /**
     * Nuevo Sol
     */
    public const PEN = 'PEN';

    /**
     * Kina
     */
    public const PGK = 'PGK';

    /**
     * Philippine Peso
     */
    public const PHP = 'PHP';

    /**
     * Pakistan Rupee
     */
    public const PKR = 'PKR';

    /**
     * Zloty
     */
    public const PLN = 'PLN';

    /**
     * Guarani
     */
    public const PYG = 'PYG';

    /**
     * Qatari Rial
     */
    public const QAR = 'QAR';

    /**
     * Romanian Leu
     */
    public const RON = 'RON';

    /**
     * Serbian Dinar
     */
    public const RSD = 'RSD';

    /**
     * Russian Ruble
     */
    public const RUB = 'RUB';

    /**
     * Rwanda Franc
     */
    public const RWF = 'RWF';

    /**
     * Saudi Riyal
     */
    public const SAR = 'SAR';

    /**
     * Solomon Islands Dollar
     */
    public const SBD = 'SBD';

    /**
     * Seychelles Rupee
     */
    public const SCR = 'SCR';

    /**
     * Sudanese Pound
     */
    public const SDG = 'SDG';

    /**
     * Swedish Krona
     */
    public const SEK = 'SEK';

    /**
     * Singapore Dollar
     */
    public const SGD = 'SGD';

    /**
     * Saint Helena Pound
     */
    public const SHP = 'SHP';

    /**
     * Leone
     */
    public const SLL = 'SLL';

    /**
     * Somali Shilling
     */
    public const SOS = 'SOS';

    /**
     * Surinam Dollar
     */
    public const SRD = 'SRD';

    /**
     * South Sudanese Pound
     */
    public const SSP = 'SSP';

    /**
     * Dobra
     */
    public const STD = 'STD';

    /**
     * El Salvador Colon
     */
    public const SVC = 'SVC';

    /**
     * Syrian Pound
     */
    public const SYP = 'SYP';

    /**
     * Lilangeni
     */
    public const SZL = 'SZL';

    /**
     * Baht
     */
    public const THB = 'THB';

    /**
     * Somoni
     */
    public const TJS = 'TJS';

    /**
     * Turkmenistan New Manat
     */
    public const TMT = 'TMT';

    /**
     * Tunisian Dinar
     */
    public const TND = 'TND';

    /**
     * Pa’anga
     */
    public const TOP = 'TOP';

    /**
     * Turkish Lira
     */
    public const TRY = 'TRY';

    /**
     * Trinidad and Tobago Dollar
     */
    public const TTD = 'TTD';

    /**
     * New Taiwan Dollar
     */
    public const TWD = 'TWD';

    /**
     * Tanzanian Shilling
     */
    public const TZS = 'TZS';

    /**
     * Hryvnia
     */
    public const UAH = 'UAH';

    /**
     * Uganda Shilling
     */
    public const UGX = 'UGX';

    /**
     * US Dollar
     */
    public const USD = 'USD';

    /**
     * US Dollar (Next day)
     */
    public const USN = 'USN';

    /**
     * Uruguay Peso en Unidades Indexadas (URUIURUI)
     */
    public const UYI = 'UYI';

    /**
     * Peso Uruguayo
     */
    public const UYU = 'UYU';

    /**
     * Uzbekistan Sum
     */
    public const UZS = 'UZS';

    /**
     * Bolivar
     */
    public const VEF = 'VEF';

    /**
     * Dong
     */
    public const VND = 'VND';

    /**
     * Vatu
     */
    public const VUV = 'VUV';

    /**
     * Tala
     */
    public const WST = 'WST';

    /**
     * CFA Franc BEAC
     */
    public const XAF = 'XAF';

    /**
     * Silver
     */
    public const XAG = 'XAG';

    /**
     * Gold
     */
    public const XAU = 'XAU';

    /**
     * Bond Markets Unit European Composite Unit (EURCO)
     */
    public const XBA = 'XBA';

    /**
     * Bond Markets Unit European Monetary Unit (E.M.U.-6)
     */
    public const XBB = 'XBB';

    /**
     * Bond Markets Unit European Unit of Account 9 (E.U.A.-9)
     */
    public const XBC = 'XBC';

    /**
     * Bond Markets Unit European Unit of Account 17 (E.U.A.-17)
     */
    public const XBD = 'XBD';

    /**
     * East Caribbean Dollar
     */
    public const XCD = 'XCD';

    /**
     * SDR (Special Drawing Right)
     */
    public const XDR = 'XDR';

    /**
     * CFA Franc BCEAO
     */
    public const XOF = 'XOF';

    /**
     * Palladium
     */
    public const XPD = 'XPD';

    /**
     * CFP Franc
     */
    public const XPF = 'XPF';

    /**
     * Platinum
     */
    public const XPT = 'XPT';

    /**
     * Sucre
     */
    public const XSU = 'XSU';

    /**
     * Codes specifically reserved for testing purposes
     */
    public const XTS = 'XTS';

    /**
     * ADB Unit of Account
     */
    public const XUA = 'XUA';

    /**
     * The codes assigned for transactions where no currency is involved
     */
    public const XXX = 'XXX';

    /**
     * Yemeni Rial
     */
    public const YER = 'YER';

    /**
     * Rand
     */
    public const ZAR = 'ZAR';

    /**
     * Zambian Kwacha
     */
    public const ZMW = 'ZMW';

    /**
     * Zimbabwe Dollar
     */
    public const ZWL = 'ZWL';

    protected static array $currencies = [
        'AED' => [
            'display' => 'UAE Dirham',
            'code'    => 'AED',
            'numeric' => 784,
            'digits'  => 2,
            'minor'   => 100
        ],
        'AFN' => [
            'display' => 'Afghani',
            'code'    => 'AFN',
            'numeric' => 971,
            'digits'  => 2,
            'minor'   => 100
        ],
        'ALL' => [
            'display' => 'Lek',
            'code'    => 'ALL',
            'numeric' => 8,
            'digits'  => 2,
            'minor'   => 100
        ],
        'AMD' => [
            'display' => 'Armenian Dram',
            'code'    => 'AMD',
            'numeric' => 51,
            'digits'  => 2,
            'minor'   => 100
        ],
        'ANG' => [
            'display' => 'Netherlands Antillean Guilder',
            'code'    => 'ANG',
            'numeric' => 532,
            'digits'  => 2,
            'minor'   => 100
        ],
        'AOA' => [
            'display' => 'Kwanza',
            'code'    => 'AOA',
            'numeric' => 973,
            'digits'  => 2,
            'minor'   => 100
        ],
        'ARS' => [
            'display' => 'Argentine Peso',
            'code'    => 'ARS',
            'numeric' => 32,
            'digits'  => 2,
            'minor'   => 100
        ],
        'AUD' => [
            'display' => 'Australian Dollar',
            'code'    => 'AUD',
            'numeric' => 36,
            'digits'  => 2,
            'minor'   => 100
        ],
        'AWG' => [
            'display' => 'Aruban Florin',
            'code'    => 'AWG',
            'numeric' => 533,
            'digits'  => 2,
            'minor'   => 100
        ],
        'AZN' => [
            'display' => 'Azerbaijanian Manat',
            'code'    => 'AZN',
            'numeric' => 944,
            'digits'  => 2,
            'minor'   => 100
        ],
        'BAM' => [
            'display' => 'Convertible Mark',
            'code'    => 'BAM',
            'numeric' => 977,
            'digits'  => 2,
            'minor'   => 100
        ],
        'BBD' => [
            'display' => 'Barbados Dollar',
            'code'    => 'BBD',
            'numeric' => 52,
            'digits'  => 2,
            'minor'   => 100
        ],
        'BDT' => [
            'display' => 'Taka',
            'code'    => 'BDT',
            'numeric' => 50,
            'digits'  => 2,
            'minor'   => 100
        ],
        'BGN' => [
            'display' => 'Bulgarian Lev',
            'code'    => 'BGN',
            'numeric' => 975,
            'digits'  => 2,
            'minor'   => 100
        ],
        'BHD' => [
            'display' => 'Bahraini Dinar',
            'code'    => 'BHD',
            'numeric' => 48,
            'digits'  => 3,
            'minor'   => 1000
        ],
        'BIF' => [
            'display' => 'Burundi Franc',
            'code'    => 'BIF',
            'numeric' => 108,
            'digits'  => 0,
            'minor'   => 100
        ],
        'BMD' => [
            'display' => 'Bermudian Dollar',
            'code'    => 'BMD',
            'numeric' => 60,
            'digits'  => 2,
            'minor'   => 100
        ],
        'BND' => [
            'display' => 'Brunei Dollar',
            'code'    => 'BND',
            'numeric' => 96,
            'digits'  => 2,
            'minor'   => 100
        ],
        'BOB' => [
            'display' => 'Boliviano',
            'code'    => 'BOB',
            'numeric' => 68,
            'digits'  => 2,
            'minor'   => 100
        ],
        'BOV' => [
            'display' => 'Mvdol',
            'code'    => 'BOV',
            'numeric' => 984,
            'digits'  => 2,
            'minor'   => 100
        ],
        'BRL' => [
            'display' => 'Brazilian Real',
            'code'    => 'BRL',
            'numeric' => 986,
            'digits'  => 2,
            'minor'   => 100
        ],
        'BSD' => [
            'display' => 'Bahamian Dollar',
            'code'    => 'BSD',
            'numeric' => 44,
            'digits'  => 2,
            'minor'   => 100
        ],
        'BTN' => [
            'display' => 'Ngultrum',
            'code'    => 'BTN',
            'numeric' => 64,
            'digits'  => 2,
            'minor'   => 100
        ],
        'BWP' => [
            'display' => 'Pula',
            'code'    => 'BWP',
            'numeric' => 72,
            'digits'  => 2,
            'minor'   => 100
        ],
        'BYR' => [
            'display' => 'Belarussian Ruble',
            'code'    => 'BYR',
            'numeric' => 974,
            'digits'  => 0,
            'minor'   => 100
        ],
        'BZD' => [
            'display' => 'Belize Dollar',
            'code'    => 'BZD',
            'numeric' => 84,
            'digits'  => 2,
            'minor'   => 100
        ],
        'CAD' => [
            'display' => 'Canadian Dollar',
            'code'    => 'CAD',
            'numeric' => 124,
            'digits'  => 2,
            'minor'   => 100
        ],
        'CDF' => [
            'display' => 'Congolese Franc',
            'code'    => 'CDF',
            'numeric' => 976,
            'digits'  => 2,
            'minor'   => 100
        ],
        'CHE' => [
            'display' => 'WIR Euro',
            'code'    => 'CHE',
            'numeric' => 947,
            'digits'  => 2,
            'minor'   => 100
        ],
        'CHF' => [
            'display' => 'Swiss Franc',
            'code'    => 'CHF',
            'numeric' => 756,
            'digits'  => 2,
            'minor'   => 100
        ],
        'CHW' => [
            'display' => 'WIR Franc',
            'code'    => 'CHW',
            'numeric' => 948,
            'digits'  => 2,
            'minor'   => 100
        ],
        'CLF' => [
            'display' => 'Unidad de Fomento',
            'code'    => 'CLF',
            'numeric' => 990,
            'digits'  => 4,
            'minor'   => 100
        ],
        'CLP' => [
            'display' => 'Chilean Peso',
            'code'    => 'CLP',
            'numeric' => 152,
            'digits'  => 0,
            'minor'   => 100
        ],
        'CNY' => [
            'display' => 'Yuan Renminbi',
            'code'    => 'CNY',
            'numeric' => 156,
            'digits'  => 2,
            'minor'   => 100
        ],
        'COP' => [
            'display' => 'Colombian Peso',
            'code'    => 'COP',
            'numeric' => 170,
            'digits'  => 2,
            'minor'   => 100
        ],
        'COU' => [
            'display' => 'Unidad de Valor Real',
            'code'    => 'COU',
            'numeric' => 970,
            'digits'  => 2,
            'minor'   => 100
        ],
        'CRC' => [
            'display' => 'Costa Rican Colon',
            'code'    => 'CRC',
            'numeric' => 188,
            'digits'  => 2,
            'minor'   => 100
        ],
        'CUC' => [
            'display' => 'Peso Convertible',
            'code'    => 'CUC',
            'numeric' => 931,
            'digits'  => 2,
            'minor'   => 100
        ],
        'CUP' => [
            'display' => 'Cuban Peso',
            'code'    => 'CUP',
            'numeric' => 192,
            'digits'  => 2,
            'minor'   => 100
        ],
        'CVE' => [
            'display' => 'Cabo Verde Escudo',
            'code'    => 'CVE',
            'numeric' => 132,
            'digits'  => 2,
            'minor'   => 100
        ],
        'CZK' => [
            'display' => 'Czech Koruna',
            'code'    => 'CZK',
            'numeric' => 203,
            'digits'  => 2,
            'minor'   => 100
        ],
        'DJF' => [
            'display' => 'Djibouti Franc',
            'code'    => 'DJF',
            'numeric' => 262,
            'digits'  => 0,
            'minor'   => 100
        ],
        'DKK' => [
            'display' => 'Danish Krone',
            'code'    => 'DKK',
            'numeric' => 208,
            'digits'  => 2,
            'minor'   => 100
        ],
        'DOP' => [
            'display' => 'Dominican Peso',
            'code'    => 'DOP',
            'numeric' => 214,
            'digits'  => 2,
            'minor'   => 100
        ],
        'DZD' => [
            'display' => 'Algerian Dinar',
            'code'    => 'DZD',
            'numeric' => 12,
            'digits'  => 2,
            'minor'   => 100
        ],
        'EGP' => [
            'display' => 'Egyptian Pound',
            'code'    => 'EGP',
            'numeric' => 818,
            'digits'  => 2,
            'minor'   => 100
        ],
        'ERN' => [
            'display' => 'Nakfa',
            'code'    => 'ERN',
            'numeric' => 232,
            'digits'  => 2,
            'minor'   => 100
        ],
        'ETB' => [
            'display' => 'Ethiopian Birr',
            'code'    => 'ETB',
            'numeric' => 230,
            'digits'  => 2,
            'minor'   => 100
        ],
        'EUR' => [
            'display' => 'Euro',
            'code'    => 'EUR',
            'numeric' => 978,
            'digits'  => 2,
            'minor'   => 100
        ],
        'FJD' => [
            'display' => 'Fiji Dollar',
            'code'    => 'FJD',
            'numeric' => 242,
            'digits'  => 2,
            'minor'   => 100
        ],
        'FKP' => [
            'display' => 'Falkland Islands Pound',
            'code'    => 'FKP',
            'numeric' => 238,
            'digits'  => 2,
            'minor'   => 100
        ],
        'GBP' => [
            'display' => 'Pound Sterling',
            'code'    => 'GBP',
            'numeric' => 826,
            'digits'  => 2,
            'minor'   => 100
        ],
        'GEL' => [
            'display' => 'Lari',
            'code'    => 'GEL',
            'numeric' => 981,
            'digits'  => 2,
            'minor'   => 100
        ],
        'GHS' => [
            'display' => 'Ghana Cedi',
            'code'    => 'GHS',
            'numeric' => 936,
            'digits'  => 2,
            'minor'   => 100
        ],
        'GIP' => [
            'display' => 'Gibraltar Pound',
            'code'    => 'GIP',
            'numeric' => 292,
            'digits'  => 2,
            'minor'   => 100
        ],
        'GMD' => [
            'display' => 'Dalasi',
            'code'    => 'GMD',
            'numeric' => 270,
            'digits'  => 2,
            'minor'   => 100
        ],
        'GNF' => [
            'display' => 'Guinea Franc',
            'code'    => 'GNF',
            'numeric' => 324,
            'digits'  => 0,
            'minor'   => 100
        ],
        'GTQ' => [
            'display' => 'Quetzal',
            'code'    => 'GTQ',
            'numeric' => 320,
            'digits'  => 2,
            'minor'   => 100
        ],
        'GYD' => [
            'display' => 'Guyana Dollar',
            'code'    => 'GYD',
            'numeric' => 328,
            'digits'  => 2,
            'minor'   => 100
        ],
        'HKD' => [
            'display' => 'Hong Kong Dollar',
            'code'    => 'HKD',
            'numeric' => 344,
            'digits'  => 2,
            'minor'   => 100
        ],
        'HNL' => [
            'display' => 'Lempira',
            'code'    => 'HNL',
            'numeric' => 340,
            'digits'  => 2,
            'minor'   => 100
        ],
        'HRK' => [
            'display' => 'Kuna',
            'code'    => 'HRK',
            'numeric' => 191,
            'digits'  => 2,
            'minor'   => 100
        ],
        'HTG' => [
            'display' => 'Gourde',
            'code'    => 'HTG',
            'numeric' => 332,
            'digits'  => 2,
            'minor'   => 100
        ],
        'HUF' => [
            'display' => 'Forint',
            'code'    => 'HUF',
            'numeric' => 348,
            'digits'  => 2,
            'minor'   => 100
        ],
        'IDR' => [
            'display' => 'Rupiah',
            'code'    => 'IDR',
            'numeric' => 360,
            'digits'  => 2,
            'minor'   => 100
        ],
        'ILS' => [
            'display' => 'New Israeli Sheqel',
            'code'    => 'ILS',
            'numeric' => 376,
            'digits'  => 2,
            'minor'   => 100
        ],
        'INR' => [
            'display' => 'Indian Rupee',
            'code'    => 'INR',
            'numeric' => 356,
            'digits'  => 2,
            'minor'   => 100
        ],
        'IQD' => [
            'display' => 'Iraqi Dinar',
            'code'    => 'IQD',
            'numeric' => 368,
            'digits'  => 3,
            'minor'   => 1000
        ],
        'IRR' => [
            'display' => 'Iranian Rial',
            'code'    => 'IRR',
            'numeric' => 364,
            'digits'  => 2,
            'minor'   => 100
        ],
        'ISK' => [
            'display' => 'Iceland Krona',
            'code'    => 'ISK',
            'numeric' => 352,
            'digits'  => 0,
            'minor'   => 100
        ],
        'JMD' => [
            'display' => 'Jamaican Dollar',
            'code'    => 'JMD',
            'numeric' => 388,
            'digits'  => 2,
            'minor'   => 100
        ],
        'JOD' => [
            'display' => 'Jordanian Dinar',
            'code'    => 'JOD',
            'numeric' => 400,
            'digits'  => 3,
            'minor'   => 100
        ],
        'JPY' => [
            'display' => 'Yen',
            'code'    => 'JPY',
            'numeric' => 392,
            'digits'  => 0,
            'minor'   => 1
        ],
        'KES' => [
            'display' => 'Kenyan Shilling',
            'code'    => 'KES',
            'numeric' => 404,
            'digits'  => 2,
            'minor'   => 100
        ],
        'KGS' => [
            'display' => 'Som',
            'code'    => 'KGS',
            'numeric' => 417,
            'digits'  => 2,
            'minor'   => 100
        ],
        'KHR' => [
            'display' => 'Riel',
            'code'    => 'KHR',
            'numeric' => 116,
            'digits'  => 2,
            'minor'   => 100
        ],
        'KMF' => [
            'display' => 'Comoro Franc',
            'code'    => 'KMF',
            'numeric' => 174,
            'digits'  => 0,
            'minor'   => 100
        ],
        'KPW' => [
            'display' => 'North Korean Won',
            'code'    => 'KPW',
            'numeric' => 408,
            'digits'  => 2,
            'minor'   => 100
        ],
        'KRW' => [
            'display' => 'Won',
            'code'    => 'KRW',
            'numeric' => 410,
            'digits'  => 0,
            'minor'   => 100
        ],
        'KWD' => [
            'display' => 'Kuwaiti Dinar',
            'code'    => 'KWD',
            'numeric' => 414,
            'digits'  => 3,
            'minor'   => 1000
        ],
        'KYD' => [
            'display' => 'Cayman Islands Dollar',
            'code'    => 'KYD',
            'numeric' => 136,
            'digits'  => 2,
            'minor'   => 100
        ],
        'KZT' => [
            'display' => 'Tenge',
            'code'    => 'KZT',
            'numeric' => 398,
            'digits'  => 2,
            'minor'   => 100
        ],
        'LAK' => [
            'display' => 'Kip',
            'code'    => 'LAK',
            'numeric' => 418,
            'digits'  => 2,
            'minor'   => 100
        ],
        'LBP' => [
            'display' => 'Lebanese Pound',
            'code'    => 'LBP',
            'numeric' => 422,
            'digits'  => 2,
            'minor'   => 100
        ],
        'LKR' => [
            'display' => 'Sri Lanka Rupee',
            'code'    => 'LKR',
            'numeric' => 144,
            'digits'  => 2,
            'minor'   => 100
        ],
        'LRD' => [
            'display' => 'Liberian Dollar',
            'code'    => 'LRD',
            'numeric' => 430,
            'digits'  => 2,
            'minor'   => 100
        ],
        'LSL' => [
            'display' => 'Loti',
            'code'    => 'LSL',
            'numeric' => 426,
            'digits'  => 2,
            'minor'   => 100
        ],
        'LTL' => [
            'display' => 'Lithuanian Litas',
            'code'    => 'LTL',
            'numeric' => 440,
            'digits'  => 2,
            'minor'   => 100
        ],
        'LVL' => [
            'display' => 'Latvian Lats',
            'code'    => 'LVL',
            'numeric' => 428,
            'digits'  => 2,
            'minor'   => 100
        ],
        'LYD' => [
            'display' => 'Libyan Dinar',
            'code'    => 'LYD',
            'numeric' => 434,
            'digits'  => 3,
            'minor'   => 1000
        ],
        'MAD' => [
            'display' => 'Moroccan Dirham',
            'code'    => 'MAD',
            'numeric' => 504,
            'digits'  => 2,
            'minor'   => 100
        ],
        'MDL' => [
            'display' => 'Moldovan Leu',
            'code'    => 'MDL',
            'numeric' => 498,
            'digits'  => 2,
            'minor'   => 100
        ],
        'MGA' => [
            'display' => 'Malagasy Ariary',
            'code'    => 'MGA',
            'numeric' => 969,
            'digits'  => 1,
            'minor'   => 5
        ],
        'MKD' => [
            'display' => 'Denar',
            'code'    => 'MKD',
            'numeric' => 807,
            'digits'  => 2,
            'minor'   => 100
        ],
        'MMK' => [
            'display' => 'Kyat',
            'code'    => 'MMK',
            'numeric' => 104,
            'digits'  => 2,
            'minor'   => 100
        ],
        'MNT' => [
            'display' => 'Tugrik',
            'code'    => 'MNT',
            'numeric' => 496,
            'digits'  => 2,
            'minor'   => 100
        ],
        'MOP' => [
            'display' => 'Pataca',
            'code'    => 'MOP',
            'numeric' => 446,
            'digits'  => 2,
            'minor'   => 100
        ],
        'MRO' => [
            'display' => 'Ouguiya',
            'code'    => 'MRO',
            'numeric' => 478,
            'digits'  => 1,
            'minor'   => 5
        ],
        'MUR' => [
            'display' => 'Mauritius Rupee',
            'code'    => 'MUR',
            'numeric' => 480,
            'digits'  => 2,
            'minor'   => 100
        ],
        'MVR' => [
            'display' => 'Rufiyaa',
            'code'    => 'MVR',
            'numeric' => 462,
            'digits'  => 2,
            'minor'   => 100
        ],
        'MWK' => [
            'display' => 'Kwacha',
            'code'    => 'MWK',
            'numeric' => 454,
            'digits'  => 2,
            'minor'   => 100
        ],
        'MXN' => [
            'display' => 'Mexican Peso',
            'code'    => 'MXN',
            'numeric' => 484,
            'digits'  => 2,
            'minor'   => 100
        ],
        'MXV' => [
            'display' => 'Mexican Unidad de Inversion (UDI)',
            'code'    => 'MXV',
            'numeric' => 979,
            'digits'  => 2,
            'minor'   => 100
        ],
        'MYR' => [
            'display' => 'Malaysian Ringgit',
            'code'    => 'MYR',
            'numeric' => 458,
            'digits'  => 2,
            'minor'   => 100
        ],
        'MZN' => [
            'display' => 'Mozambique Metical',
            'code'    => 'MZN',
            'numeric' => 943,
            'digits'  => 2,
            'minor'   => 100
        ],
        'NAD' => [
            'display' => 'Namibia Dollar',
            'code'    => 'NAD',
            'numeric' => 516,
            'digits'  => 2,
            'minor'   => 100
        ],
        'NGN' => [
            'display' => 'Naira',
            'code'    => 'NGN',
            'numeric' => 566,
            'digits'  => 2,
            'minor'   => 100
        ],
        'NIO' => [
            'display' => 'Cordoba Oro',
            'code'    => 'NIO',
            'numeric' => 558,
            'digits'  => 2,
            'minor'   => 100
        ],
        'NOK' => [
            'display' => 'Norwegian Krone',
            'code'    => 'NOK',
            'numeric' => 578,
            'digits'  => 2,
            'minor'   => 100
        ],
        'NPR' => [
            'display' => 'Nepalese Rupee',
            'code'    => 'NPR',
            'numeric' => 524,
            'digits'  => 2,
            'minor'   => 100
        ],
        'NZD' => [
            'display' => 'New Zealand Dollar',
            'code'    => 'NZD',
            'numeric' => 554,
            'digits'  => 2,
            'minor'   => 100
        ],
        'OMR' => [
            'display' => 'Rial Omani',
            'code'    => 'OMR',
            'numeric' => 512,
            'digits'  => 3,
            'minor'   => 1000
        ],
        'PAB' => [
            'display' => 'Balboa',
            'code'    => 'PAB',
            'numeric' => 590,
            'digits'  => 2,
            'minor'   => 100
        ],
        'PEN' => [
            'display' => 'Nuevo Sol',
            'code'    => 'PEN',
            'numeric' => 604,
            'digits'  => 2,
            'minor'   => 100
        ],
        'PGK' => [
            'display' => 'Kina',
            'code'    => 'PGK',
            'numeric' => 598,
            'digits'  => 2,
            'minor'   => 100
        ],
        'PHP' => [
            'display' => 'Philippine Peso',
            'code'    => 'PHP',
            'numeric' => 608,
            'digits'  => 2,
            'minor'   => 100
        ],
        'PKR' => [
            'display' => 'Pakistan Rupee',
            'code'    => 'PKR',
            'numeric' => 586,
            'digits'  => 2,
            'minor'   => 100
        ],
        'PLN' => [
            'display' => 'Zloty',
            'code'    => 'PLN',
            'numeric' => 985,
            'digits'  => 2,
            'minor'   => 100
        ],
        'PYG' => [
            'display' => 'Guarani',
            'code'    => 'PYG',
            'numeric' => 600,
            'digits'  => 0,
            'minor'   => 100
        ],
        'QAR' => [
            'display' => 'Qatari Rial',
            'code'    => 'QAR',
            'numeric' => 634,
            'digits'  => 2,
            'minor'   => 100
        ],
        'RON' => [
            'display' => 'Romanian Leu',
            'code'    => 'RON',
            'numeric' => 946,
            'digits'  => 2,
            'minor'   => 100
        ],
        'RSD' => [
            'display' => 'Serbian Dinar',
            'code'    => 'RSD',
            'numeric' => 941,
            'digits'  => 2,
            'minor'   => 100
        ],
        'RUB' => [
            'display' => 'Russian Ruble',
            'code'    => 'RUB',
            'numeric' => 643,
            'digits'  => 2,
            'minor'   => 100
        ],
        'RWF' => [
            'display' => 'Rwanda Franc',
            'code'    => 'RWF',
            'numeric' => 646,
            'digits'  => 0,
            'minor'   => 100
        ],
        'SAR' => [
            'display' => 'Saudi Riyal',
            'code'    => 'SAR',
            'numeric' => 682,
            'digits'  => 2,
            'minor'   => 100
        ],
        'SBD' => [
            'display' => 'Solomon Islands Dollar',
            'code'    => 'SBD',
            'numeric' => 90,
            'digits'  => 2,
            'minor'   => 100
        ],
        'SCR' => [
            'display' => 'Seychelles Rupee',
            'code'    => 'SCR',
            'numeric' => 690,
            'digits'  => 2,
            'minor'   => 100
        ],
        'SDG' => [
            'display' => 'Sudanese Pound',
            'code'    => 'SDG',
            'numeric' => 938,
            'digits'  => 2,
            'minor'   => 100
        ],
        'SEK' => [
            'display' => 'Swedish Krona',
            'code'    => 'SEK',
            'numeric' => 752,
            'digits'  => 2,
            'minor'   => 100
        ],
        'SGD' => [
            'display' => 'Singapore Dollar',
            'code'    => 'SGD',
            'numeric' => 702,
            'digits'  => 2,
            'minor'   => 100
        ],
        'SHP' => [
            'display' => 'Saint Helena Pound',
            'code'    => 'SHP',
            'numeric' => 654,
            'digits'  => 2,
            'minor'   => 100
        ],
        'SLL' => [
            'display' => 'Leone',
            'code'    => 'SLL',
            'numeric' => 694,
            'digits'  => 2,
            'minor'   => 100
        ],
        'SOS' => [
            'display' => 'Somali Shilling',
            'code'    => 'SOS',
            'numeric' => 706,
            'digits'  => 2,
            'minor'   => 100
        ],
        'SRD' => [
            'display' => 'Surinam Dollar',
            'code'    => 'SRD',
            'numeric' => 968,
            'digits'  => 2,
            'minor'   => 100
        ],
        'SSP' => [
            'display' => 'South Sudanese Pound',
            'code'    => 'SSP',
            'numeric' => 728,
            'digits'  => 2,
            'minor'   => 100
        ],
        'STD' => [
            'display' => 'Dobra',
            'code'    => 'STD',
            'numeric' => 678,
            'digits'  => 2,
            'minor'   => 100
        ],
        'SVC' => [
            'display' => 'El Salvador Colon',
            'code'    => 'SVC',
            'numeric' => 222,
            'digits'  => 2,
            'minor'   => 100
        ],
        'SYP' => [
            'display' => 'Syrian Pound',
            'code'    => 'SYP',
            'numeric' => 760,
            'digits'  => 2,
            'minor'   => 100
        ],
        'SZL' => [
            'display' => 'Lilangeni',
            'code'    => 'SZL',
            'numeric' => 748,
            'digits'  => 2,
            'minor'   => 100
        ],
        'THB' => [
            'display' => 'Baht',
            'code'    => 'THB',
            'numeric' => 764,
            'digits'  => 2,
            'minor'   => 100
        ],
        'TJS' => [
            'display' => 'Somoni',
            'code'    => 'TJS',
            'numeric' => 972,
            'digits'  => 2,
            'minor'   => 100
        ],
        'TMT' => [
            'display' => 'Turkmenistan New Manat',
            'code'    => 'TMT',
            'numeric' => 934,
            'digits'  => 2,
            'minor'   => 100
        ],
        'TND' => [
            'display' => 'Tunisian Dinar',
            'code'    => 'TND',
            'numeric' => 788,
            'digits'  => 3,
            'minor'   => 1000
        ],
        'TOP' => [
            'display' => 'Pa’anga',
            'code'    => 'TOP',
            'numeric' => 776,
            'digits'  => 2,
            'minor'   => 100
        ],
        'TRY' => [
            'display' => 'Turkish Lira',
            'code'    => 'TRY',
            'numeric' => 949,
            'digits'  => 2,
            'minor'   => 100
        ],
        'TTD' => [
            'display' => 'Trinidad and Tobago Dollar',
            'code'    => 'TTD',
            'numeric' => 780,
            'digits'  => 2,
            'minor'   => 100
        ],
        'TWD' => [
            'display' => 'New Taiwan Dollar',
            'code'    => 'TWD',
            'numeric' => 901,
            'digits'  => 2,
            'minor'   => 100
        ],
        'TZS' => [
            'display' => 'Tanzanian Shilling',
            'code'    => 'TZS',
            'numeric' => 834,
            'digits'  => 2,
            'minor'   => 100
        ],
        'UAH' => [
            'display' => 'Hryvnia',
            'code'    => 'UAH',
            'numeric' => 980,
            'digits'  => 2,
            'minor'   => 100
        ],
        'UGX' => [
            'display' => 'Uganda Shilling',
            'code'    => 'UGX',
            'numeric' => 800,
            'digits'  => 0,
            'minor'   => 100
        ],
        'USD' => [
            'display' => 'US Dollar',
            'code'    => 'USD',
            'numeric' => 840,
            'digits'  => 2,
            'minor'   => 100
        ],
        'USN' => [
            'display' => 'US Dollar (Next day)',
            'code'    => 'USN',
            'numeric' => 997,
            'digits'  => 2,
            'minor'   => 100
        ],
        'UYI' => [
            'display' => 'Uruguay Peso en Unidades Indexadas (URUIURUI)',
            'code'    => 'UYI',
            'numeric' => 940,
            'digits'  => 0,
            'minor'   => 100
        ],
        'UYU' => [
            'display' => 'Peso Uruguayo',
            'code'    => 'UYU',
            'numeric' => 858,
            'digits'  => 2,
            'minor'   => 100
        ],
        'UZS' => [
            'display' => 'Uzbekistan Sum',
            'code'    => 'UZS',
            'numeric' => 860,
            'digits'  => 2,
            'minor'   => 100
        ],
        'VEF' => [
            'display' => 'Bolivar',
            'code'    => 'VEF',
            'numeric' => 937,
            'digits'  => 2,
            'minor'   => 100
        ],
        'VND' => [
            'display' => 'Dong',
            'code'    => 'VND',
            'numeric' => 704,
            'digits'  => 0,
            'minor'   => 10
        ],
        'VUV' => [
            'display' => 'Vatu',
            'code'    => 'VUV',
            'numeric' => 548,
            'digits'  => 0,
            'minor'   => 1
        ],
        'WST' => [
            'display' => 'Tala',
            'code'    => 'WST',
            'numeric' => 882,
            'digits'  => 2,
            'minor'   => 100
        ],
        'XAF' => [
            'display' => 'CFA Franc BEAC',
            'code'    => 'XAF',
            'numeric' => 950,
            'digits'  => 0,
            'minor'   => 100
        ],
        'XAG' => [
            'display' => 'Silver',
            'code'    => 'XAG',
            'numeric' => 961,
            'digits'  => -1,
            'minor'   => 1
        ],
        'XAU' => [
            'display' => 'Gold',
            'code'    => 'XAU',
            'numeric' => 959,
            'digits'  => -1,
            'minor'   => 1
        ],
        'XBA' => [
            'display' => 'Bond Markets Unit European Composite Unit (EURCO)',
            'code'    => 'XBA',
            'numeric' => 955,
            'digits'  => -1,
            'minor'   => 1
        ],
        'XBB' => [
            'display' => 'Bond Markets Unit European Monetary Unit (E.M.U.-6)',
            'code'    => 'XBB',
            'numeric' => 956,
            'digits'  => -1,
            'minor'   => 1
        ],
        'XBC' => [
            'display' => 'Bond Markets Unit European Unit of Account 9 (E.U.A.-9)',
            'code'    => 'XBC',
            'numeric' => 957,
            'digits'  => -1,
            'minor'   => 1
        ],
        'XBD' => [
            'display' => 'Bond Markets Unit European Unit of Account 17 (E.U.A.-17)',
            'code'    => 'XBD',
            'numeric' => 958,
            'digits'  => -1,
            'minor'   => 1
        ],
        'XCD' => [
            'display' => 'East Caribbean Dollar',
            'code'    => 'XCD',
            'numeric' => 951,
            'digits'  => 2,
            'minor'   => 100
        ],
        'XDR' => [
            'display' => 'SDR (Special Drawing Right)',
            'code'    => 'XDR',
            'numeric' => 960,
            'digits'  => -1,
            'minor'   => 1
        ],
        'XOF' => [
            'display' => 'CFA Franc BCEAO',
            'code'    => 'XOF',
            'numeric' => 952,
            'digits'  => 0,
            'minor'   => 100
        ],
        'XPD' => [
            'display' => 'Palladium',
            'code'    => 'XPD',
            'numeric' => 964,
            'digits'  => -1,
            'minor'   => 1
        ],
        'XPF' => [
            'display' => 'CFP Franc',
            'code'    => 'XPF',
            'numeric' => 953,
            'digits'  => 0,
            'minor'   => 100
        ],
        'XPT' => [
            'display' => 'Platinum',
            'code'    => 'XPT',
            'numeric' => 962,
            'digits'  => -1,
            'minor'   => 1
        ],
        'XSU' => [
            'display' => 'Sucre',
            'code'    => 'XSU',
            'numeric' => 994,
            'digits'  => -1,
            'minor'   => 1
        ],
        'XTS' => [
            'display' => 'Codes specifically reserved for testing purposes',
            'code'    => 'XTS',
            'numeric' => 963,
            'digits'  => -1,
            'minor'   => 1
        ],
        'XUA' => [
            'display' => 'ADB Unit of Account',
            'code'    => 'XUA',
            'numeric' => 965,
            'digits'  => -1,
            'minor'   => 1
        ],
        'XXX' => [
            'display' => 'The codes assigned for transactions where no currency is involved',
            'code'    => 'XXX',
            'numeric' => 999,
            'digits'  => -1,
            'minor'   => 1
        ],
        'YER' => [
            'display' => 'Yemeni Rial',
            'code'    => 'YER',
            'numeric' => 886,
            'digits'  => 2,
            'minor'   => 100
        ],
        'ZAR' => [
            'display' => 'Rand',
            'code'    => 'ZAR',
            'numeric' => 710,
            'digits'  => 2,
            'minor'   => 100
        ],
        'ZMW' => [
            'display' => 'Zambian Kwacha',
            'code'    => 'ZMW',
            'numeric' => 967,
            'digits'  => 2,
            'minor'   => 100
        ],
        'ZWL' => [
            'display' => 'Zimbabwe Dollar',
            'code'    => 'ZWL',
            'numeric' => 932,
            'digits'  => 2,
            'minor'   => 100
        ]
    ];

    /**
     * Retrieves the display name
     */
    public function displayName(): string
    {
        return static::$currencies[$this->value()]['display'];
    }

    /**
     * Retrieves the ISO 4217 currency code
     */
    public function code(): string
    {
        return static::$currencies[$this->value()]['code'];
    }

    /**
     * Retrieves the ISO 4217 numeric code
     */
    public function numericCode(): int
    {
        return static::$currencies[$this->value()]['numeric'];
    }

    /**
     * Retrieves the default number of fraction digits
     *
     * In the case of pseudo-currencies, -1 is returned.
     */
    public function digits(): int
    {
        return static::$currencies[$this->value()]['digits'];
    }

    /**
     * Retrieves the number of minor units
     */
    public function minor(): int
    {
        return static::$currencies[$this->value()]['minor'];
    }
}

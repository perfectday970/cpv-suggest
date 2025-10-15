<?php

namespace Database\Seeders;

use App\Models\Bekanntmachung;
use App\Models\CpvCode;
use Illuminate\Database\Seeder;

class BekanntmachungSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        \DB::table('bekanntmachung_cpv')->truncate();
        Bekanntmachung::truncate();

        $mockData = [
            // IT & Software (CPV 72xxxxxx)
            [
                'veroeffentlicht' => '2025-09-15',
                'angebotsfrist' => '2025-11-01',
                'kurzbezeichnung' => 'IT-Dienstleistungen für Verwaltungsmodernisierung',
                'typ' => 'Offenes Verfahren',
                'vergabeplattform' => 'bund.de',
                'geschaetzter_auftragswert' => 250000.00,
                'beschreibung' => 'Ausschreibung für umfassende IT-Beratungsleistungen zur Digitalisierung der Verwaltungsprozesse.',
                'cpv_codes' => ['72000000', '72200000', '72220000'],
            ],
            [
                'veroeffentlicht' => '2025-09-18',
                'angebotsfrist' => '2025-11-05',
                'kurzbezeichnung' => 'Cloud-Infrastruktur und Hosting-Services',
                'typ' => 'Verhandlungsverfahren',
                'vergabeplattform' => 'Vergabe24',
                'geschaetzter_auftragswert' => 500000.00,
                'beschreibung' => 'Bereitstellung und Betrieb einer skalierbaren Cloud-Infrastruktur für öffentliche Dienste.',
                'cpv_codes' => ['72000000', '72400000', '72410000'],
            ],
            [
                'veroeffentlicht' => '2025-09-20',
                'angebotsfrist' => '2025-11-10',
                'kurzbezeichnung' => 'Softwareentwicklung für E-Government Portal',
                'typ' => 'Offenes Verfahren',
                'vergabeplattform' => 'dtvp.de',
                'geschaetzter_auftragswert' => 750000.00,
                'beschreibung' => 'Entwicklung einer modernen Webanwendung für Bürgerservices mit Authentifizierung und Formularmanagement.',
                'cpv_codes' => ['72000000', '72200000', '72212000', '72260000'],
            ],
            [
                'veroeffentlicht' => '2025-09-22',
                'angebotsfrist' => '2025-11-12',
                'kurzbezeichnung' => 'Cybersecurity-Beratung und Penetrationstests',
                'typ' => 'Verhandlungsverfahren',
                'vergabeplattform' => 'bund.de',
                'geschaetzter_auftragswert' => 150000.00,
                'beschreibung' => 'Sicherheitsanalyse der IT-Infrastruktur inkl. Penetrationstests und Erstellung eines Sicherheitskonzepts.',
                'cpv_codes' => ['72000000', '72200000', '72240000'],
            ],
            [
                'veroeffentlicht' => '2025-09-25',
                'angebotsfrist' => '2025-11-15',
                'kurzbezeichnung' => 'Datenbankmanagement und Migration',
                'typ' => 'Offenes Verfahren',
                'vergabeplattform' => 'Vergabe24',
                'geschaetzter_auftragswert' => 180000.00,
                'beschreibung' => 'Migration von Legacy-Datenbanksystemen zu modernen Cloud-basierten Lösungen.',
                'cpv_codes' => ['72000000', '72260000', '72263000'],
            ],
            [
                'veroeffentlicht' => '2025-09-28',
                'angebotsfrist' => '2025-11-18',
                'kurzbezeichnung' => 'Rechenzentrum Wartung und Support',
                'typ' => 'Verhandlungsverfahren',
                'vergabeplattform' => 'bund.de',
                'geschaetzter_auftragswert' => 350000.00,
                'beschreibung' => '24/7 Wartung und technischer Support für Rechenzentrum mit 500+ Servern.',
                'cpv_codes' => ['72000000', '72500000', '72700000'],
            ],
            [
                'veroeffentlicht' => '2025-10-01',
                'angebotsfrist' => '2025-11-20',
                'kurzbezeichnung' => 'ERP-System Implementierung',
                'typ' => 'Offenes Verfahren',
                'vergabeplattform' => 'dtvp.de',
                'geschaetzter_auftragswert' => 980000.00,
                'beschreibung' => 'Einführung und Customizing eines ERP-Systems für kommunale Verwaltung.',
                'cpv_codes' => ['72000000', '72260000', '72268000'],
            ],
            [
                'veroeffentlicht' => '2025-10-03',
                'angebotsfrist' => '2025-11-22',
                'kurzbezeichnung' => 'Mobile App Entwicklung für Bürgerdienste',
                'typ' => 'Verhandlungsverfahren',
                'vergabeplattform' => 'Vergabe24',
                'geschaetzter_auftragswert' => 420000.00,
                'beschreibung' => 'Native iOS und Android App für kommunale Dienstleistungen.',
                'cpv_codes' => ['72000000', '72200000', '72212000'],
            ],
            [
                'veroeffentlicht' => '2025-10-05',
                'angebotsfrist' => '2025-11-25',
                'kurzbezeichnung' => 'Netzwerk-Infrastruktur Modernisierung',
                'typ' => 'Offenes Verfahren',
                'vergabeplattform' => 'bund.de',
                'geschaetzter_auftragswert' => 630000.00,
                'beschreibung' => 'Ausbau und Modernisierung der Netzwerk-Infrastruktur inkl. Switches und Router.',
                'cpv_codes' => ['72000000', '72700000'],
            ],
            [
                'veroeffentlicht' => '2025-10-07',
                'angebotsfrist' => '2025-11-28',
                'kurzbezeichnung' => 'IT-Schulungen für Mitarbeiter',
                'typ' => 'Verhandlungsverfahren',
                'vergabeplattform' => 'dtvp.de',
                'geschaetzter_auftragswert' => 85000.00,
                'beschreibung' => 'Durchführung von IT-Schulungen in den Bereichen Cloud, Security und Datenschutz.',
                'cpv_codes' => ['72000000', '80500000'],
            ],

            // Bauleistungen (CPV 45xxxxxx)
            [
                'veroeffentlicht' => '2025-09-10',
                'angebotsfrist' => '2025-10-30',
                'kurzbezeichnung' => 'Straßenbauarbeiten Bundesstraße B123',
                'typ' => 'Offenes Verfahren',
                'vergabeplattform' => 'Vergabe24',
                'geschaetzter_auftragswert' => 2500000.00,
                'beschreibung' => 'Sanierung und Ausbau der Bundesstraße B123 auf einer Länge von 5 km inkl. Nebenarbeiten.',
                'cpv_codes' => ['45000000', '45233000', '45233120'],
            ],
            [
                'veroeffentlicht' => '2025-09-12',
                'angebotsfrist' => '2025-11-02',
                'kurzbezeichnung' => 'Neubau Grundschule Musterstadt',
                'typ' => 'Offenes Verfahren',
                'vergabeplattform' => 'bund.de',
                'geschaetzter_auftragswert' => 5800000.00,
                'beschreibung' => 'Errichtung einer zweizügigen Grundschule mit Turnhalle und Außenanlagen.',
                'cpv_codes' => ['45000000', '45214000', '45214200'],
            ],
            [
                'veroeffentlicht' => '2025-09-16',
                'angebotsfrist' => '2025-11-06',
                'kurzbezeichnung' => 'Sanierung Rathaus Altbau',
                'typ' => 'Verhandlungsverfahren',
                'vergabeplattform' => 'dtvp.de',
                'geschaetzter_auftragswert' => 1200000.00,
                'beschreibung' => 'Denkmalgerechte Sanierung des historischen Rathauses inkl. Dach und Fassade.',
                'cpv_codes' => ['45000000', '45400000', '45453000'],
            ],
            [
                'veroeffentlicht' => '2025-09-19',
                'angebotsfrist' => '2025-11-08',
                'kurzbezeichnung' => 'Brückenneubau über Fluss Muster',
                'typ' => 'Offenes Verfahren',
                'vergabeplattform' => 'Vergabe24',
                'geschaetzter_auftragswert' => 8900000.00,
                'beschreibung' => 'Neubau einer zweispurigen Straßenbrücke inkl. Rad- und Gehweg.',
                'cpv_codes' => ['45000000', '45221000', '45221110'],
            ],
            [
                'veroeffentlicht' => '2025-09-23',
                'angebotsfrist' => '2025-11-13',
                'kurzbezeichnung' => 'Kanalarbeiten Wohngebiet Süd',
                'typ' => 'Offenes Verfahren',
                'vergabeplattform' => 'bund.de',
                'geschaetzter_auftragswert' => 1450000.00,
                'beschreibung' => 'Verlegung von Abwasserkanälen im neuen Wohngebiet.',
                'cpv_codes' => ['45000000', '45231000', '45232410'],
            ],
            [
                'veroeffentlicht' => '2025-09-26',
                'angebotsfrist' => '2025-11-16',
                'kurzbezeichnung' => 'Parkhaus Innenstadt',
                'typ' => 'Verhandlungsverfahren',
                'vergabeplattform' => 'dtvp.de',
                'geschaetzter_auftragswert' => 4200000.00,
                'beschreibung' => 'Errichtung eines Parkhauses mit 350 Stellplätzen in der Innenstadt.',
                'cpv_codes' => ['45000000', '45214100', '45223200'],
            ],
            [
                'veroeffentlicht' => '2025-09-29',
                'angebotsfrist' => '2025-11-19',
                'kurzbezeichnung' => 'Sporthallenneubau',
                'typ' => 'Offenes Verfahren',
                'vergabeplattform' => 'Vergabe24',
                'geschaetzter_auftragswert' => 3100000.00,
                'beschreibung' => 'Neubau einer Dreifeldsporthalle mit Umkleideräumen und Technikzentrale.',
                'cpv_codes' => ['45000000', '45212200'],
            ],
            [
                'veroeffentlicht' => '2025-10-02',
                'angebotsfrist' => '2025-11-21',
                'kurzbezeichnung' => 'Feuerwache Erweiterung',
                'typ' => 'Offenes Verfahren',
                'vergabeplattform' => 'bund.de',
                'geschaetzter_auftragswert' => 2700000.00,
                'beschreibung' => 'Erweiterungsbau der bestehenden Feuerwache um Fahrzeughalle und Schulungsräume.',
                'cpv_codes' => ['45000000', '45214220'],
            ],
            [
                'veroeffentlicht' => '2025-10-04',
                'angebotsfrist' => '2025-11-23',
                'kurzbezeichnung' => 'Radweg Ausbau L456',
                'typ' => 'Offenes Verfahren',
                'vergabeplattform' => 'dtvp.de',
                'geschaetzter_auftragswert' => 890000.00,
                'beschreibung' => 'Ausbau und Asphaltierung eines Radwegs entlang der Landstraße L456.',
                'cpv_codes' => ['45000000', '45233000'],
            ],
            [
                'veroeffentlicht' => '2025-10-06',
                'angebotsfrist' => '2025-11-26',
                'kurzbezeichnung' => 'Kindergarten Sanierung',
                'typ' => 'Verhandlungsverfahren',
                'vergabeplattform' => 'Vergabe24',
                'geschaetzter_auftragswert' => 650000.00,
                'beschreibung' => 'Energetische Sanierung eines kommunalen Kindergartens.',
                'cpv_codes' => ['45000000', '45400000'],
            ],

            // Büromöbel & Ausstattung (CPV 39xxxxxx)
            [
                'veroeffentlicht' => '2025-09-14',
                'angebotsfrist' => '2025-10-28',
                'kurzbezeichnung' => 'Beschaffung von Büromöbeln',
                'typ' => 'Offenes Verfahren',
                'vergabeplattform' => 'dtvp.de',
                'geschaetzter_auftragswert' => 80000.00,
                'beschreibung' => 'Lieferung von ergonomischen Büromöbeln für 150 Arbeitsplätze inkl. Montage.',
                'cpv_codes' => ['39100000', '39110000', '39130000'],
            ],
            [
                'veroeffentlicht' => '2025-09-21',
                'angebotsfrist' => '2025-11-11',
                'kurzbezeichnung' => 'Schulausstattung Möbel und Tafeln',
                'typ' => 'Offenes Verfahren',
                'vergabeplattform' => 'bund.de',
                'geschaetzter_auftragswert' => 125000.00,
                'beschreibung' => 'Komplettausstattung von 20 Klassenräumen mit Tischen, Stühlen und interaktiven Tafeln.',
                'cpv_codes' => ['39100000', '39162100'],
            ],
            [
                'veroeffentlicht' => '2025-10-08',
                'angebotsfrist' => '2025-11-29',
                'kurzbezeichnung' => 'Bibliotheksmöbel und Regalsysteme',
                'typ' => 'Verhandlungsverfahren',
                'vergabeplattform' => 'Vergabe24',
                'geschaetzter_auftragswert' => 95000.00,
                'beschreibung' => 'Möblierung der neuen Stadtbibliothek mit Regalen, Lesesälen und Arbeitsplätzen.',
                'cpv_codes' => ['39100000', '39130000'],
            ],

            // Medizintechnik & Gesundheit (CPV 33xxxxxx)
            [
                'veroeffentlicht' => '2025-09-11',
                'angebotsfrist' => '2025-10-31',
                'kurzbezeichnung' => 'Medizinische Geräte für Krankenhaus',
                'typ' => 'Offenes Verfahren',
                'vergabeplattform' => 'bund.de',
                'geschaetzter_auftragswert' => 1850000.00,
                'beschreibung' => 'Beschaffung von Röntgengeräten, Ultraschall und OP-Ausstattung.',
                'cpv_codes' => ['33100000', '33111000'],
            ],
            [
                'veroeffentlicht' => '2025-09-17',
                'angebotsfrist' => '2025-11-07',
                'kurzbezeichnung' => 'Rettungsfahrzeuge und Notfallausrüstung',
                'typ' => 'Verhandlungsverfahren',
                'vergabeplattform' => 'dtvp.de',
                'geschaetzter_auftragswert' => 540000.00,
                'beschreibung' => 'Beschaffung von 3 Rettungswagen inkl. medizinischer Ausstattung.',
                'cpv_codes' => ['34114000', '33100000'],
            ],
            [
                'veroeffentlicht' => '2025-09-27',
                'angebotsfrist' => '2025-11-17',
                'kurzbezeichnung' => 'Labor-Equipment für Universitätsklinik',
                'typ' => 'Offenes Verfahren',
                'vergabeplattform' => 'Vergabe24',
                'geschaetzter_auftragswert' => 720000.00,
                'beschreibung' => 'Modernisierung der Laborausstattung mit Analysegeräten und Zentrifugen.',
                'cpv_codes' => ['33100000', '38000000'],
            ],

            // Fahrzeuge (CPV 34xxxxxx)
            [
                'veroeffentlicht' => '2025-09-13',
                'angebotsfrist' => '2025-11-03',
                'kurzbezeichnung' => 'Fuhrpark Elektrofahrzeuge',
                'typ' => 'Offenes Verfahren',
                'vergabeplattform' => 'bund.de',
                'geschaetzter_auftragswert' => 380000.00,
                'beschreibung' => 'Beschaffung von 15 elektrischen Dienstfahrzeugen inkl. Ladeinfrastruktur.',
                'cpv_codes' => ['34100000', '34144900'],
            ],
            [
                'veroeffentlicht' => '2025-09-24',
                'angebotsfrist' => '2025-11-14',
                'kurzbezeichnung' => 'Müllfahrzeuge und Kehrtechnik',
                'typ' => 'Offenes Verfahren',
                'vergabeplattform' => 'Vergabe24',
                'geschaetzter_auftragswert' => 950000.00,
                'beschreibung' => 'Neubeschaffung von Müllfahrzeugen und Kehrmaschinen für Stadtwerke.',
                'cpv_codes' => ['34100000', '34144510'],
            ],
            [
                'veroeffentlicht' => '2025-10-09',
                'angebotsfrist' => '2025-11-30',
                'kurzbezeichnung' => 'Busse für ÖPNV',
                'typ' => 'Verhandlungsverfahren',
                'vergabeplattform' => 'dtvp.de',
                'geschaetzter_auftragswert' => 4500000.00,
                'beschreibung' => 'Beschaffung von 12 Hybrid-Bussen für den öffentlichen Nahverkehr.',
                'cpv_codes' => ['34100000', '34121000'],
            ],

            // Reinigung & Facility (CPV 90xxxxxx)
            [
                'veroeffentlicht' => '2025-09-08',
                'angebotsfrist' => '2025-10-25',
                'kurzbezeichnung' => 'Gebäudereinigung Verwaltungsgebäude',
                'typ' => 'Offenes Verfahren',
                'vergabeplattform' => 'bund.de',
                'geschaetzter_auftragswert' => 145000.00,
                'beschreibung' => 'Unterhaltsreinigung von 5 Verwaltungsgebäuden für 2 Jahre.',
                'cpv_codes' => ['90900000', '90910000'],
            ],
            [
                'veroeffentlicht' => '2025-09-30',
                'angebotsfrist' => '2025-11-19',
                'kurzbezeichnung' => 'Winterdienst Stadtgebiet',
                'typ' => 'Verhandlungsverfahren',
                'vergabeplattform' => 'Vergabe24',
                'geschaetzter_auftragswert' => 280000.00,
                'beschreibung' => 'Schneeräumung und Streudienst für Hauptverkehrsstraßen.',
                'cpv_codes' => ['90600000', '90630000'],
            ],
            [
                'veroeffentlicht' => '2025-10-10',
                'angebotsfrist' => '2025-12-01',
                'kurzbezeichnung' => 'Grünflächenpflege Stadtpark',
                'typ' => 'Offenes Verfahren',
                'vergabeplattform' => 'dtvp.de',
                'geschaetzter_auftragswert' => 165000.00,
                'beschreibung' => 'Pflege und Instandhaltung der städtischen Grünanlagen für 3 Jahre.',
                'cpv_codes' => ['77300000', '77310000'],
            ],

            // Beratung & Services (CPV 71xxxxxx, 79xxxxxx)
            [
                'veroeffentlicht' => '2025-09-09',
                'angebotsfrist' => '2025-10-29',
                'kurzbezeichnung' => 'Architekturleistungen Schulneubau',
                'typ' => 'Verhandlungsverfahren',
                'vergabeplattform' => 'bund.de',
                'geschaetzter_auftragswert' => 420000.00,
                'beschreibung' => 'Architektenleistungen für Planung und Bauüberwachung Schulneubau.',
                'cpv_codes' => ['71200000', '71220000'],
            ],
            [
                'veroeffentlicht' => '2025-09-15',
                'angebotsfrist' => '2025-11-04',
                'kurzbezeichnung' => 'Unternehmensberatung Digitalisierung',
                'typ' => 'Verhandlungsverfahren',
                'vergabeplattform' => 'Vergabe24',
                'geschaetzter_auftragswert' => 195000.00,
                'beschreibung' => 'Beratungsleistungen zur digitalen Transformation der Verwaltung.',
                'cpv_codes' => ['79400000', '79410000'],
            ],
            [
                'veroeffentlicht' => '2025-10-11',
                'angebotsfrist' => '2025-12-02',
                'kurzbezeichnung' => 'Rechtsberatung Vergaberecht',
                'typ' => 'Verhandlungsverfahren',
                'vergabeplattform' => 'dtvp.de',
                'geschaetzter_auftragswert' => 85000.00,
                'beschreibung' => 'Juristische Beratung bei komplexen Vergabeverfahren.',
                'cpv_codes' => ['79100000'],
            ],

            // Energie & Versorgung (CPV 09xxxxxx, 31xxxxxx)
            [
                'veroeffentlicht' => '2025-09-07',
                'angebotsfrist' => '2025-10-27',
                'kurzbezeichnung' => 'Stromlieferung kommunale Liegenschaften',
                'typ' => 'Offenes Verfahren',
                'vergabeplattform' => 'bund.de',
                'geschaetzter_auftragswert' => 1200000.00,
                'beschreibung' => 'Stromversorgung für alle städtischen Gebäude, Laufzeit 2 Jahre.',
                'cpv_codes' => ['09310000'],
            ],
            [
                'veroeffentlicht' => '2025-09-12',
                'angebotsfrist' => '2025-11-01',
                'kurzbezeichnung' => 'Photovoltaik-Anlagen Schuldächer',
                'typ' => 'Offenes Verfahren',
                'vergabeplattform' => 'Vergabe24',
                'geschaetzter_auftragswert' => 890000.00,
                'beschreibung' => 'Installation von PV-Anlagen auf 8 Schuldächern inkl. Speichersystemen.',
                'cpv_codes' => ['09331000', '31000000'],
            ],
            [
                'veroeffentlicht' => '2025-10-12',
                'angebotsfrist' => '2025-12-03',
                'kurzbezeichnung' => 'LED-Straßenbeleuchtung Umrüstung',
                'typ' => 'Offenes Verfahren',
                'vergabeplattform' => 'dtvp.de',
                'geschaetzter_auftragswert' => 520000.00,
                'beschreibung' => 'Umrüstung der Straßenbeleuchtung auf energiesparende LED-Technik.',
                'cpv_codes' => ['31000000', '31500000'],
            ],

            // Sicherheit (CPV 35xxxxxx)
            [
                'veroeffentlicht' => '2025-09-16',
                'angebotsfrist' => '2025-11-05',
                'kurzbezeichnung' => 'Feuerwehr-Ausrüstung und Schutzkleidung',
                'typ' => 'Offenes Verfahren',
                'vergabeplattform' => 'bund.de',
                'geschaetzter_auftragswert' => 175000.00,
                'beschreibung' => 'Beschaffung von Schutzausrüstung und Atemschutzgeräten für Feuerwehr.',
                'cpv_codes' => ['35000000', '35113000'],
            ],
            [
                'veroeffentlicht' => '2025-10-13',
                'angebotsfrist' => '2025-12-04',
                'kurzbezeichnung' => 'Videoüberwachungssystem Bahnhof',
                'typ' => 'Verhandlungsverfahren',
                'vergabeplattform' => 'Vergabe24',
                'geschaetzter_auftragswert' => 240000.00,
                'beschreibung' => 'Installation eines modernen Videoüberwachungssystems am Hauptbahnhof.',
                'cpv_codes' => ['35000000', '35120000'],
            ],

            // Catering & Verpflegung (CPV 55xxxxxx)
            [
                'veroeffentlicht' => '2025-09-18',
                'angebotsfrist' => '2025-11-08',
                'kurzbezeichnung' => 'Schulverpflegung Ganztagsschulen',
                'typ' => 'Offenes Verfahren',
                'vergabeplattform' => 'dtvp.de',
                'geschaetzter_auftragswert' => 340000.00,
                'beschreibung' => 'Bereitstellung von Mittagessen für 5 Ganztagsschulen, Laufzeit 2 Jahre.',
                'cpv_codes' => ['55520000', '55523100'],
            ],
            [
                'veroeffentlicht' => '2025-10-14',
                'angebotsfrist' => '2025-12-05',
                'kurzbezeichnung' => 'Kantinenbetrieb Rathaus',
                'typ' => 'Verhandlungsverfahren',
                'vergabeplattform' => 'bund.de',
                'geschaetzter_auftragswert' => 210000.00,
                'beschreibung' => 'Bewirtschaftung der Rathauskantine für 3 Jahre.',
                'cpv_codes' => ['55000000', '55320000'],
            ],

            // Druck & Papier (CPV 30xxxxxx, 79xxxxxx)
            [
                'veroeffentlicht' => '2025-09-19',
                'angebotsfrist' => '2025-11-09',
                'kurzbezeichnung' => 'Druckleistungen Amtsblatt',
                'typ' => 'Offenes Verfahren',
                'vergabeplattform' => 'Vergabe24',
                'geschaetzter_auftragswert' => 95000.00,
                'beschreibung' => 'Druck und Vertrieb des monatlichen Amtsblatts für 2 Jahre.',
                'cpv_codes' => ['79800000', '79810000'],
            ],
            [
                'veroeffentlicht' => '2025-10-15',
                'angebotsfrist' => '2025-12-06',
                'kurzbezeichnung' => 'Büromaterial und Papier',
                'typ' => 'Offenes Verfahren',
                'vergabeplattform' => 'dtvp.de',
                'geschaetzter_auftragswert' => 65000.00,
                'beschreibung' => 'Rahmenvertrag Büromaterial für alle städtischen Dienststellen.',
                'cpv_codes' => ['30100000', '30190000'],
            ],

            // Sonstige
            [
                'veroeffentlicht' => '2025-09-20',
                'angebotsfrist' => '2025-11-10',
                'kurzbezeichnung' => 'Telefonanlagen und Kommunikationstechnik',
                'typ' => 'Verhandlungsverfahren',
                'vergabeplattform' => 'bund.de',
                'geschaetzter_auftragswert' => 285000.00,
                'beschreibung' => 'Erneuerung der Telefonanlagen in 12 Verwaltungsgebäuden.',
                'cpv_codes' => ['32000000', '32500000'],
            ],
            [
                'veroeffentlicht' => '2025-09-22',
                'angebotsfrist' => '2025-11-12',
                'kurzbezeichnung' => 'Versicherungsleistungen Fuhrpark',
                'typ' => 'Offenes Verfahren',
                'vergabeplattform' => 'Vergabe24',
                'geschaetzter_auftragswert' => 120000.00,
                'beschreibung' => 'Versicherung des kommunalen Fuhrparks für 3 Jahre.',
                'cpv_codes' => ['66000000', '66510000'],
            ],
            [
                'veroeffentlicht' => '2025-09-25',
                'angebotsfrist' => '2025-11-15',
                'kurzbezeichnung' => 'Abfallentsorgung Stadtgebiet',
                'typ' => 'Offenes Verfahren',
                'vergabeplattform' => 'dtvp.de',
                'geschaetzter_auftragswert' => 780000.00,
                'beschreibung' => 'Müllabfuhr und Entsorgungsdienstleistungen für 4 Jahre.',
                'cpv_codes' => ['90500000', '90511000'],
            ],
            [
                'veroeffentlicht' => '2025-09-28',
                'angebotsfrist' => '2025-11-18',
                'kurzbezeichnung' => 'Gutachterleistungen Immobilienbewertung',
                'typ' => 'Verhandlungsverfahren',
                'vergabeplattform' => 'bund.de',
                'geschaetzter_auftragswert' => 75000.00,
                'beschreibung' => 'Erstellung von Verkehrswertgutachten für kommunale Liegenschaften.',
                'cpv_codes' => ['71000000', '71300000'],
            ],
            [
                'veroeffentlicht' => '2025-10-01',
                'angebotsfrist' => '2025-11-20',
                'kurzbezeichnung' => 'Personalvermittlung IT-Fachkräfte',
                'typ' => 'Offenes Verfahren',
                'vergabeplattform' => 'Vergabe24',
                'geschaetzter_auftragswert' => 155000.00,
                'beschreibung' => 'Vermittlung von IT-Spezialisten für befristete Projekte.',
                'cpv_codes' => ['79000000', '79600000'],
            ],
        ];

        foreach ($mockData as $data) {
            $cpvCodes = $data['cpv_codes'];
            unset($data['cpv_codes']);

            $bekanntmachung = Bekanntmachung::create($data);

            // Attach CPV codes
            foreach ($cpvCodes as $code) {
                // Only attach if CPV code exists in database
                if (CpvCode::where('code', $code)->exists()) {
                    $bekanntmachung->cpvCodes()->attach($code);
                }
            }
        }

        $this->command->info('Bekanntmachungen seeded successfully with ' . count($mockData) . ' records.');
    }
}

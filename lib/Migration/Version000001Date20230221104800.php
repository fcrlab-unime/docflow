<?php

namespace OCA\Docflow\Migration;

use Closure;
use OCP\DB\ISchemaWrapper;
use OCP\Migration\SimpleMigrationStep;
use OCP\Migration\IOutput;

// mapper imports
use OCA\Docflow\Db\SensitiveDataMapper;
use OCA\Docflow\Db\MethodsMapper;
use OCA\Docflow\Db\MethodsDataMapper;
use OCA\Docflow\Db\TagMapper;
use OCP\IDBConnection;

class Version000001Date20230221104800 extends SimpleMigrationStep
{

    // CREATE DATABASE TABLES

    /**
     * @param IOutput $output
     * @param Closure $schemaClosure The `\Closure` returns a `ISchemaWrapper`
     * @param array $options
     * @return null|ISchemaWrapper
     */
    public function changeSchema(IOutput $output, Closure $schemaClosure, array $options)
    {
        /** @var ISchemaWrapper $schema */
        $schema = $schemaClosure();

        // 1. TABELLA SU COSA ANONIMIZZARE
        if (!$schema->hasTable('docflow_sensitive_data')) {

            $table = $schema->createTable('docflow_sensitive_data');

            $table->addColumn('sensitive_data_id', 'integer', [
                'autoincrement' => true,
                'notnull' => true,
            ]);
            $table->addColumn('data', 'string', [
                'notnull' => true,
                'length' => 200
            ]);
            $table->addColumn('exclusivity_s', 'integer', [
                'notnull' => true,
            ]);

            $table->setPrimaryKey(['sensitive_data_id']);
            $table->addUniqueIndex(array('data'));
            //$table->unique('data');
        }

        // 2. TABELLA SU COME ANONIMIZZARE
        if (!$schema->hasTable('docflow_methods')) {

            $table = $schema->createTable('docflow_methods');

            $table->addColumn('methods_id', 'integer', [
                'autoincrement' => true,
                'notnull' => true,
            ]);
            $table->addColumn('desc', 'string', [
                'notnull' => true,
                'length' => 200
            ]);
            $table->addColumn('exclusivity_m', 'integer', [
                'notnull' => true,
            ]);

            $table->setPrimaryKey(['methods_id']);
            $table->addUniqueIndex(array('desc'));
            //$table->unique('desc');
        }

        // 3. TABELLA PER OTTENERE GLI SCRIPT CHE ANONIMIZZANO QUEL DATO SENSIBILE USANTO QUEL METODO
        if (!$schema->hasTable('docflow_methods_data')) {

            $table = $schema->createTable('docflow_methods_data');

            $table->addColumn('methods_data_id', 'integer', [
                'autoincrement' => true,
                'notnull' => true,
            ]);
            $table->addColumn('sensitive_data_id', 'integer', [
                'notnull' => true,
            ]);
            $table->addColumn('methods_id', 'integer', [
                'notnull' => true,
            ]);
            $table->addColumn('path', 'string', [
                'notnull' => true,
                'length' => 2000
            ]);
            $table->addColumn('default', 'integer', [
                'notnull' => true,
            ]);
            $table->addColumn('tag', 'string', [
                'notnull' => true,
                'length' => 2000
            ]);

            $table->setPrimaryKey(['methods_data_id']);
            $table->addForeignKeyConstraint($schema->getTable('docflow_sensitive_data'), ['sensitive_data_id'], ['sensitive_data_id']);
            $table->addForeignKeyConstraint($schema->getTable('docflow_methods'), ['methods_id'], ['methods_id']);
            $table->addUniqueIndex(array('sensitive_data_id', 'methods_id'));
            //$table->unique(['sensitive_data_id', 'methods_id', 'path']);
        }

        // 4. TABELLA DEI TAG
        if (!$schema->hasTable('docflow_tag')) {

            $table = $schema->createTable('docflow_tag');

            $table->addColumn('tag_id', 'integer', [
                'autoincrement' => true,
                'notnull' => true,
            ]);
            $table->addColumn('label', 'string', [
                'notnull' => true,
                'length' => 200
            ]);
            $table->addColumn('tag_string', 'string', [
                'notnull' => true,
                'length' => 200
            ]);

            $table->setPrimaryKey(['tag_id']);
            $table->addUniqueIndex(array('label'));
            $table->addUniqueIndex(array('tag_string'));
        }

        return $schema;
    }


    // INSERT DATA
    public function postSchemaChange(IOutput $output, Closure $schemaClosure, array $options)
    {

        /** @var ISchemaWrapper $schema */
        $schema = $schemaClosure();

        // ADD SENSITIVE DATA
        if ($schema->hasTable('docflow_sensitive_data')) {

            try {

                $mapper = new SensitiveDataMapper(\OC::$server->get(IDBConnection::class));

                $mapper->create("Indirizzo", 0);
                $mapper->create("Dati biometrici", 0);
                $mapper->create("Data", 0);
                $mapper->create("Comunicazioni elettroniche", 0);
                $mapper->create("Origine etnica", 0);
                $mapper->create("Identità di genere", 0);
                $mapper->create("Dati genetici", 0);
                $mapper->create("Geolocalizzazione", 0);
                $mapper->create("Dati sanitari", 0);
                $mapper->create("Indirizzo IP", 0);
                $mapper->create("Provvedimenti giuridici suscettibili di iscrizione nel casellario giudiziale", 0);
                $mapper->create("Targa", 0);
                $mapper->create("Nome e cognome", 0);
                $mapper->create("Altro", 0);
                $mapper->create("Convinzioni filosofiche", 0);
                $mapper->create("Luogo", 0);
                $mapper->create("Opinione politica", 0);
                $mapper->create("Qualità di imputato", 0);
                $mapper->create("Qualità di indagato", 0);
                $mapper->create("Origine razziale", 0);
                $mapper->create("Credenza religiosa", 0);
                $mapper->create("Vita sessuale", 0);
                $mapper->create("Orientamento sessuale", 0);
                $mapper->create("Codice fiscale", 0);
                $mapper->create("Appartenenza sindacale", 0);
                $mapper->create("Tutto in chiaro", 1);
            } catch (\Throwable $e) {
                //do nothing
            }
        }

        // ADD METHODS
        if ($schema->hasTable('docflow_methods')) {

            try {

                $mapper = new MethodsMapper(\OC::$server->get(IDBConnection::class));

                $mapper->create("Totale", 1);
                $mapper->create("Numero civico", 0);
                $mapper->create("Comune", 0);
                $mapper->create("Provincia", 0);
                $mapper->create("Via/Piazza", 0);
                $mapper->create("CAP", 0);
                $mapper->create("Data", 0);
                $mapper->create("Giorno", 0);
                $mapper->create("Mese", 0);
                $mapper->create("Ultimo ottetto", 0);
                $mapper->create("Ultimi due ottetti",2);
                $mapper->create("Ultimi tre ottetti",3);
                $mapper->create("Solo iniziali", 0);
                $mapper->create("Nome e cognome", 0); // x codice fiscale
                $mapper->create("Chiaro", 0);
            } catch (\Throwable $e) {
                //do nothing
            }
        }

        // ADD TAGS
        if ($schema->hasTable('docflow_tag')) {

            try {

                $mapper = new TagMapper(\OC::$server->get(IDBConnection::class));

                $mapper->create("Address - Housenumber", "{.personaldata .address .housenumber}");
                $mapper->create("Address - Municipality", "{.personaldata .address .municipality}");
                $mapper->create("Address - Province", "{.personaldata .address .province}");
                $mapper->create("Address - Street/Square", "{.personaldata .address .streetsquare}");
                $mapper->create("Address - Zipcode", "{.personaldata .address .zipcode}");
                $mapper->create("Biometric Data", "{.personaldata .biometricdata}");
                $mapper->create("Year", "{.personaldata .date .year}");
                $mapper->create("Month", "{.personaldata .date .month}");
                $mapper->create("Day", "{.personaldata .date .day}");
                $mapper->create("Electronic Communications", "{.personaldata .electroniccommunications}");
                $mapper->create("Ethnicity", "{.personaldata .ethnicity}");
                $mapper->create("Gender Identity", "{.personaldata .genderidentity}");
                $mapper->create("Genetic Data", "{.personaldata .geneticdata}");
                $mapper->create("Geolocation", "{.personaldata .geolocation}"); 
                $mapper->create("Health Data", "{.personaldata .healthdata}"); 
                $mapper->create("IP Address", "{.personaldata .ipaddress}"); 
                $mapper->create("Judicial Order", "{.personaldata .judicialorder}"); 
                $mapper->create("License Plate", "{.personaldata .licenseplate}"); 
                $mapper->create("Name/Surname", "{.personaldata .name}"); 
                $mapper->create("Philosophical Convictions", "{.personaldata .philosophicalconvictions}"); 
                $mapper->create("Place - Municipality", "{.personaldata .place .municipality}"); 
                $mapper->create("Place - Province", "{.personaldata .place .province}"); 
                $mapper->create("Political Opinion", "{.personaldata .politicalopinion}"); 
                $mapper->create("Quality of Accused", "{.personaldata .qualityofaccused}"); 
                $mapper->create("Quality of Suspected", "{.personaldata .qualityofsuspected}"); 
                $mapper->create("Racial Origin", "{.personaldata .racialorigin}"); 
                $mapper->create("Religious Belief", "{.personaldata .religiousbelief}"); 
                $mapper->create("Sex Life", "{.personaldata .sexlife}"); 
                $mapper->create("Sexual Orientation", "{.personaldata .sexualorientation}"); 
                $mapper->create("Taxcode", "{.personaldata .taxcode}"); 
                $mapper->create("Tradeunion Membership", "{.personaldata .tradeunionmembership}"); 
                $mapper->create("Other Data", "{.personaldata .otherdata}"); 
            } catch (\Throwable $e) {
                //do nothing
            }
        }

        // ADD METHODS DATA
        if ($schema->hasTable('docflow_methods_data')) {

            try {

                $mapperSensitiveData = new SensitiveDataMapper(\OC::$server->get(IDBConnection::class));
                $mapperMethods = new MethodsMapper(\OC::$server->get(IDBConnection::class));
                $mapperTag = new TagMapper(\OC::$server->get(IDBConnection::class));
                $mapperMethodsData = new MethodsDataMapper(\OC::$server->get(IDBConnection::class));

                // GET SENSITIVE DATA: 
                $sdIndirizzo = $mapperSensitiveData->findByData("Indirizzo");
                $sdDatiBiometrici = $mapperSensitiveData->findByData("Dati biometrici");
                $sdData = $mapperSensitiveData->findByData("Data");
                $sdComunicazioniElettroniche = $mapperSensitiveData->findByData("Comunicazioni elettroniche");
                $sdOrigineEtnica = $mapperSensitiveData->findByData("Origine etnica");
                $sdIdentitaGenere = $mapperSensitiveData->findByData("Identità di genere");
                $sdDatiGenetici = $mapperSensitiveData->findByData("Dati genetici");
                $sdGeolocalizzazione = $mapperSensitiveData->findByData("Geolocalizzazione");
                $sdDatiSanitari = $mapperSensitiveData->findByData("Dati sanitari");
                $sdIndirizzoIp = $mapperSensitiveData->findByData("Indirizzo IP");
                $sdProvGiud = $mapperSensitiveData->findByData("Provvedimenti giuridici suscettibili di iscrizione nel casellario giudiziale");
                $sdTarga = $mapperSensitiveData->findByData("Targa");
                $sdNomeCognome = $mapperSensitiveData->findByData("Nome e cognome");
                $sdAltro = $mapperSensitiveData->findByData("Altro");
                $sdConvFilos = $mapperSensitiveData->findByData("Convinzioni filosofiche");
                $sdLuogo = $mapperSensitiveData->findByData("Luogo");
                $sdOpinionePolitica = $mapperSensitiveData->findByData("Opinione politica");
                $sdQualitImputato = $mapperSensitiveData->findByData("Qualità di imputato");
                $sdQualitIndagato = $mapperSensitiveData->findByData("Qualità di indagato");
                $sdOrigRazziale = $mapperSensitiveData->findByData("Origine razziale");
                $sdCredReligiosa = $mapperSensitiveData->findByData("Credenza religiosa");
                $sdVitaSessuale = $mapperSensitiveData->findByData("Vita sessuale");
                $sdOrientSessuale = $mapperSensitiveData->findByData("Orientamento sessuale");
                $sdCodFiscale = $mapperSensitiveData->findByData("Codice fiscale");
                $sdAppSindacale = $mapperSensitiveData->findByData("Appartenenza sindacale");
                $sdChiaro = $mapperSensitiveData->findByData("Tutto in chiaro");

                // GET METHODS:
                $mTotale = $mapperMethods->findByDesc("Totale");
                $mNumCivico = $mapperMethods->findByDesc("Numero civico");
                $mComune = $mapperMethods->findByDesc("Comune");
                $mProvincia = $mapperMethods->findByDesc("Provincia");
                $mViaPiazza = $mapperMethods->findByDesc("Via/Piazza");
                $mCap = $mapperMethods->findByDesc("CAP");
                $mData = $mapperMethods->findByDesc("Data");
                $mGiorno = $mapperMethods->findByDesc("Giorno");
                $mMese = $mapperMethods->findByDesc("Mese");
                $mUltimoOtt = $mapperMethods->findByDesc("Ultimo ottetto");
                $mUltimiDueOtt = $mapperMethods->findByDesc("Ultimi due ottetti");
                $mUltimiTreOtt = $mapperMethods->findByDesc("Ultimi tre ottetti");
                $mSoloIniziali = $mapperMethods->findByDesc("Solo iniziali");
                $mNomeCognome = $mapperMethods->findByDesc("Nome e cognome"); // x codice fiscale
                $mChiaro = $mapperMethods->findByDesc("Chiaro");

                // GET TAGS:
                $tHouseNumber = $mapperTag->findByLabel("Address - Housenumber");
                $tMunicipalityAddress = $mapperTag->findByLabel("Address - Municipality");
                $tProvinceAddress = $mapperTag->findByLabel("Address - Province");
                $tStreetSquare = $mapperTag->findByLabel("Address - Street/Square");
                $tZipcode = $mapperTag->findByLabel("Address - Zipcode");
                $tBiometricData = $mapperTag->findByLabel("Biometric Data");
                $tYear = $mapperTag->findByLabel("Year");
                $tMonth = $mapperTag->findByLabel("Month");
                $tDay = $mapperTag->findByLabel("Day");
                $tElectronicCommunications = $mapperTag->findByLabel("Electronic Communications");
                $tEthnicity = $mapperTag->findByLabel("Ethnicity");
                $tGenderIdentity = $mapperTag->findByLabel("Gender Identity");
                $tGeneticData = $mapperTag->findByLabel("Genetic Data");
                $tGeolocation = $mapperTag->findByLabel("Geolocation"); 
                $tHealthData = $mapperTag->findByLabel("Health Data"); 
                $tIPAddress = $mapperTag->findByLabel("IP Address"); 
                $tJudicialOrder = $mapperTag->findByLabel("Judicial Order"); 
                $tLicensePlate = $mapperTag->findByLabel("License Plate"); 
                $tNameSurname = $mapperTag->findByLabel("Name/Surname"); 
                $tPhilosophicalConvictions = $mapperTag->findByLabel("Philosophical Convictions"); 
                $tMunicipalityPlace = $mapperTag->findByLabel("Place - Municipality"); 
                $tProvincePlace = $mapperTag->findByLabel("Place - Province"); 
                $tPoliticalOpinion = $mapperTag->findByLabel("Political Opinion"); 
                $tQualityOfAccused = $mapperTag->findByLabel("Quality of Accused"); 
                $tQualityOfSuspected = $mapperTag->findByLabel("Quality of Suspected"); 
                $tRacialOrigin = $mapperTag->findByLabel("Racial Origin"); 
                $tReligiousBelief = $mapperTag->findByLabel("Religious Belief"); 
                $tSexLife = $mapperTag->findByLabel("Sex Life"); 
                $tSexualOrientation = $mapperTag->findByLabel("Sexual Orientation"); 
                $tTaxcode = $mapperTag->findByLabel("Taxcode"); 
                $tTradeunionMembership = $mapperTag->findByLabel("Tradeunion Membership"); 
                $tOtherData = $mapperTag->findByLabel("Other Data"); 

                // ADD ELEMENTS

                // create(int $sensitiveDataId, int $methodsId, string $path)
                $prefix = "./custom_apps/docflow/pandoc/filters/";

                // INDIRIZZO:
                $mapperMethodsData->create($sdIndirizzo->getSensitiveDataId(), $mTotale->getMethodsId(), $prefix . 'address/anon-total.lua', 1, $tHouseNumber->getTagString() . ';' . $tMunicipalityAddress->getTagString() . ';' . $tProvinceAddress->getTagString() . ';' . $tStreetSquare->getTagString() . ';' . $tZipcode->getTagString());
                $mapperMethodsData->create($sdIndirizzo->getSensitiveDataId(), $mNumCivico->getMethodsId(), $prefix . 'address/anon-housenumber.lua', 0, $tHouseNumber->getTagString());
                $mapperMethodsData->create($sdIndirizzo->getSensitiveDataId(), $mComune->getMethodsId(), $prefix . 'address/anon-municipality.lua', 0, $tMunicipalityAddress->getTagString());
                $mapperMethodsData->create($sdIndirizzo->getSensitiveDataId(), $mProvincia->getMethodsId(), $prefix . 'address/anon-province.lua', 0, $tProvinceAddress->getTagString());
                $mapperMethodsData->create($sdIndirizzo->getSensitiveDataId(), $mViaPiazza->getMethodsId(), $prefix . 'address/anon-streetsquare.lua', 0, $tStreetSquare->getTagString());
                $mapperMethodsData->create($sdIndirizzo->getSensitiveDataId(), $mCap->getMethodsId(), $prefix . 'address/anon-zipcode.lua', 0, $tZipcode->getTagString());

                // DATI BIOMETRICI:
                $mapperMethodsData->create($sdDatiBiometrici->getSensitiveDataId(), $mTotale->getMethodsId(), $prefix . 'biometricdata/anon-total.lua', 1, $tBiometricData->getTagString());

                // DATA:
                $mapperMethodsData->create($sdData->getSensitiveDataId(), $mTotale->getMethodsId(), $prefix . 'date/anon-total.lua', 1, $tYear->getTagString() . ';' . $tMonth->getTagString() . ';' . $tDay->getTagString());
                $mapperMethodsData->create($sdData->getSensitiveDataId(), $mMese->getMethodsId(), $prefix . 'date/anon-month.lua', 0, $tMonth->getTagString());
                $mapperMethodsData->create($sdData->getSensitiveDataId(), $mGiorno->getMethodsId(), $prefix . 'date/anon-day.lua', 0, $tDay->getTagString());

                // COMUNICAZIONI ELETTRONICHE
                $mapperMethodsData->create($sdComunicazioniElettroniche->getSensitiveDataId(), $mTotale->getMethodsId(), $prefix . 'electroniccommunications/anon-total.lua', 1, $tElectronicCommunications->getTagString());

                // ORIGINE ETNICA
                $mapperMethodsData->create($sdOrigineEtnica->getSensitiveDataId(), $mTotale->getMethodsId(), $prefix . 'ethnicity/anon-total.lua', 1, $tEthnicity->getTagString());

                // IDENTITA DI GENERE
                $mapperMethodsData->create($sdIdentitaGenere->getSensitiveDataId(), $mTotale->getMethodsId(), $prefix . 'genderidentity/anon-total.lua', 1, $tGenderIdentity->getTagString());

                // DATI GENETICI
                $mapperMethodsData->create($sdDatiGenetici->getSensitiveDataId(), $mTotale->getMethodsId(), $prefix . 'geneticdata/anon-total.lua', 1, $tGeneticData->getTagString());

                // GEOLOCALIZZAZIONE
                $mapperMethodsData->create($sdGeolocalizzazione->getSensitiveDataId(), $mTotale->getMethodsId(), $prefix . 'geolocation/anon-total.lua', 1, $tGeolocation->getTagString());

                // DATI SANITARI
                $mapperMethodsData->create($sdDatiSanitari->getSensitiveDataId(), $mTotale->getMethodsId(), $prefix . 'healthdata/anon-total.lua', 1, $tHealthData->getTagString());

                // INDIRIZZO IP
                $mapperMethodsData->create($sdIndirizzoIp->getSensitiveDataId(), $mTotale->getMethodsId(), $prefix . 'ipaddress/anon-total.lua', 1, $tIPAddress->getTagString());
                $mapperMethodsData->create($sdIndirizzoIp->getSensitiveDataId(), $mUltimoOtt->getMethodsId(), $prefix . 'ipaddress/anon-liv-1.lua', 0, $tIPAddress->getTagString());
                $mapperMethodsData->create($sdIndirizzoIp->getSensitiveDataId(), $mUltimiDueOtt->getMethodsId(), $prefix . 'ipaddress/anon-liv-2.lua', 0, $tIPAddress->getTagString());
                $mapperMethodsData->create($sdIndirizzoIp->getSensitiveDataId(), $mUltimiTreOtt->getMethodsId(), $prefix . 'ipaddress/anon-liv-3.lua', 0, $tIPAddress->getTagString());

                // PROVVEDIMENTI GIURIDICI SUSCETTIBILI DI ISCRIZIONE NEL CASELLARIO GIUDIZIALE
                $mapperMethodsData->create($sdProvGiud->getSensitiveDataId(), $mTotale->getMethodsId(), $prefix . 'judicialorder/anon-total.lua', 1, $tJudicialOrder->getTagString());

                // TARGA
                $mapperMethodsData->create($sdTarga->getSensitiveDataId(), $mTotale->getMethodsId(), $prefix . 'licenseplate/anon-total.lua', 1, $tLicensePlate->getTagString());

                // NOME E COGNOME
                $mapperMethodsData->create($sdNomeCognome->getSensitiveDataId(), $mTotale->getMethodsId(), $prefix . 'name/anon-total.lua', 1, $tNameSurname->getTagString());
                $mapperMethodsData->create($sdNomeCognome->getSensitiveDataId(), $mSoloIniziali->getMethodsId(), $prefix . 'name/anon-initials.lua', 0, $tNameSurname->getTagString());

                // ALTRO
                $mapperMethodsData->create($sdAltro->getSensitiveDataId(), $mTotale->getMethodsId(), $prefix . 'otherdata/anon-total.lua', 1, $tOtherData->getTagString());

                // CONVINZIONE FILOSOFICA
                $mapperMethodsData->create($sdConvFilos->getSensitiveDataId(), $mTotale->getMethodsId(), $prefix . 'philosophicalconvictions/anon-total.lua', 1, $tPhilosophicalConvictions->getTagString());

                // LUOGO
                $mapperMethodsData->create($sdLuogo->getSensitiveDataId(), $mTotale->getMethodsId(),  $prefix . 'place/anon-total.lua', 1, $tMunicipalityPlace->getTagString() .  ';' . $tProvincePlace->getTagString());
                $mapperMethodsData->create($sdLuogo->getSensitiveDataId(), $mComune->getMethodsId(), $prefix . 'place/anon-municipality.lua', 0, $tMunicipalityPlace->getTagString());

                // OPINIONE POLITICA
                $mapperMethodsData->create($sdOpinionePolitica->getSensitiveDataId(), $mTotale->getMethodsId(), $prefix . 'politicalopinion/anon-total.lua', 1, $tPoliticalOpinion->getTagString());

                // QUALITA' IMPUTATO
                $mapperMethodsData->create($sdQualitImputato->getSensitiveDataId(), $mTotale->getMethodsId(), $prefix . 'qualityofaccused/anon-total.lua', 1, $tQualityOfAccused->getTagString());

                // QUALITA' INDAGATO
                $mapperMethodsData->create($sdQualitIndagato->getSensitiveDataId(), $mTotale->getMethodsId(), $prefix . 'qualityofsuspected/anon-total.lua', 1, $tQualityOfSuspected->getTagString());

                // ORIGINI RAZIALI
                $mapperMethodsData->create($sdOrigRazziale->getSensitiveDataId(), $mTotale->getMethodsId(), $prefix . 'racialorigin/anon-total.lua', 1, $tRacialOrigin->getTagString());

                // CREDENZE RELIGIOSE
                $mapperMethodsData->create($sdCredReligiosa->getSensitiveDataId(), $mTotale->getMethodsId(), $prefix . 'religiousbelief/anon-total.lua', 1, $tReligiousBelief->getTagString());

                // VITA SESSUALE
                $mapperMethodsData->create($sdVitaSessuale->getSensitiveDataId(), $mTotale->getMethodsId(), $prefix . 'sexlife/anon-total.lua', 1, $tSexLife->getTagString());

                // ORIENTAMENTO SESSUALE                
                $mapperMethodsData->create($sdOrientSessuale->getSensitiveDataId(), $mTotale->getMethodsId(), $prefix . 'sexualorientation/anon-total.lua', 1, $tSexualOrientation->getTagString());

                // CODICE FISCALE
                $mapperMethodsData->create($sdCodFiscale->getSensitiveDataId(), $mTotale->getMethodsId(), $prefix . 'taxcode/anon-total.lua', 1, $tTaxcode->getTagString());
                $mapperMethodsData->create($sdCodFiscale->getSensitiveDataId(), $mData->getMethodsId(), $prefix . 'taxcode/anon-date.lua', 0, $tTaxcode->getTagString());
                $mapperMethodsData->create($sdCodFiscale->getSensitiveDataId(), $mGiorno->getMethodsId(), $prefix . 'taxcode/anon-day.lua', 0, $tTaxcode->getTagString());
                $mapperMethodsData->create($sdCodFiscale->getSensitiveDataId(), $mSoloIniziali->getMethodsId(), $prefix . 'taxcode/anon-initials.lua', 0, $tTaxcode->getTagString());
                $mapperMethodsData->create($sdCodFiscale->getSensitiveDataId(), $mMese->getMethodsId(), $prefix . 'taxcode/anon-month.lua', 0, $tTaxcode->getTagString());
                $mapperMethodsData->create($sdCodFiscale->getSensitiveDataId(), $mComune->getMethodsId(), $prefix . 'taxcode/anon-municipality.lua', 0, $tTaxcode->getTagString());
                $mapperMethodsData->create($sdCodFiscale->getSensitiveDataId(), $mNomeCognome->getMethodsId(), $prefix . 'taxcode/anon-name.lua', 0, $tTaxcode->getTagString());

                // APPARTENENZA SINDACALE
                $mapperMethodsData->create($sdAppSindacale->getSensitiveDataId(), $mTotale->getMethodsId(), $prefix . 'tradeunionmembership/anon-total.lua', 1, $tTradeunionMembership->getTagString());

                // IN CHIARO
                $mapperMethodsData->create($sdChiaro->getSensitiveDataId(), $mChiaro->getMethodsId(), $prefix . 'clear-all.lua', 1, $tHouseNumber->getTagString() . ';' . $tMunicipalityAddress->getTagString() . ';' . $tProvinceAddress->getTagString() . ';' . $tStreetSquare->getTagString() . ';' . $tZipcode->getTagString() . ';' . $tBiometricData->getTagString() . ';' . $tYear->getTagString() . ';' . $tMonth->getTagString() . ';' . $tDay->getTagString() . ';' . $tElectronicCommunications->getTagString() . ';' . $tEthnicity->getTagString() . ';' . $tGenderIdentity->getTagString() . ';' . $tGeneticData->getTagString() . ';' . $tGeolocation->getTagString() . ';' . $tHealthData->getTagString() . ';' . $tIPAddress->getTagString() . ';' . $tJudicialOrder->getTagString() . ';' . $tLicensePlate->getTagString() . ';' . $tNameSurname->getTagString() . ';' . $tOtherData->getTagString() . ';' . $tPhilosophicalConvictions->getTagString() . ';' . $tMunicipalityPlace->getTagString() . ';' . $tProvincePlace->getTagString() . ';' . $tPoliticalOpinion->getTagString() . ';' . $tQualityOfAccused->getTagString() . ';' . $tQualityOfSuspected->getTagString() . ';' . $tRacialOrigin->getTagString() . ';' . $tReligiousBelief->getTagString() . ';' . $tSexLife->getTagString() . ';' . $tSexualOrientation->getTagString() . ';' . $tTaxcode->getTagString() . ';' . $tTradeunionMembership->getTagString());
                

            } catch (\Throwable $e) {
                //do nothing
            }
        }
    }
}

<?php


use App\Models\License;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $xml=simplexml_load_string($this->countries());
        $text = $xml->option[0]->text;
        foreach ($xml->children() as $value) {
            $text = $value->__toString();
            if($text != '---'){
                DB::table('country')->insert(['name' => $text]);
            }
        }
        return;
        $this->call([
            // RoleSeeder::class,
            // CompanySeeder::class,
            UserSeeder::class
        ]);

        License::create(['title' => '240 (salud)']);
        License::create(['title' => '214 (vida)']);
        License::create(['title' => '215  (salud y vida)']);
        License::create(['title' => '440  (customer services)']);
        License::create(['title' => '220  (Property & Casualty)']);
    }

    public function countries(){
        return '<select name="primary-citizenship" class="wpcf7-form-control wpcf7-select" aria-invalid="false"><option value="">---</option><option value="Aruba">Aruba</option><option value="Afganistán">Afganistán</option><option value="Angola">Angola</option><option value="Anguila">Anguila</option><option value="Islas de Åland">Islas de Åland</option><option value="Albania">Albania</option><option value="Andorra">Andorra</option><option value="Emiratos Árabes Unidos">Emiratos Árabes Unidos</option><option value="Argentina">Argentina</option><option value="Armenia">Armenia</option><option value="Samoa Americana">Samoa Americana</option><option value="Antarctica">Antarctica</option><option value="Territorios Australes y Antárticas Franceses">Territorios Australes y Antárticas Franceses</option><option value="Antigua and Barbuda">Antigua and Barbuda</option><option value="Australia">Australia</option><option value="Austria">Austria</option><option value="Azerbayán">Azerbayán</option><option value="Burundi">Burundi</option><option value="Bélgica">Bélgica</option><option value="Benín">Benín</option><option value="Bonaire, San Eustaquio y Saba">Bonaire, San Eustaquio y Saba</option><option value="Burkina Faso">Burkina Faso</option><option value="Bangladesh">Bangladesh</option><option value="Bulgaria">Bulgaria</option><option value="Bahrein">Bahrein</option><option value="Bahamas">Bahamas</option><option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option><option value="San Bartolomé">San Bartolomé</option><option value="Bielorrusia">Bielorrusia</option><option value="Belice">Belice</option><option value="Islas Bermudas">Islas Bermudas</option><option value="Bolivia">Bolivia</option><option value="Brasil">Brasil</option><option value="Barbados">Barbados</option><option value="Brunéi">Brunéi</option><option value="Bhután">Bhután</option><option value="Isla Bouvet">Isla Bouvet</option><option value="Botsuana">Botsuana</option><option value="República Centroafricana">República Centroafricana</option><option value="Canada">Canada</option><option value="Islas Cocos (Keeling)">Islas Cocos (Keeling)</option><option value="Suiza">Suiza</option><option value="Chile">Chile</option><option value="China">China</option><option value="Costa de Marfil">Costa de Marfil</option><option value="Camerún">Camerún</option><option value="Congo, República Democrática del">Congo, República Democrática del</option><option value="Congo">Congo</option><option value="Islas Cook">Islas Cook</option><option value="Colombia">Colombia</option><option value="Comoras">Comoras</option><option value="Cabo Verde">Cabo Verde</option><option value="Costa Rica">Costa Rica</option><option value="Cuba">Cuba</option><option value="Curazao">Curazao</option><option value="Isla de Navidad">Isla de Navidad</option><option value="Islas Caimán">Islas Caimán</option><option value="Chipre">Chipre</option><option value="República Checa">República Checa</option><option value="Alemania">Alemania</option><option value="Yibuti">Yibuti</option><option value="Dominica">Dominica</option><option value="Dinamarca">Dinamarca</option><option value="República Dominicana">República Dominicana</option><option value="Argelia">Argelia</option><option value="Ecuador">Ecuador</option><option value="Egipto">Egipto</option><option value="Eritrea">Eritrea</option><option value="Sahara Occidental">Sahara Occidental</option><option value="España">España</option><option value="Estonia">Estonia</option><option value="Etiopía">Etiopía</option><option value="Finlandia">Finlandia</option><option value="Fiji">Fiji</option><option value="Islas Malvinas">Islas Malvinas</option><option value="Francia">Francia</option><option value="Islas Feroe">Islas Feroe</option><option value="Micronesia">Micronesia</option><option value="Gabon">Gabon</option><option value="Reino Unido">Reino Unido</option><option value="Georgia">Georgia</option><option value="Guernsey">Guernsey</option><option value="Ghana">Ghana</option><option value="Gibraltar">Gibraltar</option><option value="Guinea">Guinea</option><option value="Guadalupe">Guadalupe</option><option value="Gambia">Gambia</option><option value="Guinea-Bissau">Guinea-Bissau</option><option value="Guinea Ecuatorial">Guinea Ecuatorial</option><option value="Grecia">Grecia</option><option value="Granada">Granada</option><option value="Groenlandia">Groenlandia</option><option value="Guatemala">Guatemala</option><option value="Guayana Francesa">Guayana Francesa</option><option value="Guam">Guam</option><option value="Guyana">Guyana</option><option value="Hong Kong">Hong Kong</option><option value="Islas Heard y McDonald">Islas Heard y McDonald</option><option value="Honduras">Honduras</option><option value="Croacia">Croacia</option><option value="Haití">Haití</option><option value="Hungría">Hungría</option><option value="Indonesia">Indonesia</option><option value="Isla de Man">Isla de Man</option><option value="India">India</option><option value="Territorio Británico del Océano Índico">Territorio Británico del Océano Índico</option><option value="Irlanda">Irlanda</option><option value="Irán">Irán</option><option value="Irak">Irak</option><option value="Islandia">Islandia</option><option value="Israel">Israel</option><option value="Italia">Italia</option><option value="Jamaica">Jamaica</option><option value="Jersey">Jersey</option><option value="Jordania">Jordania</option><option value="Japón">Japón</option><option value="Kazajistán">Kazajistán</option><option value="Kenia">Kenia</option><option value="Kirgizstán">Kirgizstán</option><option value="Camboya">Camboya</option><option value="Kiribati">Kiribati</option><option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option><option value="Corea del Sur">Corea del Sur</option><option value="Kuwait">Kuwait</option><option value="Laos">Laos</option><option value="Lebanon">Lebanon</option><option value="Liberia">Liberia</option><option value="Libia">Libia</option><option value="Santa Lucía">Santa Lucía</option><option value="Liechtenstein">Liechtenstein</option><option value="Sri Lanka">Sri Lanka</option><option value="Lesoto">Lesoto</option><option value="Lituania">Lituania</option><option value="Luxemburgo">Luxemburgo</option><option value="Letonia">Letonia</option><option value="Macao">Macao</option><option value="San Martín (Francia)">San Martín (Francia)</option><option value="Marruecos">Marruecos</option><option value="Monaco">Monaco</option><option value="Moldavia">Moldavia</option><option value="Madagascar">Madagascar</option><option value="Islas Maldivas">Islas Maldivas</option><option value="Mexico">Mexico</option><option value="Islas Marshall">Islas Marshall</option><option value="Macedônia">Macedônia</option><option value="Mali">Mali</option><option value="Malta">Malta</option><option value="Birmania">Birmania</option><option value="Montenegro">Montenegro</option><option value="Mongolia">Mongolia</option><option value="Islas Marianas del Norte">Islas Marianas del Norte</option><option value="Mozambique">Mozambique</option><option value="Mauritania">Mauritania</option><option value="Montserrat">Montserrat</option><option value="Martinica">Martinica</option><option value="Mauricio">Mauricio</option><option value="Malawi">Malawi</option><option value="Malasia">Malasia</option><option value="Mayotte">Mayotte</option><option value="Namibia">Namibia</option><option value="Nueva Caledonia">Nueva Caledonia</option><option value="Niger">Niger</option><option value="Norfolk Island">Norfolk Island</option><option value="Nigeria">Nigeria</option><option value="Nicaragua">Nicaragua</option><option value="Niue">Niue</option><option value="Países Bajos">Países Bajos</option><option value="Norway">Norway</option><option value="Nepal">Nepal</option><option value="Nauru">Nauru</option><option value="Nueva Zelanda">Nueva Zelanda</option><option value="Omán">Omán</option><option value="Pakistán">Pakistán</option><option value="Panamá">Panamá</option><option value="Pitcairn">Pitcairn</option><option value="Perú">Perú</option><option value="Filipinas">Filipinas</option><option value="Palau">Palau</option><option value="Papúa Nueva Guinea">Papúa Nueva Guinea</option><option value="Polonia">Polonia</option><option value="Puerto Rico">Puerto Rico</option><option value="Corea del Norte">Corea del Norte</option><option value="Portugal">Portugal</option><option value="Paraguay">Paraguay</option><option value="Palestina">Palestina</option><option value="Polinesia Francesa">Polinesia Francesa</option><option value="Qatar">Qatar</option><option value="Reunión">Reunión</option><option value="Rumanía">Rumanía</option><option value="Rusia">Rusia</option><option value="Ruanda">Ruanda</option><option value="Arabia Saudita">Arabia Saudita</option><option value="Sudán">Sudán</option><option value="Senegal">Senegal</option><option value="Singapur">Singapur</option><option value="Islas Georgias del Sur y Sandwich del Sur">Islas Georgias del Sur y Sandwich del Sur</option><option value="Santa Elena">Santa Elena</option><option value="Svalbard y Jan Mayen">Svalbard y Jan Mayen</option><option value="Solomon Islands">Solomon Islands</option><option value="Sierra Leona">Sierra Leona</option><option value="El Salvador">El Salvador</option><option value="San Marino">San Marino</option><option value="Somalia">Somalia</option><option value="San Pedro y Miquelón">San Pedro y Miquelón</option><option value="Serbia">Serbia</option><option value="Sudán del Sur">Sudán del Sur</option><option value="Santo Tomé y Príncipe">Santo Tomé y Príncipe</option><option value="Surinám">Surinám</option><option value="Eslovaquia">Eslovaquia</option><option value="Eslovenia">Eslovenia</option><option value="Suecia">Suecia</option><option value="Swazilandia">Swazilandia</option><option value="Sint Maarten">Sint Maarten</option><option value="Seychelles">Seychelles</option><option value="Siria">Siria</option><option value="Islas Turcas y Caicos">Islas Turcas y Caicos</option><option value="Chad">Chad</option><option value="Togo">Togo</option><option value="Tailandia">Tailandia</option><option value="Tadjikistán">Tadjikistán</option><option value="Tokelau">Tokelau</option><option value="Turkmenistan">Turkmenistan</option><option value="Timor Oriental">Timor Oriental</option><option value="Tonga">Tonga</option><option value="Trinidad y Tobago">Trinidad y Tobago</option><option value="Tunez">Tunez</option><option value="Turquía">Turquía</option><option value="Tuvalu">Tuvalu</option><option value="Taiwán">Taiwán</option><option value="Tanzania">Tanzania</option><option value="Uganda">Uganda</option><option value="Ucrania">Ucrania</option><option value="Islas Ultramarinas Menores de Estados Unidos">Islas Ultramarinas Menores de Estados Unidos</option><option value="Uruguay">Uruguay</option><option value="Estados Unidos de América">Estados Unidos de América</option><option value="Uzbekistán">Uzbekistán</option><option value="Ciudad del Vaticano">Ciudad del Vaticano</option><option value="San Vicente y las Granadinas">San Vicente y las Granadinas</option><option value="Venezuela">Venezuela</option><option value="Islas Vírgenes Británicas">Islas Vírgenes Británicas</option><option value="Islas Vírgenes de los Estados Unidos">Islas Vírgenes de los Estados Unidos</option><option value="Vietnam">Vietnam</option><option value="Vanuatu">Vanuatu</option><option value="Wallis y Futuna">Wallis y Futuna</option><option value="Samoa">Samoa</option><option value="Yemen">Yemen</option><option value="Sudáfrica">Sudáfrica</option><option value="Zambia">Zambia</option><option value="Zimbabwe">Zimbabwe</option></select>';
    }
}

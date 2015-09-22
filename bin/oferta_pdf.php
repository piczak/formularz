<?php
//============================================================+
// Plik   : oferta_pdf.php
//
// Opis : generate offer as pdf format
//
// Autor: Kamil Klamut
//
//============================================================+

require_once('tcpdf/tcpdf.php');

// tworzy nowy PDF dokument PDF
$pdf = new TCPDF();
$pdf->setPrintHeader(false);

    $pdf->setFontSubsetting(true);

    $pdf->SetFont('dejavusans', '', 14, '', true);

    // dodaje str
    $pdf->AddPage();

    // cien tekstu
    //$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));



    /* ====================================================== */

    /* zmienne */

    $data = date('Y-m-d');

    $siedziba = 'Siemianowice Śl.';

    $telemarketer_dane = file('telemarketer.txt');
    $telemarketer = $telemarketer_dane[0];
    $telemarketer_tel = $telemarketer_dane[1];

    /* imie naswisko */
    $imie = ucfirst(strtolower($_POST['imie']));
    $nazwisko = ucfirst(strtolower($_POST['nazwisko']));

    $klient = $imie.' '.$nazwisko;

    /* adres_1 */
    $adres_1 = '';
    $ulica = $_POST['ulica'];
    $nr_domu = $_POST['nr_domu'];
    $nr_lokalu = $_POST['nr_lokalu'];

    if($nr_lokalu == ''){
        $adres_1 = $ulica.' '.$nr_domu;
    } else {
        $adres_1 = $ulica.' '.$nr_domu.'/'.$nr_lokalu;
    }

    $kod = '';
    /* adres_2 */
    if($_POST['kod'] > 0){
        $kod = substr($_POST['kod'],0,2).'-'.substr($_POST['kod'],2,3);
    }

    $miasto = ucfirst(strtolower($_POST['miasto']));;
    $adres_2 = $kod.' '.$miasto;

    $objekt = $_POST['objekt'];


    /* walidacja imion */
    $imie_przypadek = '';
    $zwrot = '';
    $walidacja = substr($imie, -2);

    if(substr($walidacja,-1) == 'a' && $imie != 'Kuba'){

        $zwrot = 'Szanowna Pani';
        switch ($walidacja){
            case 'la':
                $imie_przypadek = substr($imie, 0, -1).'u';
                break;
            default:
                $imie_przypadek = substr($imie, 0, -1).'o';
                break;
        }
    } else if(ord($walidacja) == 197){

        $zwrot = 'Szanowny Panie';

        $walidacja = substr($imie, -3);

        switch ($walidacja){
            case 'eł':
                $imie_przypadek = substr($imie, 0, -3).'le';
                break;
            default:
                $imie_przypadek = substr($imie, 0, -2).'le';
        }
    } else {

        $zwrot = 'Szanowny Pane';

        $walidacja = substr($imie, -1);
        switch ($walidacja){
            case 'a';
                $imie_przypadek = substr($imie, 0, -1).'o';
                break;
            case 'b':
            case 'c';
            case 'f';
            case 'm';
            case 'n';
            case 'p';
            case 's';
            case 'w';
                $imie_przypadek = $imie.'ie';
                break;
            case 'g':
            case 'h';
            case 'j';
            case 'l';
            case 'z';
                $imie_przypadek = $imie.'u';
                break;
            case 'e':
            case 'i':
            case 'o':
            case 'u':
            case 'y':
                $imie_przypadek = $imie;
                break;
            case 'd':
                $imie_przypadek = $imie.'zie';
                break;
            case 'k':
                $imie_przypadek = substr($imie, 0, -2).'ku';
                break;
            case 'r':
                $imie_przypadek = $imie.'ze';
                break;
            case 't':
                $imie_przypadek = substr($imie, 0, -1).'cie';
                break;
        }
    }

    /* styl lini */
    $style = array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));

    /* =========================== NAGLOWEK ======================== */

    /* logo */
    $pdf->Image('resources/pic/eko.jpg',70,5,70);

    /* miejsce i data */
    $pdf->SetFont('dejavusans','',7);
    $pdf->SetY(55);
    $pdf->SetX(120);
    $pdf->Cell(70,5,$siedziba.' '.$data,'C');

    /* imie i nazwisako */
    $pdf->SetFont('dejavusans','B',12);
    $pdf->SetY(60);
    $pdf->SetX(120);    
    $pdf->Cell(70,5,$klient);

    /* ulica, nr domu, nr lokalu */
    $pdf->SetFont('dejavusans','',12);
    $pdf->SetY(65);
    $pdf->SetX(120);    
    $pdf->Cell(70,5,$adres_1);

    /* kod miasto */
    $pdf->SetY(70);
    $pdf->SetX(120);    
    $pdf->Cell(70,5,$adres_2);

    /* ============================== CIALO =================================== */

    $pdf->SetFont('dejavusans','',10);
    $pdf->SetY(85);
    $pdf->SetX(20);    
    $pdf->Cell(70,5,'Osoba prowadząca rozmowę:');

    /* telemarketer */
    $pdf->SetFont('dejavusans','',10);
    $pdf->SetY(90);
    $pdf->SetX(20);    
    $pdf->Cell(70,5,$telemarketer."\n");

    /* temat zgloszenia */
    $pdf->SetFont('dejavusans','B',10);
    $pdf->SetY(102);
    $pdf->SetX(20);    
    $pdf->Cell(70,5,'Dotyczy: Wyposażenia objektu('.$objekt.') w panele grzewcze.');

    $pdf->Line(21, 107, 188, 107, $style);

    $pdf->SetFont('dejavusans','',10);
    $pdf->SetY(115);
    $pdf->SetX(20);    
    $pdf->Cell(70,5,$zwrot.' '.$imie_przypadek.',');    

    /* głowna wiadomosc */
    $pdf->SetFont('dejavusans','',10);
    $pdf->SetY(125);
    $pdf->SetX(20); 
    $pdf->MultiCell(170,5,"\t\t\t\t\t\t\tW nawiązaniu do naszej rozmowy telefonicznej mam przyjemność przedstawić aktualne warunki wyposażenia firmy w panele podczerwieni oraz korzyści finansowe wynikające ze zmiany alternatywnego źródła ogrzewania. Oszczędność, bezpieczeństwo oraz komfort tak w skrócie można opisać ogólne zalety paneli grzewczych.\n",'C');

    /* waznosc oferty */
    $pdf->SetFont('dejavusans','',7);
    $pdf->SetY(147);
    $pdf->SetX(20);    
    $pdf->Cell(70,5, 'Oferta ważna na dzień : '.$data);




    // ============================= TABELA =============================

    
    // ---------------------- NAGLOWEK - nazwy kolumn ---------------------
    
    //pozycja Y
    $poz_Y_naglowka_tabeli = 153;

    //pozycja X
    $poz_X_pomieszczenie = 21;
    $poz_X_kubatura = 65;
    $poz_X_koszt_miesieczny = 90;
    $poz_X_model_panelu = 115;
    $poz_X_cena_modelu = 140;
    $poz_X_cena_paneli = 165;

    //szerokosc komorek
    $szerokosc_pomieszczenie = 44;
    $szerokosc_kubatura = 25;
    $szerokosc_koszt_miesieczny = 25;
    $szerokosc_model_panelu = 25;
    $szerokosc_cena_modelu = 25;
    $szerokosc_cena_paneli = 24;

    //wysokosc komorek naglowka
    $wysokosc_naglowek = 7;
    
    // naglowek
    naglowek($pdf, $poz_Y_naglowka_tabeli);


    // ------------------------- komorki z danymi --------------------------- 

    $poz_Y_rekordu_1 = $poz_Y_naglowka_tabeli+$wysokosc_naglowek;

    if(isset($_POST['liczba_pomieszczen'])){
        $liczba_pomieszczen = $_POST['liczba_pomieszczen'];
    }

    // kolor komorek
    $pdf->SetFillColor(255, 255, 255);

    $laczny_koszt_zuzycia_energii = 0;

    $wysokosc_rekordu = 0;
    $rzad = 1;
    $poz_Y_rekordow = 0;

    //while($poz_Y_rekordow < ){
    // petla tworzaca tabele pomieszczen  w zaleznosci od ich liczby
    $nowa_str = 2;
    for($i = 0; $i < $liczba_pomieszczen;$i++){
        

        $pomieszczenie = $_POST['nazwa_pomieszczenia'.$i];
        $pow = $_POST['powierzchnia'.$i];
        $wys = $_POST['wysokosc'.$i];
        $izolacja = $_POST['izolacja'.$i];
        $stawka = $_POST['stawka'.$i];

        $ilosc_h = '';

        // czas dzialania paneli(h) w zaleznosci od izolacji
        if($izolacja > 40){
            $ilosc_h = 7;
        } else if($izolacja <= 40 && $izolacja > 35){
            $ilosc_h = 6;
        } else {
            $ilosc_h = 5;
        }

        // obliczenia
        $kubatura = $pow*$wys;
        $h = $kubatura*$izolacja;
        $miesiac = ($ilosc_h*$h)*30;
        $koszt_mc = ($miesiac*$stawka)/1000;

        // koszt enegii wszystkich pomieszczen w ciagu miesiaca
        $laczny_koszt_zuzycia_energii = $koszt_mc + $laczny_koszt_zuzycia_energii;

        // dobor odpowiednich paneli
        $array = dobor_paneli(intval($h));

        // ceny paneli
        $ceny = array(
            '700' => '890',
            '800' => '1500',
            '1000' => '1980'
        );

        $poz_Y_rekordow = $poz_Y_rekordu_1+$wysokosc_rekordu;


        //wysokosc pojedynczych komorek w polach model panelu i cena panelu
        $wysokosc_model_cena_panelu = 5;

        
        //rodzaje paneli
        $rodzaje_paneli = 0;
        $cena = '';
        if($poz_Y_rekordow < 180){
        
            foreach($array as $key => $value){
                if(($value != '' || $value != 0) && $key != 'wynik' ){
                    
                    //pozycja Y = ilosc rodzajow paneli razy wysokosc komorki
                    $rodzaje_paneli_x_komorka = $rodzaje_paneli * $wysokosc_model_cena_panelu;
                    
                    //pozycja Y kolejnych malych komorek wewnatrz komorek model i cena panelu 
                    $poz_Y_minikomorek = $poz_Y_rekordow + $rodzaje_paneli_x_komorka;

                    $pdf->SetY($poz_Y_minikomorek);
                    $pdf->SetX($poz_X_model_panelu);
                    $pdf->Cell($szerokosc_model_panelu, $wysokosc_model_cena_panelu, $poz_Y_minikomorek/*'HQ '.$key.' - '.$value.' szt.'*/, 1, 0, 'C', true);

                    foreach( $ceny as $cena_key => $cena_value){
                        if($key == $cena_key){
                            //cena modelu za sztuke
                            $pdf->SetY($poz_Y_minikomorek);
                            $pdf->SetX($poz_X_cena_modelu);
                            $pdf->Cell($szerokosc_cena_modelu, $wysokosc_model_cena_panelu, /*$cena_value*/$poz_Y_minikomorek.' zł.', 1, 0, 'C', true);

                            $cena_rodzaju = $cena_value*$value;
                            $cena = $cena_rodzaju+$cena;
                        }
                    }
                    $rodzaje_paneli++;
                }
            }

            // pozycja Y tabeli danego pomieszczenia
            $wys_segmentu = $rodzaje_paneli*$wysokosc_model_cena_panelu;

            //nazwa pomieszczenia
            $pdf->SetY($poz_Y_rekordow);
            $pdf->SetX($poz_X_pomieszczenie);
            $pdf->Cell($szerokosc_pomieszczenie, $wys_segmentu, '('.$pomieszczenie.') '.$poz_Y_rekordow.' - '.$wys_segmentu, 1, 0, 'C', true);

            //kubatura
            $pdf->SetY($poz_Y_rekordow);
            $pdf->SetX($poz_X_kubatura);
            $pdf->Cell($szerokosc_kubatura, $wys_segmentu, $kubatura.' m3', 1, 0, 'C', true);

            //koszt energi za pomieszczenie/miesiac
            $pdf->SetY($poz_Y_rekordow);
            $pdf->SetX($poz_X_koszt_miesieczny);
            $pdf->Cell($szerokosc_koszt_miesieczny, $wys_segmentu, $koszt_mc.' zł', 1, 0, 'C', true);

            //laczna cena
            $pdf->SetY($poz_Y_rekordow);
            $pdf->SetX($poz_X_cena_paneli);
            $pdf->Cell($szerokosc_cena_paneli, $wys_segmentu, $cena.' zł', 1, 0, 'C', true);

            //calkowita wysokosc rzadu calego pojedynczego wpisu pomieszczenia w tabeli 
            $wysokosc_rekordu = $rzad*$wys_segmentu;
            $rzad++;
            
        } else if ($poz_Y_rekordow >= 180){

            $pdf->AddPage();
            $pdf->setPage($nowa_str);
            
            $rzad = 1;
            $wysokosc_rekordu = 0;
            $rodzaje_paneli = 0;
            $wysokosc_model_cena_panelu = 0;
            
            $poz_Y_naglowka_tabeli = 25;
            $poz_Y_rekordow = 7+$poz_Y_naglowka_tabeli;
            
            $wys_segmentu = $rodzaje_paneli*$wysokosc_model_cena_panelu;
            
            $wysokosc_rekordu = $rzad*$wys_segmentu;

            $poz_Y_rekordu_1 = $poz_Y_naglowka_tabeli+$wysokosc_naglowek;

            $i = $i-1; 
            
            naglowek($pdf, $poz_Y_naglowka_tabeli);
            $pdf->SetFillColor(255, 255, 255);
            $nowa_str++;
        }
    }

    
    // -------------------------- pusty formularz -------------------------
    if($liczba_pomieszczen == 0){

        for($i=0; $i<1;$i++){

            $wys_puste = 5;

            $text = 'brak danych';

            $pdf->SetY($poz_Y_rekordu_1);
            $pdf->SetX($poz_X_pomieszczenie);
            $pdf->Cell($szerokosc_pomieszczenie, $wys_puste, $text, 1, 0, 'C', true);

            $pdf->SetY($poz_Y_rekordu_1);
            $pdf->SetX($poz_X_kubatura);
            $pdf->Cell($szerokosc_kubatura, $wys_puste, $text, 1, 0, 'C', true);

            $pdf->SetY($poz_Y_rekordu_1);
            $pdf->SetX($poz_X_koszt_miesieczny);
            $pdf->Cell($szerokosc_koszt_miesieczny, $wys_puste, $text, 1, 0, 'C', true);

            $pdf->SetY($poz_Y_rekordu_1);
            $pdf->SetX($poz_X_model_panelu);
            $pdf->Cell($szerokosc_model_panelu, $wys_puste, $text, 1, 0, 'C', true);

            $pdf->SetY($poz_Y_rekordu_1);
            $pdf->SetX($poz_X_cena_modelu);
            $pdf->Cell($szerokosc_cena_modelu, $wys_puste, $text, 1, 0, 'C', true);

            $pdf->SetY($poz_Y_rekordu_1);
            $pdf->SetX($poz_X_cena_paneli);
            $pdf->Cell($szerokosc_cena_paneli, $wys_puste, $text, 1, 0, 'C', true);
        }
    }

    /*
    $pdf->SetY(271);
    $pdf->SetX(21);
    $pdf->Cell(44, 5, 'test', 1, 0, 'C', true);
    */

    
    /*
    $pdf->AddPage();
    $pdf->setPage(2);
    $poz_Y_naglowka_tabeli = 20;
    naglowek($pdf, $poz_Y_naglowka_tabeli);

    //$y = '';
    $r_y = 0;
    $nowa_str = 3;
    //$poz_Y_naglowka_tabeli = 25;
    $y = $poz_Y_naglowka_tabeli+$wysokosc_naglowek;
    for($i = 0; $i < 200; $i++){
        
        $wysY = $y+$r_y*5;

        if($wysY < 270){
            $wys_puste = 5;

            $text = 'brak danych';
            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetY($wysY);
            $pdf->SetX($poz_X_pomieszczenie);
            $pdf->Cell($szerokosc_pomieszczenie, $wys_puste, $text, 1, 0, 'C', true);

            $pdf->SetY($wysY);
            $pdf->SetX($poz_X_kubatura);
            $pdf->Cell($szerokosc_kubatura, $wys_puste, $text, 1, 0, 'C', true);

            $pdf->SetY($wysY);
            $pdf->SetX($poz_X_koszt_miesieczny);
            $pdf->Cell($szerokosc_koszt_miesieczny, $wys_puste, $text, 1, 0, 'C', true);

            $pdf->SetY($wysY);
            $pdf->SetX($poz_X_model_panelu);
            $pdf->Cell($szerokosc_model_panelu, $wys_puste, $text, 1, 0, 'C', true);

            $pdf->SetY($wysY);
            $pdf->SetX($poz_X_cena_modelu);
            $pdf->Cell($szerokosc_cena_modelu, $wys_puste, $text, 1, 0, 'C', true);

            $pdf->SetY($wysY);
            $pdf->SetX($poz_X_cena_paneli);
            $pdf->Cell($szerokosc_cena_paneli, $wys_puste, $text, 1, 0, 'C', true);
            $r_y++;
        } else if ($i != 200-1){
            $r_y = 0;
            $pdf->AddPage();
            $pdf->setPage($nowa_str);
            
            //$poz_Y_naglowka_tabeli = 25;
            $y = $poz_Y_naglowka_tabeli+$wysokosc_naglowek;
            naglowek($pdf, $poz_Y_naglowka_tabeli);

            $nowa_str++;
        }
    }
*/
    /*
    $pdf->SetY(20);
    $pdf->SetX(65);
    $pdf->writeHTMLCell(100, '11', '', '', '22', 1, 1, 1, true, 'J', true);
    */

    /*
    $pdf->SetY($poz_Y_rekordu_1);
    $pdf->SetX(165);
    $pdf->MultiCell(24, 10+5*$i, 'aaa', 1, 'J', 1, 0, '', '', true, 0, false, true, 0);

    $wiersz3 = $poz_Y_rekordu_1+5;

    // podsumowanie 

    $pdf->SetFillColor(255, 255, 200);
    $pdf->SetY($wiersz3+$row);
    $pdf->SetX(21);
    $pdf->Cell(69, 10, 'Łączny koszt energii w ciągu miesiąca', 1, 0, 'C', true);

    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetY($wiersz3+$row);
    $pdf->SetX(90);
    $pdf->Cell(25, 10, $laczny_koszt_zuzycia_energii, 1, 0, 'C', true);

    $pdf->SetFillColor(255, 255, 200);
    $pdf->SetY($wiersz3+$row);
    $pdf->SetX(115);
    $pdf->Cell(25, 10, 'Razem (panel)', 1, 0, 'C', true);

    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetY($wiersz3+$row);
    $pdf->SetX(140);
    $pdf->Cell(25, 10, '22', 1, 0, 'C', true);

    // legenda
    $pdf->SetFont('dejavusans','B',7);
    $pdf->SetY(177+$row);
    $pdf->SetX(20);
    $pdf->MultiCell(170,5, "* Prognoza kosztów została opracowana w oparciu o informacje uzyskane od Państwa i wyliczone zostały dla modelu termicznego -20°C. Przy temperaturach wyższych oraz uwzględnieniu Państwa indywidualnych preferencji termicznych koszty te ulegną zmniejszeniu na Państwa korzyść.\n", 'C');

    $pdf->SetFont('dejavusans','',8);
    $pdf->SetY(195+$row);
    $pdf->SetX(20);
    $pdf->MultiCell(170,5, "W razie jakichkolwiek pytań pozostaję do Państwa dyspozycji.\n");

    // linia 
    $pdf->Line(21, 199+$row, 188, 199+$row, $style);

    // ============================== STOPKA ============================== 
    $pdf->SetFont('dejavusans','',6);
    $pdf->SetY(205+$row);
    $pdf->SetX(20);
    $pdf->MultiCell(70,5, "EKOPANEL ul. Elizy Orzeszkowej2\n41-103 Siemianowice Śląskie\ntel. 32 - 307 15 00\ntel.kom: ".$telemarketer_tel."infolinia: 801 00 66 11\nbiuro@ekopanel.com.pl\n");

    $pdf->SetFont('dejavusans','',6);
    $pdf->SetY(205+$row);
    $pdf->SetX(146);
    $pdf->MultiCell(70,5, "Nip: 6342219963\nRegon: 240879437\nCentrum Obsługi Klienta 801 00 66 11\nwww.ekopanel.com.pl\n");
*/




    /* nazwa pliku */
    $pdf->Output($klient.'_oferta_'.$data.'.pdf', 'I');

    // wylicza ilosc paneli na pomieszczenie w oparciu o potrzebna moc/h
    function dobor_paneli($liczba){
        $tysiace = substr($liczba, 0, -3)*1000;
        $setki = substr($liczba, -3, 1)*100;
        $dziesiatki = substr($liczba, -2, 2);

        if($dziesiatki > 50){
            $setki = $setki + 100;
        } 
        $dziesiatki = 0;

        $no_700 = '';
        $no_800 = '';
        $no_1000 = '';

        $rest_no_1000 = '';
        if($liczba < 100){
            $wynik = $liczba;
        } else {
            $wynik = $tysiace+$setki;
        }

        if($liczba > 999){
            if($liczba > 2000 && ($setki != 0 || $setki != 1000)){
                $rest_no_1000 = substr($wynik, 0, -3)-2;
                switch($setki){
                    case '100';
                        $no_700 = 3;
                    break;
                    case '200';
                        $no_700 = 2;
                        $no_800 = 1;
                    break;
                    case '300';
                        $no_700 = 1;
                        $no_800 = 2;
                    break;
                    case '400';
                        $no_700 = 2;
                        $no_1000 = $no_1000+1;
                    break;
                    case '500';
                        $no_700 = 1;
                        $no_800 = 1;
                        $no_1000 = $no_1000+1;
                    break;
                    case '600';
                        $no_800 = 2;
                        $no_1000 = $no_1000+1;
                    break;
                    case '700';
                        $no_700 = 1;
                        $no_1000 = $no_1000+2;
                    break;
                    case '800';
                        $no_800 = 1;
                        $no_1000 = $no_1000+2;
                    break;
                    case '900';
                        $no_700 = 3;
                        $no_800 = 1;
                    break;
                }
                $no_1000 = $no_1000 + $rest_no_1000;
            } else if($liczba < 2000 && $liczba > 1000 && ($setki != 0 || $setki != 1000)) {
                switch($setki){
                    case '100';
                    case '200';
                    case '300';
                    case '400';
                        $no_700 = 2;
                    break;
                    case '500';
                        $no_700 = 1;
                        $no_800 = 1;
                    break;
                    case '600';
                        $no_800 = 2;
                    break;
                    case '700';
                        $no_700 = 1;
                        $no_1000 = 1;
                    break;
                    case '800';
                        $no_800 = 1;
                        $no_1000 = 1;
                    break;
                    case 900;
                        $no_1000 = 2;
                    break;
                }
            }
        } else {

            if($liczba < 999 && $liczba > 850){
                $no_1000 = 1;
            } else if ($liczba <= 850 && $liczba > 750){
                $no_800 = 1;
            } else {
                $no_700 = 1;
            }
        }

        if($wynik > 999 && substr($wynik,-3) === '000' ) {
                $no_1000 = substr($wynik, 0, -3);
        }
        $array = array(
            '700' => $no_700,
            '800' => $no_800,
            '1000' => $no_1000,
            'wynik' => $wynik
        );
        return($array);
    }
    
    
    //funkcja generujaca naglowek tabeli
    function naglowek($pdf, $poz_Y_naglowka_tabeli){

    //pozycja X
    $poz_X_pomieszczenie = 21;
    $poz_X_kubatura = 65;
    $poz_X_koszt_miesieczny = 90;
    $poz_X_model_panelu = 115;
    $poz_X_cena_modelu = 140;
    $poz_X_cena_paneli = 165;

    //szerokosc komorek
    $szerokosc_pomieszczenie = 44;
    $szerokosc_kubatura = 25;
    $szerokosc_koszt_miesieczny = 25;
    $szerokosc_model_panelu = 25;
    $szerokosc_cena_modelu = 25;
    $szerokosc_cena_paneli = 24;

    //wysokosc komorek naglowka
    $wysokosc_naglowek = 7;

    
    //dane kolumn naglowka
    $naglowek = array(
        'pomieszczenie' => array(
            'nazwa' => 'Nazwa Pomieszczenia',
            'X' => $poz_X_pomieszczenie,
            'Y' => $poz_Y_naglowka_tabeli,
            'szerokosc' => $szerokosc_pomieszczenie,
            'wysokosc' => $wysokosc_naglowek
        ),
        'kubatura' => array (
            'nazwa' => 'Kubatura',
            'X' => $poz_X_kubatura,
            'Y' => $poz_Y_naglowka_tabeli,
            'szerokosc' => $szerokosc_kubatura,
            'wysokosc' => $wysokosc_naglowek
        ),
        'koszt_miesieczny' => array(
            'nazwa' => 'Miesięczny koszt energii',
            'X' => $poz_X_koszt_miesieczny,
            'Y' => $poz_Y_naglowka_tabeli,
            'szerokosc' => $szerokosc_koszt_miesieczny,
            'wysokosc' => $wysokosc_naglowek
        ),
        'model_panelu' => array(
            'nazwa' => 'Model Panelu',
            'X' => $poz_X_model_panelu,
            'Y' => $poz_Y_naglowka_tabeli,
            'szerokosc' => $szerokosc_model_panelu,
            'wysokosc' => $wysokosc_naglowek
        ),
        'cena_modelu' => array(
            'nazwa' => 'Cena modelu za sztukę',
            'X' => $poz_X_cena_modelu,
            'Y' => $poz_Y_naglowka_tabeli,
            'szerokosc' => $szerokosc_cena_modelu,
            'wysokosc' => $wysokosc_naglowek
        ),
        'cena_paneli' => array(
            'nazwa' => 'Cena paneli/ pomieszczenie',
            'X' => $poz_X_cena_paneli,
            'Y' => $poz_Y_naglowka_tabeli,
            'szerokosc' => $szerokosc_cena_paneli,
            'wysokosc' => $wysokosc_naglowek
        )
    );
    
        $pdf->SetFillColor(255, 255, 200);
        
        foreach($naglowek as $key => $value){
            
            if($key == 'koszt_miesieczny' || $key == 'cena_modelu' || $key == 'cena_paneli'){

                $pdf->SetY($value['Y']);
                $pdf->SetX($value['X']);
                $pdf->MultiCell($value['szerokosc'], $value['wysokosc'], $value['nazwa']."\n", 1, 'C', 1, 0, '', '', true, 0, false, true, 0);

            } else {

                $pdf->SetY($value['Y']);
                $pdf->SetX($value['X']);
                //$pdf->Cell(0,0,'This is a sample data',1,1,'L',0,'');
                $pdf->Cell($value['szerokosc'], $value['wysokosc'], $value['nazwa']."\n", 1, 0, 'C', true);

            }
        }
    }
?>
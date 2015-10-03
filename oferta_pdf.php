<?php
//============================================================+
// Plik : oferta_pdf.php
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

    // ======================================================

    // zmienne

    $data = date('Y-m-d');

    $miejsce = 'Siemianowice Śl.';
    
    $siedziba = 'EKOPANEL ul. Elizy Orzeszkowej 2 41-103 '.$miejsce;

    
    $telemarketer = ucfirst(strtolower($_POST['telemarketer_imie'])).' '.ucfirst(strtolower($_POST['telemarketer_nazwisko'])).' ('.$_POST['telemarketer_telefon'].')';
    $telemarketer_tel = $_POST['telemarketer_telefon'];
    
    
    
    $ceny_paneli = file('dane/ceny.txt');
    $cena_hq700 = intval($ceny_paneli[0]);
    $cena_hq800 = intval($ceny_paneli[1]);
    $cena_hq1000 = intval($ceny_paneli[2]);
    
    $formularz_dane = file('dane/formularz_dane.txt');
    
    $tekst_glowny = $formularz_dane[0];
    $temperatura_zewnetrzna = (double)(str_replace(",",".", $formularz_dane[1]));
    $tekst_podsumowanie = $formularz_dane[2];

    // imie naswisko
    $imie = ucfirst(strtolower($_POST['imie']));
    $nazwisko = ucfirst(strtolower($_POST['nazwisko']));
    
    $klient = $imie.' '.$nazwisko;
    
    //zmienna bez polskich znakow uzyta w nazwie pliku podczas zapisu pdf
    $klientNpl = bezPl($klient);
    
    $firma = '';
    //firma
    if(!empty($_POST['firma'])){
        $firma = $_POST['firma'];
    } else {
        $firma = '';
    }
    
    // adres_1
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
    // adres_2
    if($_POST['kod'] > 0){
        $kod = substr($_POST['kod'],0,2).'-'.substr($_POST['kod'],2,3);
    }

    $miasto = ucfirst(strtolower($_POST['miasto']));;
    $adres_2 = $kod.' '.$miasto;

    //email
    $email = $_POST['email'];
    
    //objekt
    $obiekt = $_POST['obiekt'];

    // styl lini
    $style = array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));

    // =========================== NAGLOWEK ========================

    // logo
    $pdf->Image('resources/pic/eko.jpg',70,5,70);

    //$cena_suma ='Łączna cena ';
    $cenyBN = array();
    if($firma == ''){
        
        //$cena_suma = $cena_suma.'netto';
    
        $cenyBN = array(
            '700' => $cena_hq700,
            '800' => $cena_hq800,
            '1000' => $cena_hq1000,
            'waga' => 'brutto'
        );
        
        //nazwa firmy
        $pdf->SetFont('dejavusans','',5);
        $pdf->SetY(50);
        $pdf->SetX(120);
        $pdf->Cell(70,5,$siedziba,'C');
        
        // imie i nazwisako
        $pdf->SetFont('dejavusans','B',10);
        $pdf->SetY(55);
        $pdf->SetX(120);    
        $pdf->Cell(70,5,$klient);

        // ulica, nr domu, nr lokalu
        $pdf->SetFont('dejavusans','',10);
        $pdf->SetY(60);
        $pdf->SetX(120);    
        $pdf->Cell(70,5,$adres_1);

        // kod miasto
        $pdf->SetY(65);
        $pdf->SetX(120);    
        $pdf->Cell(70,5,$adres_2);
        
        // email
        $pdf->SetFont('dejavusans','',7);
        $pdf->SetY(70);
        $pdf->SetX(120);    
        $pdf->Cell(70,5,$email);
        
        // miejsce i data
        $pdf->SetFont('dejavusans','',5);
        $pdf->SetY(80);
        $pdf->SetX(160);
        $pdf->Cell(70,5,$miejsce.' '.$data,'C');
        
    } else {
        
        //$cena_suma = $cena_suma.'brutto';
        
        $cenyBN = array(
            '700' => round($cena_hq700/1.23, 2),
            '800' => round($cena_hq800/1.23, 2),
            '1000' => round($cena_hq1000/1.23, 2),
            'waga' => 'netto'
        );
        
        
        //nazwa firmy
        $pdf->SetFont('dejavusans','',5);
        $pdf->SetY(40);
        $pdf->SetX(120);
        $pdf->Cell(70,5,$siedziba,'C');
        
        // firma
        $pdf->SetFont('dejavusans','',10);
        $pdf->SetY(45);
        $pdf->SetX(120);
        $lines = $pdf->MultiCell(55, 5, $firma."\n", 0, 'L', FALSE);
        
        $eol_firma = ($lines-1)*5;
        
        // ulica, nr domu, nr lokalu
        $pdf->SetFont('dejavusans','',10);
        $pdf->SetY(50+$eol_firma);
        $pdf->SetX(120);    
        $pdf->Cell(70,5,$adres_1);

        // kod miasto
        $pdf->SetY(55+$eol_firma);
        $pdf->SetX(120);    
        $pdf->Cell(70,5,$adres_2);
        
        $pdf->SetFont('dejavusans','B',10);
        $pdf->SetY(60+$eol_firma);
        $pdf->SetX(120);    
        $pdf->Cell(70,5,$klient);
        
        // email
        $pdf->SetFont('dejavusans','',7);
        $pdf->SetY(65+$eol_firma);
        $pdf->SetX(120);    
        $pdf->Cell(70,5,$email);
        
        // miejsce i data
        $pdf->SetFont('dejavusans','',5);
        $pdf->SetY(75+$eol_firma);
        $pdf->SetX(160);
        $pdf->Cell(70,5,$miejsce.' '.$data,'C');
    }
    

    // ============================== CIALO ===================================

    $pdf->SetFont('dejavusans','',10);
    $pdf->SetY(85);
    $pdf->SetX(20);    
    $pdf->Cell(70,5,'Osoba prowadząca rozmowę');

    // telemarketer
    $pdf->SetFont('dejavusans','',8);
    $pdf->SetY(90);
    $pdf->SetX(20);    
    $pdf->MultiCell(70,5,$telemarketer."\n",'C');
    
    $pdf->SetY(94);
    $pdf->SetX(20);    
    $pdf->MultiCell(70,5,"biuro@ekopanel.com.pl\n",'C');

    // temat zgloszenia
    $pdf->SetFont('dejavusans','B',10);
    $pdf->SetY(102);
    $pdf->SetX(20);    
    $pdf->Cell(70,5,'Dotyczy: Wyposażenia obiektu('.$obiekt.') w panele grzewcze.');

    $pdf->Line(21, 107, 188, 107, $style);
    
    
    $imie_zwrot = imie_zwrot($imie);
    
    $pdf->SetFont('dejavusans','',10);
    $pdf->SetY(115);
    $pdf->SetX(20);    
    $pdf->Cell(70,5,$imie_zwrot['zwrot'].' '.$imie_zwrot['imie'].',');    

    // głowna wiadomosc
    $pdf->SetFont('dejavusans','',8);
    $pdf->SetY(125);
    $pdf->SetX(20); 
    $pdf->MultiCell(170,5,$tekst_glowny."\n",'C');

    // waznosc oferty
    $pdf->SetFont('dejavusans','',7);
    $pdf->SetY(147);
    $pdf->SetX(20);    
    $pdf->Cell(70,5, 'Oferta ważna na dzień : '.$data);




    // ============================= TABELA =============================

    
    // ---------------------------- NAGLOWEK  ---------------------------
    
    $naglowek = naglowek($pdf, 153);
    
    $segment_Y_start = $naglowek['segment_poczatek'];
    
    if(isset($_POST['liczba_pomieszczen'])){
        $liczba_pomieszczen = $_POST['liczba_pomieszczen'];
    }
    
    
    
    // --------------------- SEGMENT PODSUMOWANIE -----------------------
    
    $str = 1;
    $licznik = 0;
    $cena_pradu = 0;
    $cena_paneli = 0;
    $dane = array();
    
    for($i = 0; $i < $liczba_pomieszczen; $i++){

        $stawka = (double)(str_replace(",",".", $_POST['stawka'.$i]));
        $wysokosc = (double)(str_replace(",",".", $_POST['wysokosc'.$i]));
        
        $dane = array(
            'pomieszczenie' => $_POST['nazwa_pomieszczenia'.$i],
            'powierzchnia' => $_POST['powierzchnia'.$i],
            'wysokosc' => $wysokosc,
            'izolacja' => $_POST['izolacja'.$i],
            'stawka' => $stawka
        );
        
        
        // tworzy nowa str
        if( $segment_Y_start < 219 ){
            
            $segment = segment($pdf, $segment_Y_start, $licznik, $dane, $liczba_pomieszczen, $cena_pradu, $cena_paneli, $cenyBN, $temperatura_zewnetrzna, $tekst_podsumowanie);
            $segment_Y_start = $segment['kolejny_segment_Y'];
            $licznik = $segment['licznik'];
            $cena_pradu = $segment['łączna_cena_pradu'];
            $cena_paneli = $segment['łączna_cena_paneli'];
            stopka($pdf, $telemarketer_tel);
            
        } else {

            $str++;
            $pdf->AddPage();
            $pdf->setPage($str);
            
            $naglowek = naglowek($pdf, 25);
            $segment_Y_start = $naglowek['segment_poczatek'];
            
            $segment = segment($pdf, $segment_Y_start, $licznik, $dane, $liczba_pomieszczen, $cena_pradu, $cena_paneli, $cenyBN, $temperatura_zewnetrzna, $tekst_podsumowanie);
            $segment_Y_start = $segment['kolejny_segment_Y'];
            $licznik = $segment['licznik'];
            $cena_pradu = $segment['łączna_cena_pradu'];
            $cena_paneli = $segment['łączna_cena_paneli'];
            stopka($pdf, $telemarketer_tel);
  
        }
    }


    
    // -------------------------- PUSTY FORMULARZ -------------------------
    
    if($liczba_pomieszczen == 0){
        pusty_formularz($pdf, $segment_Y_start);
    }
    
    
    // ----------------------------- STOPKA -------------------------------
    stopka($pdf, $telemarketer_tel);


    // ---------------------------- NAZWA PLIKU ---------------------------
    
    $pdf->Output($klientNpl.'_oferta_'.$data.'.pdf', 'I');

    
    
    
    
    
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    //+++++++++++++++++++++++++++++ FUNKCJE +++++++++++++++++++++++++++++++
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    
    
    
    // --------------------- FUNKCJA IMIE_ZWROT -----------------------
    // wylicza ilosc paneli na pomieszczenie w oparciu o potrzebna moc/h
    
    function imie_zwrot($imie){
    
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

            $zwrot = 'Szanowny Panie';

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
        $imie_zwrot = array(
            'imie' => $imie_przypadek,
            'zwrot' => $zwrot
        );
        return $imie_zwrot;
    }
    
    
    
    // -------------------------- DOBOR PANELI ----------------------------
    // wylicza ilosc paneli na pomieszczenie w oparciu o potrzebna moc/h
    
    function dobor_paneli($liczba, $cenyBN){
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
        
        //var_dump($liczba);
        
        if($liczba > 999){
            if($wynik > 2000 && substr($wynik, -3, 3) != 0){
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
                    case '900';
                        $no_1000 = 2;
                    break;
                }
            } else if(substr($wynik, -3, 3) == 0){
                $no_1000 = $tysiace/1000;
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
        
        $panele = array(
            '700' => array(
                'ilosc' =>  $no_700,
                'cena' => $cenyBN['700'].' zł'
            ),
            '800' => array(
                'ilosc' =>  $no_800,
                'cena' => $cenyBN['800'].' zł'
            ),
            '1000' => array(
                'ilosc' =>  $no_1000,
                'cena' => $cenyBN['1000'].' zł'
            )
        );
        return($panele);
    }
    
    
    
    // --------------------------- FUNKCJA TABELKI(X) -------------------------
    // szerokosc kolumn i punkty x
    
    function tabelka(){
        $tabelka = array(
            'pomieszczenie' => array(
                'pozycja_x' => 21,
                'szerokosc' => 44
            ),
            'powierzchnia' => array(
                'pozycja_x' => 65,
                'szerokosc' => 25
            ),
            'koszt_dzienny' => array(
                'pozycja_x' => 90,
                'szerokosc' => 25
            ),
            'model_panelu' => array(
                'pozycja_x' => 115,
                'szerokosc' => 25
            ),
            'cena_modelu' => array(
                'pozycja_x' => 140,
                'szerokosc' => 25
            ),
            'cena_paneli' => array(
                'pozycja_x' => 165,
                'szerokosc' => 24
            )
        );
        return $tabelka;
    }
    
    
    
    // -------------------------- FUNKCJA NAGLOWKA -------------------------
    // tworzy naglowek na wybranej wysokosci(Y)
    
    function naglowek($pdf, $poz_Y_naglowka){
        $nazwy_pol_naglowka = array(
            'pomieszczenie' => 'Nazwa Pomieszczenia',
            'powierzchnia' => 'Powierzchnia',
            'koszt_dzienny' => 'Dzienny koszt energii',
            'model_panelu' => 'Model Panelu',
            'cena_modelu' => 'Cena modelu za sztukę',
            'cena_paneli' => 'Cena paneli/ pomieszczenie'
        );
        
        $wysokosc_naglowka = 7;
        
        $dolny_Y_segmentu = array('segment_poczatek' => $poz_Y_naglowka + $wysokosc_naglowka+1);
        
        foreach($nazwy_pol_naglowka as $key => $value ){
                
                $data = array(
                    'wysokosc' => $wysokosc_naglowka,
                    'pozycja_y' => $poz_Y_naglowka,
                    'nazwa' => $value,
                );
                $naglowek_data[$key] = $data;
        }
        
        $i = 0;
        $j = 0;
        $wynik = '';
        $tabelka = tabelka();
        foreach($tabelka as $nazwa1 => $dane1){
            foreach($naglowek_data as $dane2){
                if($j > 0){
                    $wynik = $i/$j;
                }
                if($wynik == sizeof($tabelka)+1 || ( $i == 0 && $j == 0 )){
                    $dane = array_merge($dane1, $dane2);
                    $naglowek[$nazwa1] = $dane;
                }
                $i++;
            }
            $j++;
        }
    
        $pdf->SetFillColor(255, 255, 200);
        
        foreach($naglowek as $key => $value){
            
            if($key == 'koszt_dzienny' || $key == 'cena_modelu' || $key == 'cena_paneli'){

                $pdf->SetY($value['pozycja_y']);
                $pdf->SetX($value['pozycja_x']);
                $pdf->MultiCell($value['szerokosc'], $value['wysokosc'], $value['nazwa']."\n", 1, 'C', 1, 0, '', '', true, 0, false, true, 0);

            } else {

                $pdf->SetY($value['pozycja_y']);
                $pdf->SetX($value['pozycja_x']);
                $pdf->Cell($value['szerokosc'], $value['wysokosc'], $value['nazwa'], 1, 0, 'C', true);

            }
        }
        return $dolny_Y_segmentu;
    }
    
    
    
    // ---------------------- FUNKCJA PUSTEGO FORMULARZA --------------------
    // wykonuje sie gdy nie zostanie wypelniony formularz
    
    function pusty_formularz($pdf, $segment_Y_start){
    
        $tabelka = tabelka();
        $wys_puste = 5;
        $text = 'brak danych';
        
        $pdf->SetFillColor(255, 255, 255);
        
        foreach($tabelka as $key => $value){
        
            $pdf->SetY($segment_Y_start);
            $pdf->SetX($value['pozycja_x']);
            $pdf->Cell($value['szerokosc'], $wys_puste, $text, 1, 0, 'C', true);
            
        }
    }
    
    
    
    // ---------------------- FUNKCJA SEGMENTU ------------------------
    // przetwarza i wyswietla w postaci tabelki, dane wprowadzone przez uzytkownika
    
    function segment($pdf, $segment_Y_start, $licznik, $dane, $liczba_pomieszczen, $cena_pradu, $cena_paneli, $cenyBN, $temperatura_zewnetrzna, $tekst_podsumowanie){
        
        $tabelka = tabelka();
        
        
        // dane
        
        $pomieszczenie = $dane['pomieszczenie'];
        $powierzchnia = $dane['powierzchnia'];
        $wysokosc = $dane['wysokosc'];
        $izolacja = $dane['izolacja'];
        $stawka = $dane['stawka'];
        
        $ilosc_h = '';
        
        $walidacja_kropki = array(
            '1' => $powierzchnia,
            '2' => $wysokosc,
            '3' => $stawka
        );
        
        foreach($walidacja_kropki as $value){
            intval($value);
        }
        $powierzchnia = $walidacja_kropki['1'];
        $wysokosc = $walidacja_kropki['2'];
        $stawka = $walidacja_kropki['3'];
        
        
        
        
        
        // oblliczenia
        
        $kwh = $powierzchnia * 100;
        
        
        //$kubatura = $powierzchnia*$wysokosc;

        
        $koszt_mc = kalkulator($stawka, $powierzchnia, $izolacja, $temperatura_zewnetrzna);
        
        
        $pdf->SetFillColor(255, 255, 255);

        
        $cena_pradu = $cena_pradu + $koszt_mc;

        
        //tabelka segmentu
        
        $tabelka_segmentu = $tabelka;
        
        $panele_model = dobor_paneli($kwh, $cenyBN);
        
        $cena_suma = 0;
        $modele = array();
        $ceny = array();
        foreach($panele_model as $key => $val){
            if(is_int($panele_model[$key]['ilosc']) && $panele_model[$key]['ilosc'] != 0){
                $modele[$key] = $val['ilosc'];
                $ceny[$key] = $val['cena'];
                $cena_suma = $cena_suma + ($val['ilosc'] * $val['cena']);
            }
        }

        
        //całkowita cena paneli
        
        $cena_paneli = $cena_paneli + $cena_suma;

        
        //calkowita wysokosc segmentu
        
        $wysokosc_segmentu = sizeof($modele) * 5;
        
        $wartosci = array(
            'pomieszczenie' => $pomieszczenie,
            'powierzchnia' => round($powierzchnia, 2).' m2',
            'koszt_dzienny' => round($koszt_mc, 2).' zł',
            'model_panelu' => $modele,
            'cena_modelu' => $ceny,
            'cena_paneli' => round($cena_suma, 2).' zł'
        );
        
        $tabelka_segmentu1 = array();
        
        foreach($tabelka_segmentu as $key => $value){
            $value['wysokosc'] = $wysokosc_segmentu;
            $value['pozycja_y'] = $segment_Y_start;
            $value['dane'] = $wartosci[$key];
            $tabelka_segmentu1[$key] = $value;
        }
        
        foreach($tabelka_segmentu1 as $nazwa => $wartosci ){
        
            $pdf->SetY($wartosci['pozycja_y']);
            $pdf->SetX($wartosci['pozycja_x']);
            if(is_array($wartosci['dane'])){
            
                $i = 0;
                foreach($wartosci['dane'] as $key => $val){
                    
                    $pdf->SetY($wartosci['pozycja_y'] + ($i * 5));
                    $pdf->SetX($wartosci['pozycja_x']);
                    if($nazwa == 'model_panelu'){
                        $pdf->Cell($wartosci['szerokosc'], 5, 'HQ-'.$key.', '.$val.' szt.', 1, 0, 'C', true);
                    } else {
                        $pdf->Cell($wartosci['szerokosc'], 5, $val, 1, 0, 'C', true);
                    }
                    $i++;
                }
            } else {
                $pdf->Cell($wartosci['szerokosc'], $wartosci['wysokosc'], $wartosci['dane'], 1, 0, 'C', true);
            }
        
        }
        
        //zapis_do_pliku($tabelka_segmentu1);
        
        //dane aktualizowane i przesylane ponownie
        
        $segment_Y_dol = $segment_Y_start+$wysokosc_segmentu+1;
        $licznik++;
        
        
        
        //petla zawierajaca dane przesylane ponownie
        
        $segment_dane = array(
            'kolejny_segment_Y' => $segment_Y_dol,
            'licznik' => $licznik,
            'łączna_cena_pradu' => round($cena_pradu, 2),
            'łączna_cena_paneli' => round($cena_paneli, 2)
        );
        
        // gdy ostatni segment... odpal stopke
        if($segment_dane['licznik'] == intval($liczba_pomieszczen)){
            podsumowanie($pdf, $segment_dane, $cenyBN['waga'], $dane, $temperatura_zewnetrzna, $tekst_podsumowanie);
        } else {
            return $segment_dane;
        }
    }
    
    
    
    // ---------------------- FUNKCJA PODSUMOWANIE ------------------------
    // ostatni wiersz kolumny(laczna cena za prad i laczna cena za panele) + info
    
    function podsumowanie($pdf, $podsumowanie, $waga, $dane, $temperatura_zewnetrzna, $tekst_podsumowanie){
        
        //function miesiace($dane);
        
        $pdf->SetFillColor(255, 255, 200);
        $pdf->SetY($podsumowanie['kolejny_segment_Y']);
        $pdf->SetX(21);
        $pdf->Cell(69, 7, 'Łączny dzienny koszt energii', 1, 0, 'C', true);
        
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetY($podsumowanie['kolejny_segment_Y']);
        $pdf->SetX(90);
        $pdf->Cell(25, 7, $podsumowanie['łączna_cena_pradu'].' zł', 1, 0, 'C', true);
        
        $pdf->SetFillColor(255, 255, 200);
        $pdf->SetY($podsumowanie['kolejny_segment_Y']);
        $pdf->SetX(115);
        $pdf->Cell(50, 7, 'Łączna cena '.$waga, 1, 0, 'C', true);
        
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetY($podsumowanie['kolejny_segment_Y']);
        $pdf->SetX(165);
        $pdf->Cell(24, 7, $podsumowanie['łączna_cena_paneli'].' zł', 1, 0, 'C', true);
        
        $pdf->SetFont('dejavusans','',7);
        $pdf->SetY($podsumowanie['kolejny_segment_Y']+8);
        $pdf->SetX(20);
        $linia = $pdf->MultiCell(170,5, $tekst_podsumowanie."\n", 'C');
        
        
        $pdf->SetFont('dejavusans','',8);
        $pdf->SetY($podsumowanie['kolejny_segment_Y']+20+$linia);
        $pdf->SetX(20);
        $pdf->MultiCell(170,5, "W razie jakichkolwiek pytań pozostaję do Państwa dyspozycji.\n");
        
        
    }
    
    
    
    // ---------------------- FUNKCJA STOPKI ------------------------
    // wykonuje sie po wykonaniu sie wszystkich segmentow. jest wywolywana w funkcji segmentu
    
    function stopka($pdf, $telemarketer_tel){
        
        // linia
        $style = array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
        $pdf->Line(21, 260, 188, 260, $style);
        
        $pdf->SetFont('dejavusans','',6);
        $pdf->SetY(261);
        $pdf->SetX(20);
        $pdf->MultiCell(70,5, "EKOPANEL ul. Elizy Orzeszkowej2\n41-103 Siemianowice Śląskie\ntel. 32 - 307 15 00\ntel.kom: ".$telemarketer_tel." infolinia: 801 00 66 11\nbiuro@ekopanel.com.pl\n");

        $pdf->SetFont('dejavusans','',6);
        $pdf->SetY(261);
        $pdf->SetX(146);
        $pdf->MultiCell(70,5, "Nip: 6342219963\nRegon: 240879437\nCentrum Obsługi Klienta 801 00 66 11\nwww.ekopanel.com.pl\n");
    }
    
    
    
    // ---------------------- FUNKCJA ZAPIS DO PLIKU --------------------
    // zapisuje dane do pliku 
    
    function zapis_do_pliku($tabelka_segmentu1){
        

        
        //var_dump($tabelka_segmentu1);
        
        // przypisanie zmniennej $file nazwy pliku
        //$file = "klienci.txt";

        //file_put_contents($file, "\n\ttest", FILE_APPEND | LOCK_EX);
        
        // The new person to add to the file
        //$person = "John Smith\n";
        
        /*
        foreach($tabelka_segmentu1 as $key => $value){

            if(is_array($value)){
                foreach($value as $key_v => $value_v){
                    file_put_contents($file, "\n\t".$key_v.' - '.$value_v, FILE_APPEND | LOCK_EX);
                }
            } else {
                file_put_contents($file, "\n".$key.' - '.$value, FILE_APPEND | LOCK_EX);
            }
        }*/
        
    }
    
    
    
    // ---------------------- FUNKCJA KALKULATORA --------------------
    // oblicza dzienny koszt energii w oparciu o dane dostarczone przez klienta
    
    function kalkulator($stawka, $powierzchnia, $izolacja, $temp_zewnetrzna){
    
        $temp_ustawiona = 21;
        
        //delta temperatur
        $DT = $temp_ustawiona - ($temp_zewnetrzna);
        
        $U = $izolacja / 100;
        
        $kwh = $powierzchnia * 100;
        $q = $U * $DT;
        
        $waty_na_stopien_na_m2 = $q+($q*$U);
        
        $wynik = $waty_na_stopien_na_m2 * $DT;
        $wynik = $wynik * $powierzchnia;
        
        $koszt_dzienny = (($wynik)/1000)*$stawka;
        
        return $koszt_dzienny;
    }
    
    
    
    // ---------------------- FUNKCJA BEZ POLSKICH ZNAKOW --------------------
    // usuwa polskie znaki ze zmiennej wykorzystanej w nazwie plik podczas zapisu pdf
    
    function bezPl($klient){
        $pl = array('ą','ć','ę','ł','ń','ó','ś','ź','ż','Ą','Ć','Ę','Ł','Ń','Ó','Ś','Ź','Ż');
        $npl = array('a','c','e','l','n','o','s','z','z','A','C','E','L','N','O','S','Z','Z');
        
        $pl_litery = null;
        foreach($pl as $value){
            $ilosc = substr_count($klient, $value);
            $pl_litery = $pl_litery + $ilosc;
        }
        
        if($pl_litery != null){
            for($i = 0; $i<$pl_litery; $i++){
                $count = 0;
                foreach($pl as $value){
                    $klient = str_replace($value,$npl[$count],$klient);
                    $count++;
                }
            }
        }
        return $klient;
    }
?>

<?php
require_once('index.php');
    if(file_exists('../telemarketer.txt')){
        $telemarketer_dane = file('../telemarketer.txt');
        $telemarketer = $telemarketer_dane[0];
        $telemarketer_tel = $telemarketer_dane[1];
    } else {
        $telemarketer = 'brak pliku telemarketer.txt';
        $telemarketer_tel = 'brak pliku telemarketer.txt ';
    }
?>

        <div class="container">
        
            <div class="main">
                
                <p style="margin-top:20px;">Telemarketer: <? echo $telemarketer ?>( <? echo $telemarketer_tel ?>)</p>
                <h3>DANE KLIENTA</h3>
                <hr>
               
                <form action="oferta_pdf.php" method="POST">

                    <fieldset>
                    <h4>Imię i Nazwisko</h4>
                    
                    <div class="imie_nazwisko">
                    
                        <!-- IMIE -->
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon" id="addon1">Imię</span>
                                <input type="text" class="form-control" id="input_name" data-add="addon1" name="imie" aria-describedby="addon1">
                            </div>
                        </div>
                        
                        <!-- NAZWISKO -->
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon" id="addon2">Nazwisko</span>
                                <input type="text" class="form-control" id="input_nazwisko" data-add="addon2" name="nazwisko" aria-describedby="basic-addon2">
                            </div>
                        </div>
                    </div>

                    
                    <h4>Nazwa Firmy</h4>

                    <div class="firma">
                    
                        <!-- FIRMA -->
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon" id="addon3">Nazwa Firmy</span>
                                <textarea class="form-control niewymagane" id="input_firma" data-add="addon3" name="firma" aria-describedby="addon3"></textarea> 
                            </div>
                            <div class="info"><p>*pole niewymagane</p></div>
                        </div>
                    </div>
                    <hr>
                    
                    <h4>Adres i E-mail</h4>
                    
                    <div class="adres">
                        
                        <!-- ADRES -->
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon" id="addon4">Ulica</span>
                                <input type="text" class="form-control" id="input_ulica" data-add="addon4" name="ulica" aria-describedby="addon4"> 
                            </div>
                        </div>
                        
                        <!-- NR DOMU -->
                        <div class="form-group">
                            <div class="input-group numbers">
                                <span class="input-group-addon" id="addon5">Nr. Domu</span>
                                <input type="text" class="form-control" id="input_nr_domu" data-add="addon5" name="nr_domu" aria-describedby="addon5"> 
                            </div>
                        </div>
                        
                        <!-- NR LOKALU -->
                        <div class="form-group">
                            <div class="input-group numbers">
                                <span class="input-group-addon" id="addon6">Nr. Lokalu</span>
                                <input type="text" class="form-control niewymagane" id="input_nr_lokalu" data-add="addon6" name="nr_lokalu" aria-describedby="addon6"> 
                            </div>
                            <div class="info"><p>*pole niewymagane</p></div>
                        </div>
                        
                        <!-- KOD -->
                        <div class="form-group">
                            <div class="input-group numbers">
                                <span class="input-group-addon" id="addon7">Kod</span>
                                <input type="text" class="form-control" id="input_kod" data-add="addon7" name="kod" aria-describedby="addon7" maxlength="5">
                            </div>
                            <div class="info"><p>*tylko cyfry, przyklad: 40012</p></div>
                        </div>
                        
                        <!-- MIASTO -->
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon" id="addon8">Miasto</span>
                                <input type="text" class="form-control" id="input_miasto" data-add="addon8" name="miasto" aria-describedby="addon8"> 
                            </div>
                        </div>
                        
                        <!-- EMAIL -->
                        <div class="form-group">
                            <div class="input-group email">
                                <span class="input-group-addon" id="addon9">E-mail:@</span>
                                <input type="text" class="form-control" id="input_email" data-add="addon9" name="email" aria-describedby="addon9"> 
                            </div>
                        </div>
                    </div>
                    <hr>
                    
                    <h4>Oferta</h4>
                    
                    <div class="oferta">
                    
                        <!-- OBIEKT -->
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon" id="addon10">Obiekt</span>
                                <input type="text" class="form-control" id="input_obiekt" data-add="addon10" name="obiekt" aria-describedby="addon10"> 
                            </div>
                        </div>
                        
                        <!-- ILOSC POMIESZCZEN -->
                        <div class="form-group">
                            <div class="input-group numbers">
                                <span class="input-group-addon" id="addon11">Ilość Pomieszczeń</span>
                                <input type="text" class="form-control" id="input_liczba_pomieszczen" name="liczba_pomieszczen" aria-describedby="addon11" maxlength="2"> 
                            </div>
                            <div class="info"><p>*1 dla całej powierzchni</p></div>
                        </div>
                    </div>

                    
                    <div class="pomieszczenia">
                    <!-- GENEROWANE OKNA -->

                    
                    
                    </div>

                    <br>
                    <!-- PRZYCISK GENERUJACY PDF -->
                    <button class="btn_wyslij" type="submit" name="submit" value="PDF">Generuj PDF</button>
                    </fieldset>
                </form>
                
            </div>
        </div>
    </body>
</html>

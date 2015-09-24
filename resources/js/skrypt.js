$(document).ready( function(){
    
    
    //wylacza wysylanie formularza za pomoca klawisza enter
    function stopRKey(evt) {
        var evt = (evt) ? evt : ((event) ? event : null);
        var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
        if ((evt.keyCode == 13) && (node.type=="text"))  {return false;}
    }

    document.onkeypress = stopRKey; 


    // podswietla pola nazw obok input, gdy w nich znajda sie jakiekolwiek znaki
    $(document).on('keyup', 'input, textarea', function(){
        var input_id = $(this).attr('id');
        var segment = $(this).attr('data-add');
        if($('#'+input_id).val().length > 0){
            if(input_id == "input_kod" && !($('#'+input_id).val().match(/\d{5}/)) ){
                $("#"+segment).css("background-color", "pink");
            } else if (input_id == "input_email" && !($('#'+input_id).val().match(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/))){
                $("#"+segment).css("background-color", "pink");
            } else {
                $("#"+segment).css("background-color", "#66FF99");
            }
        }else{
            $("#"+segment).css("background-color", "#eee");
        }
    });
    
    
    //usuwa wszystkie znaki poza cyframi z pol #input_kod i #input_liczba_pomieszczen
    $(document).on('keyup', '#input_kod, #input_liczba_pomieszczen, #input_telemarketer_telefon, .izolacja', function () {    
        var th = $(this);
        th.val(th.val().replace(/[^0-9]/g, function(){ return ''; }));
    });
    
    
    //usuwa wszystkie znaki poza cyframi, kropką i przecinkiem  z pol #input_kod i #input_liczba_pomieszczen
    $(document).on('keyup', '.powierzchnia, .wysokosc, .stawka', function () {    
        var th = $(this);
        th.val(th.val().replace(/[^0-9.,]/g, function(){ return ''; }));
    });
    
    
    
    
    //generuje tabelki na podstawie podanej liczby pomieszczen
    $(document).on('click', '#addon11', function(){
        
        var pomieszczenie_no = $('#input_liczba_pomieszczen').val();
        
        var zbior_liczba_pomieszczen = [];
        
        $('.pomieszczenia').find('.pomieszczenie').each(function(index){
            zbior_liczba_pomieszczen.push(index);
        });
        
        liczba_pomieszczen = Math.max.apply(Math, zbior_liczba_pomieszczen);
        
        if(liczba_pomieszczen < 0 ){

            for(var i=0; i<pomieszczenie_no; i++){
                
                nr = i+1;
                
                pomieszczenie(i);
            }
        } else {
        
            var zbior_liczba_pomieszczen = [];    
            
            $('.pomieszczenia').find('.pomieszczenie').each(function(index){
                zbior_liczba_pomieszczen.push(index);
            });
            
            liczba_pomieszczen = Math.max.apply(Math, zbior_liczba_pomieszczen)+1;

            istniejace_i_nowe = parseInt(pomieszczenie_no)+parseInt(liczba_pomieszczen);
            
            for(i = liczba_pomieszczen; i<istniejace_i_nowe; i++){
                
                nr = i+1;
                
                pomieszczenie(i);
            }
        }
        
    });
    
    //tabelki segmentu pomieszczenie
    function pomieszczenie(i){
        $('.pomieszczenia').append('<div class="pomieszczenie" id="pom'+i+'" data-input="input_izolacja'+i+'"><h4>Pomieszczenie '+nr+'</h4><div class="form-group"><div class="input-group nazwa_pomieszczenia"><span class="input-group-addon" id="room'+i+'">Nazwa pomieszczenia</span><input type="text" class="form-control" id="input_nazwa_pomieszczenia'+i+'" data-add="room'+i+'" name="nazwa_pomieszczenia'+i+'" aria-describedby="room'+i+'"></div></div><div class="form-group"><div class="input-group numbers"><span class="input-group-addon" id="area'+i+'">Powierzchnia</span><input type="text" class="form-control powierzchnia" id="input_powierzchnia'+i+'" data-add="area'+i+'" name="powierzchnia'+i+'" aria-describedby="area'+i+'"></div></div><div class="form-group"><div class="input-group numbers"><span class="input-group-addon" id="height'+i+'">Wysokość</span><input type="text" class="form-control wysokosc" id="input_wysokosc'+i+'" data-add="height'+i+'" name="wysokosc'+i+'" aria-describedby="height'+i+'"></div></div><div class="form-group"><div class="input-group numbers"><div class="btn-group input-group-btn dropup"><button type="button" class="btn btn-default dropdown-toggle btn-dpdn" data-toggle="dropdown" id="isolation_button'+i+'" data-input="input_izolacja'+i+'"><span value="null" id="label'+i+'" data-bind="label">Izolacja</span>&nbsp;<span class="caret"></span></button><ul class="dropdown-menu" role="menu"><li data-add="isolation_button'+i+'" value="40"><a href="#">Izolacja(słaba)</a></li><li data-add="isolation_button'+i+'" value="35"><a href="#">Izolacja(średnia)</a></li><li data-add="isolation_button'+i+'" value="30"><a href="#">Izolacja(dobra)</a></li></ul></div><input type="text" class="form-control izolacja" id="input_izolacja'+i+'" data-add="isolation_button'+i+'" name="izolacja'+i+'" aria-label="..."></div></div><div class="form-group"><div class="input-group numbers"><span class="input-group-addon " id="bid'+i+'">Stawka</span><input type="text" class="form-control stawka" data-add="bid'+i+'" id="input_stawka'+i+'" name="stawka'+i+'" aria-describedby="bid'+i+'"></div></div><button class="usun" id="usun'+i+'" data-main="pom'+i+'">usuń</button></div>');
    }
    
    //zamienia wartosc napisu przycisku rozsuwanego menu "izolacja"
    $(document).on( 'click', '.dropdown-menu li', function( event ) {

        var $target = $( event.currentTarget );

        $target.closest( '.btn-group' )
            .find( '[data-bind="label"]' ).text( $target.text() )
            .end()
            .children( '.dropdown-toggle' ).dropdown( 'toggle' );

            
        izolacja = $(this).attr('value');

        button = $(this).attr('data-add');
        izolacja_input_id = $('#'+button).attr('data-input');
        
        if(izolacja != ''){
            $('#'+button).css("background-color", "#66FF99")
            if(izolacja == 30){
                $('#'+izolacja_input_id).val(30);
                $('#'+button).html('Izolacja(dobra)&nbsp;<span class="caret"></span>');
            } else if(izolacja == 35){
                $('#'+izolacja_input_id).val(35);
                $('#'+button).html('Izolacja(średnia)&nbsp;<span class="caret"></span>');
            } else if(izolacja == 40){
                $('#'+izolacja_input_id).val(40);
                $('#'+button).html('Izolacja(słaba)&nbsp;<span class="caret"></span>');
            }
        } else {
            $('#'+button).css("background-color", "#eee")
        }
        
        return false;
        
    });
    
    // pole "izolacja" - zamienia napis i kolor
    $(document).on('click', '.pomieszczenie', function(){
        input_izolacja = $(this).attr('data-input');


        $('#'+input_izolacja).keypress(function() {
            
            wartosc = this.value;
            
            button = $("#"+input_izolacja).attr('data-add');

            if(wartosc <= 40 && wartosc >= 37){
                $('#'+button).html('Izolacja(słaba)&nbsp;<span class="caret"></span>');
            } else if (wartosc <= 36 && wartosc > 33){
                $('#'+button).html('Izolacja(średnia)&nbsp;<span class="caret"></span>');
            } else if (wartosc <= 33 && wartosc >= 30){
                $('#'+button).html('Izolacja(dobra)&nbsp;<span class="caret"></span>');
            } else {
                $('#'+button).html('Izolacja&nbsp;<span class="caret"></span>');
            }
        });
    });
    
    
    // usuwa wybrane(dodane) segemnty 
    $(document).on('click', '.usun', function(e){

        var pom_id = $(this).attr('data-main');
        
        $('#'+pom_id).remove();



        // uaktualnia koncowki nazw id data itp
        var usuniete_id = pom_id.substring(3);        
        zbior_liczba_pomieszczen = [];
        $('.pomieszczenia').find('.pomieszczenie').each(function(){
            
            zbior_liczba_pomieszczen.push(this.id);
            pomieszczenie_id_stala = $(this).attr('id').substring(0, 3);
            pomieszczenie_id_zmienna = $(this).attr('id').substring(3);
            
            nowe_i = parseInt(pomieszczenie_id_zmienna)-1;
            
            
            
            if(pomieszczenie_id_zmienna > usuniete_id){
                $(this).attr("id", pomieszczenie_id_stala+nowe_i);
                pomieszczenie_data_input_stala = $(this).attr('data-input').substring(0, 14);
                $(this).attr("data-input", pomieszczenie_data_input_stala+nowe_i);
            
                $(this).find("h4").html("Pomieszczenie "+(parseInt(pomieszczenie_id_zmienna)));
                
                
                
                //pomieszczenie_nazwa
                $('#room'+pomieszczenie_id_zmienna).attr('id', 'room'+nowe_i);
                
                //input_nazwa_pomieszczenia
                $('#input_nazwa_pomieszczenia'+pomieszczenie_id_zmienna).attr('data-add', 'room'+nowe_i);
                $('#input_nazwa_pomieszczenia'+pomieszczenie_id_zmienna).attr('name', 'nazwa_pomieszczenia'+nowe_i);
                $('#input_nazwa_pomieszczenia'+pomieszczenie_id_zmienna).attr('aria-describedby', 'room'+nowe_i);
                $('#input_nazwa_pomieszczenia'+pomieszczenie_id_zmienna).attr('id', 'input_nazwa_pomieszczenia'+nowe_i);
                
                
                
                //powierzchnia
                $('#area'+pomieszczenie_id_zmienna).attr('id', 'area'+nowe_i);
                
                //input_powierzchnia
                $('#input_powierzchnia'+pomieszczenie_id_zmienna).attr('name', 'powierzchnia'+nowe_i);
                $('#input_powierzchnia'+pomieszczenie_id_zmienna).attr('data-add', 'area'+nowe_i);
                $('#input_powierzchnia'+pomieszczenie_id_zmienna).attr('aria-describedby', 'area'+nowe_i);
                $('#input_powierzchnia'+pomieszczenie_id_zmienna).attr('id', 'input_powierzchnia'+nowe_i);
                
                
                
                //wysokosc
                $('#height'+pomieszczenie_id_zmienna).attr('id', 'height'+nowe_i);
                
                //input_wysokosc
                $('#input_wysokosc'+pomieszczenie_id_zmienna).attr('name', 'wysokosc'+nowe_i);
                $('#input_wysokosc'+pomieszczenie_id_zmienna).attr('data-add', 'height'+nowe_i);
                $('#input_wysokosc'+pomieszczenie_id_zmienna).attr('aria-describedby', 'height'+nowe_i);
                $('#input_wysokosc'+pomieszczenie_id_zmienna).attr('id', 'input_wysokosc'+nowe_i);
                
                
                
                //izolacja
                $('#isolation_button'+pomieszczenie_id_zmienna).attr('data-input', 'input_izolacja'+nowe_i);
                $('#isolation_button'+pomieszczenie_id_zmienna).attr('id', 'isolation_button'+nowe_i);
                $('label'+pomieszczenie_id_zmienna).attr('id', 'label'+nowe_i);
                
                //input_izolacja
                $('#input_wysokosc'+pomieszczenie_id_zmienna).attr('name', 'wysokosc'+nowe_i);
                $('#input_wysokosc'+pomieszczenie_id_zmienna).attr('data-add', 'height'+nowe_i);
                $('#input_wysokosc'+pomieszczenie_id_zmienna).attr('aria-describedby', 'height'+nowe_i);
                $('#input_wysokosc'+pomieszczenie_id_zmienna).attr('id', 'input_wysokosc'+nowe_i);
                $(this).find('li').each(function(){
                    $(this).attr('data-add', 'isolation_button'+nowe_i);
                });
                $('#input_izolacja'+pomieszczenie_id_zmienna).attr('name', 'izolacja'+nowe_i);
                $('#input_izolacja'+pomieszczenie_id_zmienna).attr('data-add', 'isolation_button'+nowe_i);
                $('#input_izolacja'+pomieszczenie_id_zmienna).attr('id', 'input_izolacja'+nowe_i);
                
                
                
                //stawka
                $('#bid'+pomieszczenie_id_zmienna).attr('id', 'bid'+nowe_i);
                
                //input_stawka
                $('#input_stawka'+pomieszczenie_id_zmienna).attr('name', 'stawka'+nowe_i);
                $('#input_stawka'+pomieszczenie_id_zmienna).attr('data-add', 'bid'+nowe_i);
                $('#input_stawka'+pomieszczenie_id_zmienna).attr('aria-describedby', 'bid'+nowe_i);
                $('#input_stawka'+pomieszczenie_id_zmienna).attr('id', 'input_stawka'+nowe_i);
                
                
                
                //przycisk usun
                $('#usun'+pomieszczenie_id_zmienna).attr('data-main', 'pom'+nowe_i);
                $('#usun'+pomieszczenie_id_zmienna).attr('id', 'usun'+nowe_i);

            }
        });
    });
    
    
    // sprawdza ilosc formularzy pomieszczen a nastepnie generuje plik pdf
    $(document).on('click', '.btn_wyslij', function(e){
        
        zbior_liczba_pomieszczen = [];
        
        $('.pomieszczenia').find('.pomieszczenie').each(function(index){
            zbior_liczba_pomieszczen.push(index);
        });
        
        liczba_pomieszczen = Math.max.apply(Math, zbior_liczba_pomieszczen)+1;
        
        $('#input_liczba_pomieszczen').val(liczba_pomieszczen);
        
        //e.preventDefault();
    });
    
});
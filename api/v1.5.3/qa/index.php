<html>
<head>
<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script></head>
<body>

<!--<a href="../phrases/empty">Leer Frases</a>-->

<div id="error" style="display:none">
  <h1>Frases</h1>
  Se presentó el siguiente error:
</div>

<div id="espere">
  <h1>Frases</h1>
  Espere, buscando frases por definir...
</div>


<div id="resultado" style="display:none">
  <h1>Frases</h1>
</div>

</body>
</html>
<script type="text/javascript">
//$.getJSON( "../phrases/empty?max=8", function( data ) {
$.getJSON( "../phrases/nextempty?max=8", function( data ) {
  console.log( "success" );
})
  .done(function(data) {
      
      //alert(data);
      $ ('<p/>', { 
          html: data 
      }).appendTo( "#resultado" );
      
      var items = [];
      items.push("<tr><th>NroReg</th><th>Cita</th><th>Contenido</th><th>Frase</th><th>Acciones</th></tr>");
      $.each( data, function( key, val ) {
          items.push( "<tr><th><small>" + val.idreg + "</small></th><th>" + val.cita + "</th><td>" + 
                      "<textarea class='contenido' rows='8' cols='60' id=conte_" + val.idreg + ">" + val.contenido + "</textarea></td>" + 
                      "<td><input class='phrase' style='width:350px' id=phrase_" + val.idreg + "></td>" + 
                      "<td><a href='javascript:guardar(" + val.idreg + ")' >Guardar</a>" + 
                      "</td>" + 
                      "</tr>" );
          /*items.push( "<b>" + key + "</b>" + 
                      "<i>" + val + "</i>" + 
                      "<br>" );*/
      });
     
      $( "<table/>", {
        "class": "my-new-table",
        html: items.join( "" )
      }).appendTo( "#resultado" );
      
      /*$( "<a/>", {
         "class": "my-new-link",
         "href": "javascript:guardar()",
         html: "Guardar",
      }).appendTo( "body" );*/
      
      $ ( '#resultado').show();
    
    })
  .fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
    
    $( '<p/>', {
      html: "Request Failed: " + err
    }).appendTo('#resultado');
      
      $ ( '#error').show();
  })
  .always(function() {
    //console.log( "complete" );
    $ ( '#espere').hide();
  });

</script>
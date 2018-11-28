// jimte (c) jumanja.net - 2018 - version 1.7
class JimteMan {
/*Simulate events like click*/
eventFire(el, etype){
  if (el.fireEvent) {
    el.fireEvent('on' + etype);
  } else {
    var evObj = document.createEvent('Events');
    evObj.initEvent(etype, true, false);
    el.dispatchEvent(evObj);
  }
}
  getStateTitle(state) {
    for (var i = 0; i < menuItems.length; i++) {
      if (menuItems[i].state === state) {
        return menuItems[i].item;
      }
    }
  }
  getIcon(state) {
    for (var i = 0; i < menuItems.length; i++) {
      if (menuItems[i].state === state) {
        return menuItems[i].icon;
      }
    }
  }

 changeState(state) {
    //var appContentContext = { 'state': state, 'title': getStateTitle(state), 'icon': getIcon(state) };
    //appContent.innerHTML = MonitorApp.content(appContentContext);

    //next lines to load real mainContent
    //var statePage = document.querySelector('#' + state);

    //console.log("changeState: " + state);
    //statePage.innerHTML = MonitorApp[state]();

    if (state == 'cerrarSesion') {
      this.token = "";
      this.userType = "";
      this.defaultOption = "iniciarSesion";
      //window.location.reload(true);
      //window.location.reload(true);

      //location.replace(this.thisURL);
      $("body").hide();

      document.getElementById('myHomePage').click();
    } else {
      if (state != 'iniciarSesion') {
        //this.buildSideMenu();
        this.buildInnerPage(state);

      }
    }

    $('.menuLinks').removeClass('menuActive');
    $('#' + state + 'Link').addClass('menuActive');
    //$('.button-collapse').sideNav();

  }

//  changeState('actividadFisica');
//
  /* now constructor
  */
    constructor() {

        //for Login
        this.submitEvent();
        this.currentUser = "";
        this.lastResponse = "";
        if (window.location.href.indexOf("localhost") > -1) {
          //this.token = "localhost";
          //this.userType = "A";
          //this.defaultOption = "cuadroMando";

          this.token = "";
          this.userType = "A";
          this.defaultOption = "";

        } else {
          this.token = "";
          this.userType = "T";
          this.defaultOption = "";

        }

        this.llave = "";
        this.apellidos = "";
        this.nombres = "";

        //this.mesastestigos = array();
        this.thisURL = "";

        this.currentArt = "";
        this.currentLang = "es";

        //now try to addValuesTranslate
        this.currentLang  = location.search.split('lang=')[1] ? location.search.split('lang=')[1] : this.currentLang ;

        this.currentMode = "";
        this.params = "";
        this.params = this.parseQueryString(window.location.search);
        if(this.params.art != "" && this.params.art != undefined){
          this.currentArt = this.params.art.substring(0,3);
        }
        //}

        this.carousel = [];
        this.carouselIndex = 1;
        this.carouselInterval = 0;

        this.includesPath = 'ui/includes/';
        this.articlesPath = 'ui/articles/';
        this.layoutPath = 'ui/layouts/';
        this.configPath = 'ui/config/';
        this.serverPath = 'api/v1.5.3/';
        this.imagesPath = 'ui/img/';
        this.imagesArticlePath = 'ui/articles/';
        this.header = 'header.json';
        this.tables = 'tables.json';
        this.sideMenu = 'sidemenu.json';
        this.navigation = 'navigation.json';
        this.imageCar = 'imageCar.json';
        this.imageNav = 'imageNav.json';
        this.footer = 'footer.html';
        this.central = 'article000.json';

        this.buildHeader();
        /*this.buildNavigation();
        this.buildImageCar();
        this.buildCentral();

        this.buildImageNav();
        */
        this.buildSideMenu(this.token);
        this.buildFooter();

        $("body").show();


    }
/*
NOT WORKING on SAFARI for MAC
    submitEvent(){
      $('#loginForm').submit((event)=>{
        event.preventDefault();
        this.sendForm();
      })
    }
    */
    submitEvent(){
      var self = $(this);
      $('#loginForm').on('submit', function(event){
        event.preventDefault();
        jimte.sendForm();
      });
    }

    sendForm(){
      var self = $(this);
      $("#loginWorking").show();
      /*
      NOT WORKING ON SAFARI for mac
      let form_data = new FormData();
      */
      var form_data = new FormData();
      form_data.append('usuario', $('#usuario').val());
      form_data.append('password', $('#password').val());
      form_data.append('lang', this.currentLang);

      //console.log("sendForm!" + form_data);
      $.ajax({
        url: this.serverPath + 'index.php/login',
        dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        data: form_data,
        type: 'POST',
        success: function(php_response){
          //console.log("presp: " + php_response[0].acceso);
          $("#loginWorking").hide();

          if (php_response[0].acceso == undefined) {
            //window.location.href = 'main.html';
            jimte.currentUser = new User(php_response[0]);
            jimte.token = php_response[0].token;
            jimte.userType = php_response[0].rol;
            jimte.apellidos = php_response[0].apellidos;
            jimte.nombres = php_response[0].nombres;
            jimte.llave = php_response[0].usuario;
            jimte.defaultOption = "";
            jimte.buildSideMenu(php_response.token);
            $("#languageSelect").hide();

            M.toast(
                      {html:'Bienvenido(a) <br>' + jimte.nombres +'!',
                      displayLenght: 3000,
                      classes: 'rounded'}
                    );

          }else {
            //alert(php_response.acceso + " " + php_response.motivo);
            jimte.alertMe(l("%denied", php_response[0].acceso) + " " +
                          l("%userNotFound", php_response[0].motivo), l("%iniciarSesion", "Ingreso al Sistema"));
          }
        },
        error: function(xhr, status, error) {
            //alert(xhr.responseText + "\nCon el error:\n" + error);
            /*
            Error: SQLSTATE[HY000] [2002] No such file or directory
Fatal error: Call to a member function prepare() on a non-object in /Library/WebServer/Documents/jumanja.net/SISGA/api/v1.5.3/app/routes/api.php on line 25
Con el error: SyntaxError: Unexpected token E in JSON at position 0
*/
            $("#loginWorking").hide();

            if(xhr.responseText.startsWith("Error: SQLSTATE[HY000]")){
              jimte.alertMe("Al parecer No hay conexión con la base de datos, Por favor Reintente más tarde. \nSi el problema persiste por favor repórtelo al Administrador.", "Ingreso al Sistema");

            } else {
              jimte.alertMe(xhr.responseText + "\nCon el error:\n" + error, "Ingreso al Sistema");

            }
        }
      })
    }

    displayMode() {
      if(this.currentMode == "blocks"){
        $(".hide-mode-blocks").hide();
      }
      if(this.currentMode == "blocks"){
        $(".show-mode-blocks").show();
      }
      if(this.currentMode == "proto"){
        $(".hide-mode-proto").hide();
      }
      if(this.currentMode == "proto"){
        $(".show-mode-proto").show();
      }
    }

    buildHeader() {
        var self = $(this);
        //let url = this.configPath + this.currentLang + '_' + this.header;
        /*
        NOT WORKING ON SAFARI
        let url = this.configPath + this.header;
        */
        var url = this.configPath + this.header;
        //console.log(url);

        //call header
        $.ajax({
          url: url,
          dataType: "json",
          cache: false,
          processData: false,
          contentType: false,
          context: this,
          type: 'GET',
          success: function(data) {
            /*if (data.msg=="OK") {
              this.poblarCalendario(data.sections)
            }else {
              alert(data.msg)
              window.location.href = 'index.html';
            }*/
            var metas = [];
            $.each( data.metas, function( key, val ) {
              var attribs = [];
              //console.log(val);
              $.each( val, function( key2, val2 ) {
                //console.log(key2 + ' = "' + val2 + '"');
                attribs.push(key2 + ' = "' + val2 + '"');
              });
              metas.push( "<meta " + attribs.join(" ") + ">" );
            });
            //console.log(metas.join(""));

            var links = [];
            $.each( data.links, function( key, val ) {
              links.push( "<link rel='" + val.rel + "' " +
                          "href='" + val.href + "'>");
            });

            var scripts = [];
            $.each( data.scripts, function( key, val ) {
              scripts.push( "<script src='" + val.src + "'></script>" );
            });
            $("head")[0].innerHTML = metas.join("") +
                                    links.join("") +
                                    scripts.join("");

            document.title = data.title;
            $("#headerImg_all").attr("src", this.imagesPath + data.img_all);
            $("#headerImg_xs").attr("src", this.imagesPath + data.img_xs);
            $("html").attr("lang", data.defaultLang);
            $("#org")[0].innerHTML = data.org;
            $("#org_URL").attr("href", data.URL);
            $("#org_URL")[0].innerHTML = data.URL;
            this.thisURL = data.thisURL;
            this.currentLang = data.defaultLang;
            this.currentMode = data.defaultMode;
            this.displayMode();

            /*$( "<ul/>", {
              "class": "my-new-list",
              html: items.join( "" )
            }).appendTo( "body" );*/
            //now try to addValuesTranslate
            this.currentLang  = location.search.split('lang=')[1] ? location.search.split('lang=')[1] : this.currentLang ;
            t(this.currentLang, "div");


          },
          error: function(xhr, status, error) {
              //alert('buildHeader failed: ' + xhr.responseText + "\nWith error:\n" + error);
              console.log('buildHeader failed: ' + xhr.responseText + "\nWith error:\n" + error);
          },
          error2: function(){
            //alert("buildHeader: error with server communication");
            console.log("buildHeader: error with server communication");
          }
        })
    }

    buildSideMenu(Token){
      var self = $(this);
      //let url = this.configPath + this.currentLang + '_' + this.sideMenu;
      var url = this.configPath + this.sideMenu;

      $('li a.menuLinks').parent().remove();
      $.ajax({
        url: url,
        dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        context: this,
        type: 'GET',
        success: function(data) {
          var links = [];

          $.each( data, function( key, val ) {

            if(val.status == "A"){
              var activeLink = (val.state == "iniciarSesion" ? "menuActive" : "");
              jimte.isLogged = false;
              jimte.isLogged = ((jimte.token == undefined || jimte.token == null || jimte.token == "") ? false : true);

              //console.log(val.item  + " val.isLogged:" + val.isLogged + " jimte.isLogged " + jimte.isLogged);

              if( (val.isLogged == "Y" && jimte.isLogged) ||
                  (val.isLogged == "N" && !jimte.isLogged) ){

                    //console.log(key + " / "+ jimte.defaultOption);

                    //console.log(jimte.userType + " / " + val.type);
                    if(val.type.indexOf(jimte.userType) !== -1) {
                      if(jimte.defaultOption == "") {
                        jimte.defaultOption = key;
                      }

                      links.push( "<li><a href='#' class='menuLinks " + activeLink + "' " +
                                  'onclick="jimte.changeState(\'' + key + '\')" ' +
                                  'id="' + key + 'Link" >'  +
                                  '<i class="material-icons text-primary-color">' + val.icon + '</i>' +
                                  "<span translate='yes' id='sp_"+key+"' class=''>" + val.item + "</span></a></li>");

                    }

              }


            }
          });

          $("#sideMenu").append(links.join(""));
          t(this.currentLang, "#sideMenu");

          if(jimte.apellidos != "" && jimte.nombres) {
            $("#userFirstName").text(jimte.nombres);
            $("#userLastName").text(jimte.apellidos);
            $("#userToken").text(jimte.llave);
          } else {
            if(jimte.token == "localhost"){
              $("#userFirstName").text("Web");
              $("#userLastName").text("Master");
              $("#userToken").text("webmaster");

            } else {
              $("#userFirstName").text(t(this.currentLang, "userFirstName"));
              $("#userLastName").text(t(this.currentLang, "userLastName"));
              $("#userToken").text("");

            }
          }

          if(jimte.defaultOption != "") {
            jimte.changeState(jimte.defaultOption);
          }

        },
        error: function(xhr, status, error) {
            //alert('buildSideMenu failed: ' + xhr.responseText + "\nWith error:\n" + error);
            console.log('buildSideMenu failed: ' + xhr.responseText + "\nWith error:\n" + error);
        },
        error2: function(){
          //alert("buildSideMenu: error with server communication");
          console.log("buildSideMenu: error with server communication");
        }
      })
    }

    buildNavigation() {
      var self = $(this);
      //let url = this.configPath + this.currentLang + '_' + this.navigation;
      var url = this.configPath + this.navigation;
      //console.log(url);
      //call header
      $.ajax({
        url: url,
        dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        context: this,
        type: 'GET',
        success: function(data) {
          var esteItem = "";
          var links = [];
          //console.log(this.imagesPath);
          var imagePath = this.imagesPath;
          $.each( data, function( key, val ) {
            //console.log("0x: " + key + " " + val.href);
            //console.log(val);

            if(val.status == "A"){
              var aType = "";
              var aCaret = "";
              if(val.content == undefined){
                aType = '';
                aCaret = '';
              } else {
                aType = "class='dropdown-toggle' data-toggle='dropdown' ";
                aCaret = "<span class='caret'>";
              }

                links.push("<li class='dropdown'>" +
                    "<a " + aType +
                    (val.target == undefined ? "" :
                      "target='"+ val.target + "' ") +
                     "href='"+
                    (val.href == undefined ? "#" :
                      val.href) + "'" +
                      ">" +
                     key +
                     aCaret +
                     "</a>");

                if(val.content == undefined){

                } else {
                  links.push("<ul class='dropdown-menu'>");
                  $.each( val.content, function( key2, val2 ) {

                    //val2.href = "index.php?art=" + key2;
                    if(val2.status == "A"){

                        val2.href = "?art=" + key2;

                        links.push("<li " +
                            (val2.content == undefined ? "" :
                            "class='dropdown-toggle' data-toggle='dropdown' ") +
                            ">" +
                            "<a " +
                            'onclick="javscript:jimte.buildCentral(\'' + key2 + '\');" ' +
                            (val2.target === undefined ? "" :
                              "target='"+ val2.target + "' ") +
                            ">" +
                             val2.name + "</a></li>");
/*
                             "href='"+
                            (val2.href == undefined ? "#" :
                              val2.href) + "'" +
*/
                    }

                  });
                  links.push("</ul>");

                }
                links.push("</li>");

            }
          });
          //console.log(links.join(""));
          $("#mainNavigation")[0].innerHTML = links.join("");

        },
        error: function(xhr, status, error) {
            //alert('buildNavigation failed: ' + xhr.responseText + "\nWith error:\n" + error);
            console.log('buildNavigation failed: ' + xhr.responseText + "\nWith error:\n" + error);
        },
        error2: function(){
          //alert("buildNavigation: error with server communication");
          console.log("buildNavigation: error with server communication");
        }
      })
    }

    buildImageCar() {
      var self = $(this);
      //let url = this.configPath + this.currentLang + '_' + this.imageCar;
      var url = this.configPath + this.imageCar;
      /*console.log(url);*/

      //call header
      $.ajax({
        url: url,
        dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        context: this,
        type: 'GET',
        success: function(data) {
          var carousel = [];
          var carouselPath = data.carouselPath;
          //console.log(this.imagesPath);
          var imagePath = this.imagesPath;
          $.each( data.links, function( key, val ) {
            //console.log(key);
            //console.log(this.imagesPath);

            if(val.status == "A"){
              //"style='order:" + orderme + "' " +

              carousel.push("<a class='carousel-link' " +
                  (val.target == undefined ? "" :
                     "target='"+ val.target + "' ") +
                  (val.href == undefined ? "href='#'" :
                     "href='"+ val.href + "' ") + ">" +
                     "<img style='margin:auto' " +
                  (val.title == undefined ? "" :
                     "title='"+ val.title + "' ") +
                  (val.src == undefined ? "" :
                     "src='" + carouselPath + val.src ) + "' " +
                     "></a>");

            }
          });
          //console.log(links.join(""));
          $("#imageCar")[0].innerHTML = carousel[0];
          $("#carousel-prev").css("display", "inline");
          $("#carousel-next").css("display", "inline");
          this.carousel = carousel;
          if(this.currentArt !== undefined && this.currentArt !== "000"){
            $("#imageCarContainer").css("display","none");
            this.stopCarousel();
          } else {
            $("#imageCarContainer").css("display","block");
            //this.startCarousel();
          }
          //console.log("imageCar:" + this.currentArt);

          //console.log(this.carousel) ;


          ;
        },
        error: function(xhr, status, error) {
            //alert('buildImageNav failed: ' + xhr.responseText + "\nWith error:\n" + error);
            console.log('buildImageNav failed: ' + xhr.responseText + "\nWith error:\n" + error);
        },
        error2: function(){
          //alert("buildImageNav: error with server communication");
          console.log("buildImageNav: error with server communication");
        }
      })
    }

    nextOnCarousel(){
      //console.log(this.carouselIndex + "/" +  this.carousel.length);
      if(this.carouselIndex + 1 > this.carousel.length){
        this.carouselIndex = 1;
      } else {
        this.carouselIndex += 1;
      }
      this.updateCarousel();
      //console.log(this.carouselIndex + "/" +  this.carousel.length);


    }

    prevOnCarousel(){
      //console.log(this.carouselIndex + "/" +  this.carousel.length);

      if(this.carouselIndex - 1 <= 0){
        this.carouselIndex = this.carousel.length;
      } else {
        this.carouselIndex -= 1;
      }

      this.updateCarousel();
      //console.log(this.carouselIndex + "/" +  this.carousel.length);
    }

    updateCarousel(){
        var self = $(this);
        $("#imageCar").animate({ 'opacity':0.5 }, 'slow');
          $("#imageCar")[0].innerHTML = this.carousel[this.carouselIndex - 1];
          $("#imageCar").animate({'opacity':1}, 'slow');

          //console.log("updating imageCar..." + $("#imageCarContainer")[0]);

      if(this.currentArt !== undefined && this.currentArt !== "000"){
        $("#imageCarContainer").css("display","none");
        this.stopCarousel();
      } else {
        $("#imageCarContainer").css("display","block");
        //this.startCarousel();
      }
    }

    stopCarousel(){
      clearInterval(this.carouselInterval );
    }
    startCarousel(){
      //this.carouselInterval = setInterval(function(){ this.nextOnCarousel(); }, 7000);
    }

    buildImageNav() {
      var self = $(this);
      //let url = this.configPath + this.currentLang + '_' + this.imageNav;
      var url = this.configPath + this.imageNav;
      /*console.log(url);*/

      //call header
      $.ajax({
        url: url,
        dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        context: this,
        type: 'GET',
        success: function(data) {
          var links = [];
          //console.log(this.imagesPath);
          var imagePath = this.imagesPath;
          $.each( data.links, function( key, val ) {
            //console.log(key);
            //console.log(this.imagesPath);

            if(val.status == "A"){
                links.push("<a " +
                     "target='"+ val.target + "' " +
                     "href='"+ val.href + "'>" +
                     "<img " +
                     "title='"+ val.title + "' " +
                     "src='" + imagePath + val.src + "'></a>");

            }
          });
          //console.log(links.join(""));
          $("#imageNav")[0].innerHTML = links.join("");

        },
        error: function(xhr, status, error) {
            //alert('buildImageNav failed: ' + xhr.responseText + "\nWith error:\n" + error);
            console.log('buildImageNav failed: ' + xhr.responseText + "\nWith error:\n" + error);
        },
        error2: function(){
          //alert("buildImageNav: error with server communication");
          console.log("buildImageNav: error with server communication");
        }
      })
    }

    buildFooter() {
        var self = $(this);
        //let url = this.includesPath + this.currentLang + '_' + this.footer;
        var url = this.includesPath + this.footer;
        /*console.log(url);*/

        //call header
        $.ajax({
          url: url,
          dataType: "html",
          cache: false,
          processData: false,
          contentType: false,
          context: this,
          type: 'GET',
          success: function(data){
            $("footer")[0].innerHTML = data;
            t(this.currentLang, "footer");

          },
          error: function(xhr, status, error) {
              //alert('buildFooter failed: ' + xhr.responseText + "\nWith error:\n" + error);
              console.log('buildFooter failed: ' + xhr.responseText + "\nWith error:\n" + error);
          },
          error2: function(){
            //alert("buildFooter: error with server communication");
            console.log("buildFooter: error with server communication");
          }
        })
    }

    buildInnerPage(key) {
        $(".mainContent").hide();
        //$("main").addClass("oculto");
        //$("#" + key).removeClass("oculto");
        $("#" + key).show();

        //key == reportarMesa
        if(key == "elaborarActas" || key == "abrirCertif"){
          $("#progresoActas").show();

          $("#loader").show();

          this.check_actas();
        }
        if(key == "configurarTablas") {

          this.check_tables();
        }
        if(key == "cuadroMando") {
          //$("footer")[0].style.marginTop = '600px';
          //backgroundColor: ['rgba(153, 102, 255, 0.2)', 'rgba(75, 192, 192, 0.2)' ],
          //borderColor: ['rgba(153, 102, 255, 1)', 'rgba(75, 192, 192, 1)', 'rgba(255, 159, 64, 1)'],

          //var ctx = document.getElementById('myChart').getContext('2d');
          //jimte_table.check_grafica(ctx);

        }
    }

    getEstadosActa(){
      $.ajax({
        url: this.serverPath + 'index.php/mins/count' +
              "?id=" + jimte.currentUser.id +
              "&tiporol=" + jimte.currentUser.tiporol +
              "&rol=" + jimte.currentUser.rol +
              "&grupo=" + jimte.currentUser.grupo +
              "&table=" + this.table +
              "&token=" + jimte.token +
              "&sqlCode=" + "mins_exec" ,

        dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        type: 'GET',
        success: function(data){
          if ((typeof data !== undefined ) &&
               (data.length == 0 || data[0].acceso == undefined)) {

                 var actasEjecu = 0;
                 var actasTotal = 0;
                 $.each( data, function( key, val ) {

                   actasTotal += parseInt(val.cuenta);

                   //Aprobadas
                   if(val.estado == "A"){
                     actasEjecu += parseInt(val.cuenta);
                     $('#actas_aprobadas')[0].innerHTML = val.cuenta;
                   }

                   //Preliminares
                   if(val.estado == "M"){
                     $('#actas_preliminares')[0].innerHTML = val.cuenta;
                   }

                   //En Progreso
                   if(val.estado == "G"){
                     $('#actas_progreso')[0].innerHTML = val.cuenta;
                   }

                   //Retiradas
                   if(val.estado == "R"){
                      actasEjecu += parseInt(val.cuenta);
                      $('#actas_retiradas')[0].innerHTML = val.cuenta;
                   }

                 });
                 /*console.log("aprob: " + actasAprob);
                 console.log("total: " + actasTotal);*/
                 var actasPorc = ( actasEjecu / actasTotal ) * 100;
                 actasPorc = actasPorc.toFixed(0);
                 $(".actas_ejec")[0].innerHTML = actasPorc;
                 $(".actas_bar").css("width", actasPorc + "%");

          }else {
            jimte.alertMe(l("%denied", data[0].acceso) + " " +
                          l("%userNotFound", data[0].motivo), l("%iniciarSesion", "No se pudo Actualizar"));

          }
        },
        error: function(xhr, status, error) {
            //alert(xhr.responseText + "\nCon el error:\n" + error);
            console.log(xhr.responseText + "\nCon el error:\n" + error);
        }
      })
    }

    cleanActa(){
      $("#lugar_reunion").val("");
      $("#tipo_de_acta").val("");
      $("#fecacta").val("");
      $("#horacta").val("");
      $("#lugar_reunion").val("");
      $("#fecproxima").val("");
      $("#horproxima").val("");
      $("#lugar_proxima").val("");
      $("#estado").val("G");

      $('.chips-autocomplete').chips({
         data: [],
         autocompleteOptions: {
             data: jimte.currentTags
         },
         placeholder: 'Ingrese Etiquetas',
         secondaryPlaceholder: '+Etiqueta',
         limit: 10,
         minLength: 1
       });

      $("#temaacta").val("");
      $("#fecacta").val("");
      $("#objetivos").val("");
      $("#conclusiones").val("");

      $("#lugar_reunion").formSelect();
      $("#tipo_de_acta").formSelect();
      $("#fecacta").formSelect();
      $("#horacta").formSelect();
      $("#lugar_reunion").formSelect();
      $("#fecproxima").formSelect();
      $("#horproxima").formSelect();
      $("#lugar_proxima").formSelect();
    }

    getActaId(nroActa){
      $.ajax({
        url: this.serverPath + 'index.php/mins' +
              "?id=" + jimte.currentUser.id +
              "&tiporol=" + jimte.currentUser.tiporol +
              "&rol=" + jimte.currentUser.rol +
              "&grupo=" + jimte.currentUser.grupo +
              "&table=" + this.table +
              "&token=" + jimte.token +
              "&sqlCode=" + "mins_nro" +
              "&nroActa=" + nroActa,

        dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        type: 'GET',
        success: function(data){
          if ((typeof data !== undefined ) &&
               (data.length == 0 || data[0].acceso == undefined)) {

                 //ciclo
                 $.each( data, function( key, val ) {
                   //console.log(val.tema);
                   $("#tipo_de_acta").val(val.tipoacta);
                   $("#acta_a_elaborar").val(val.id);
                   $("#temaacta").val(val.tema);
                   $("#lugar_reunion").val(val.lugar);
                   $("#objetivos").val(val.objetivos);
                   $("#conclusiones").val(val.conclusiones);
                   $("#estado").val(val.estado);

                   var resArray = val.fecha.split(" ");
                   $("#fecacta").val(resArray[0]);
                   $("#horacta").val(resArray[1]);

                   resArray = val.fechasig.split(" ");
                   $("#fecproxima").val(resArray[0]);
                   $("#horproxima").val(resArray[1]);

                   $("#lugar_proxima").val(val.lugarsig);

                 });
                 $("#lugar_reunion").formSelect();
                 $("#tipo_de_acta").formSelect();
                 $("#acta_a_elaborar").formSelect();
                 $("#fecacta").formSelect();
                 $("#horacta").formSelect();
                 $("#lugar_reunion").formSelect();
                 $("#fecproxima").formSelect();
                 $("#horproxima").formSelect();
                 $("#lugar_proxima").formSelect();

                jimte.getTagsMinId($("#acta_a_elaborar").val());

          }else {
            jimte.alertMe(l("%denied", data[0].acceso) + " " +
                          l("%userNotFound", data[0].motivo), l("%iniciarSesion", "No se pudo Actualizar"));

          }
        },
        error: function(xhr, status, error) {
            //alert(xhr.responseText + "\nCon el error:\n" + error);
            console.log(xhr.responseText + "\nCon el error:\n" + error);
        }
      })
    }

    getTagsMinId(nroActa){
      $.ajax({
        url: this.serverPath + 'index.php/mins/items' +
              "?id=" + jimte.currentUser.id +
              "&tiporol=" + jimte.currentUser.tiporol +
              "&rol=" + jimte.currentUser.rol +
              "&grupo=" + jimte.currentUser.grupo +
              "&table=" + this.table +
              "&token=" + jimte.token +
              "&sqlCode=" + "tags_minid" +
              "&nroActa=" + nroActa,

        dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        type: 'GET',
        success: function(data){
          if ((typeof data !== undefined ) &&
               (data.length == 0 || data[0].acceso == undefined)) {

                 $('.chips-autocomplete').chips({
                    data: [],
                    autocompleteOptions: {
                        data: jimte.currentTags
                    },
                    placeholder: 'Ingrese Etiquetas',
                    secondaryPlaceholder: '+Etiqueta',
                    limit: 10,
                    minLength: 1
                  });

                 var instance = M.Chips.getInstance ( $('#etiquetasActa') );

                 //var actaTags = [];
                 $.each( data, function( key, val ) {
                    //actaTags.push({"tags": val.etiqueta});

                    //console.log(key + "/" + val.etiqueta);
                    /*$('#etiquetasActa').append("<div class='chip' tabindex='0'>" +
                                        val.etiqueta +
                                        "<i class='material-icons'>close</i>" +
                                        "</div>");*/
                    //instance.addChip({"tags": val.etiqueta});
                    instance.addChip({
                      tag: val.etiqueta
                    });

                 });

                 //$('.chips-autocomplete')[0].chips({
                 /*$('#etiquetasActa').chips({
                    chipsData: actaTags,
                    data: actaTags,
                    autocompleteOptions: {
                        data: jimte.currentTags
                    },
                    placeholder: 'Ingrese Etiquetas',
                    secondaryPlaceholder: '+Etiqueta',
                    limit: 10,
                    minLength: 1
                  });*/

          }else {
            jimte.alertMe(l("%denied", data[0].acceso) + " " +
                          l("%userNotFound", data[0].motivo), l("%iniciarSesion", "No se pudo Actualizar"));

          }
        },
        error: function(xhr, status, error) {
            //alert(xhr.responseText + "\nCon el error:\n" + error);
            console.log(xhr.responseText + "\nCon el error:\n" + error);
        }
      })
    }

    getActasProgreso(){
      $.ajax({
        url: this.serverPath + 'index.php/mins' +
              "?id=" + jimte.currentUser.id +
              "&tiporol=" + jimte.currentUser.tiporol +
              "&rol=" + jimte.currentUser.rol +
              "&grupo=" + jimte.currentUser.grupo +
              "&table=" + this.table +
              "&token=" + jimte.token +
              "&sqlCode=" + "mins_prog" ,

        dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        type: 'GET',
        success: function(data){
          if ((typeof data !== undefined ) &&
               (data.length == 0 || data[0].acceso == undefined)) {

                 $('#acta_a_elaborar').children('option:not(:first)').remove();

                 var option = $('<option></option>').attr("value",
                   "add"
                 ).text(
                   "Agregar Acta Nueva"
                 );
                 $("#acta_a_elaborar").append(option);

                 //Limpiar la Tabla
                 $('#tbody_enprogreso')[0].innerHTML  = "";

                 //ciclo
                 $.each( data, function( key, val ) {

                     //$('#tbody_enprogreso')[0].innerHTML += "<tr><td>Ver</td>" +
                     $('#tbody_enprogreso')[0].innerHTML += "<tr>" +
                        '<td>' + val.id + '</td>' +
                        '<td>' + val.fecha + '</td>' +
                        '<td>' + val.objetivos + '</td>' +
                        '<td>' + val.conclusiones + '</td>' +
                        '</tr>';

                    var option = $('<option></option>').attr("value",
                      val.id
                    ).text(
                      "Nro: " + val.id + " " +
                      "Fec: " + val.fecha
                    );
                    $("#acta_a_elaborar").append(option);

                 });
                 $('#acta_a_elaborar').formSelect();

          }else {
            jimte.alertMe(l("%denied", data[0].acceso) + " " +
                          l("%userNotFound", data[0].motivo), l("%iniciarSesion", "No se pudo Actualizar"));

          }
        },
        error: function(xhr, status, error) {
            //alert(xhr.responseText + "\nCon el error:\n" + error);
            console.log(xhr.responseText + "\nCon el error:\n" + error);
        }
      })
    }

    getTiposActa(){
      $.ajax({
        url: this.serverPath + 'index.php/types' +
              "?id=" + jimte.currentUser.id +
              "&tiporol=" + jimte.currentUser.tiporol +
              "&rol=" + jimte.currentUser.rol +
              "&grupo=" + jimte.currentUser.grupo +
              "&table=" + this.table +
              "&token=" + jimte.token +
              "&sqlCode=" + "types_act" ,

        dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        type: 'GET',
        success: function(data){
          if ((typeof data !== undefined ) &&
               (data.length == 0 || data[0].acceso == undefined)) {
                 $('#tipo_de_acta')[0].innerHTML = '<option value="" ' +
                          'disabled selected>Seleccione Tipo Acta</option>';
                 $.each( data, function( key, val ) {

                   //console.log(key + "/" + val.tipo + "/" + val.nombre);
                   $('#tipo_de_acta').append($('<option>', {
                        value: val.tipo,
                        text: val.nombre
                    }));

                 });
                 $('#tipo_de_acta').formSelect();

          }else {
            jimte.alertMe(l("%denied", data[0].acceso) + " " +
                          l("%userNotFound", data[0].motivo), l("%iniciarSesion", "No se pudo Actualizar"));

          }
        },
        error: function(xhr, status, error) {
            //alert(xhr.responseText + "\nCon el error:\n" + error);
            console.log(xhr.responseText + "\nCon el error:\n" + error);
        }
      })
    }

    getLugares(){
      $.ajax({
        url: this.serverPath + 'index.php/places' +
              "?id=" + jimte.currentUser.id +
              "&tiporol=" + jimte.currentUser.tiporol +
              "&rol=" + jimte.currentUser.rol +
              "&grupo=" + jimte.currentUser.grupo +
              "&table=" + this.table +
              "&token=" + jimte.token +
              "&sqlCode=" + "places_act" ,

        dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        type: 'GET',
        success: function(data){
          if ((typeof data !== undefined ) &&
               (data.length == 0 || data[0].acceso == undefined)) {

                 $('#lugar_reunion')[0].innerHTML = '<option value="" disabled selected>Seleccione Lugar</option>';
                 $('#lugar_proxima')[0].innerHTML = '<option value="" disabled selected>Seleccione Lugar</option>';

                 $.each( data, function( key, val ) {

                   //console.log(key + "/" + val.id + "/" + val.lugar);
                   $('#lugar_reunion').append($('<option>', {
                        value: val.id,
                        text: val.lugar
                    }));
                    $('#lugar_proxima').append($('<option>', {
                         value: val.id,
                         text: val.lugar
                     }));
                 });
                 $('#lugar_reunion').formSelect();
                 $('#lugar_proxima').formSelect();

          }else {
            jimte.alertMe(l("%denied", data[0].acceso) + " " +
                          l("%userNotFound", data[0].motivo), l("%iniciarSesion", "No se pudo Actualizar"));

          }
        },
        error: function(xhr, status, error) {
            //alert(xhr.responseText + "\nCon el error:\n" + error);
            console.log(xhr.responseText + "\nCon el error:\n" + error);
        }
      })
    }

    getAsistentes(){
      $.ajax({
        url: this.serverPath + 'index.php/users' +
              "?id=" + jimte.currentUser.id +
              "&tiporol=" + jimte.currentUser.tiporol +
              "&rol=" + jimte.currentUser.rol +
              "&grupo=" + jimte.currentUser.grupo +
              "&table=" + this.table +
              "&token=" + jimte.token +
              "&sqlCode=" + 'users_int',
        dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        type: 'GET',
        success: function(data){
          if ((typeof data !== undefined ) &&
               (data.length == 0 || data[0].acceso == undefined)) {
                 $("#Asistentes")[0].innerHTML = "";
                 $("#responsadd")[0].innerHTML = '<option value="" disabled selected>Seleccione Responsable</option>';
                 var myAttend = [];
                 $.each( data, function( key, val ) {
                    myAttend.push(
                    '<div class="col s12 m6">' +
                    '  <label for="asi_' + val.id + '">' +
                    '    <input id="asi_' + val.id + '" type="checkbox" />' +
                    '    <span title="(' + val.nombreser + ')">' +
                    val.nombres + ' ' + val.apellidos + '</span>' +
                    '  </label>' +
                    '</div>');

                    //De una vez poblar Responsables
                    $('#responsadd').append($('<option>', {
                         value: val.usuario,
                         text: val.apellidos + " " + val.nombres
                     }));

                 });
                 $("#Asistentes")[0].innerHTML = myAttend.join("");
                 $('#responsadd').formSelect();
          }else {
            jimte.alertMe(l("%denied", data[0].acceso) + " " +
                          l("%userNotFound", data[0].motivo), l("%iniciarSesion", "No se pudo Actualizar"));

          }
        },
        error: function(xhr, status, error) {
            //alert(xhr.responseText + "\nCon el error:\n" + error);
            console.log(xhr.responseText + "\nCon el error:\n" + error);
        }
      })
    }

    getEtiquetas(){
      $.ajax({
        url: this.serverPath + 'index.php/tags' +
              "?id=" + jimte.currentUser.id +
              "&tiporol=" + jimte.currentUser.tiporol +
              "&rol=" + jimte.currentUser.rol +
              "&grupo=" + jimte.currentUser.grupo +
              "&table=" + this.table +
              "&token=" + jimte.token +
              "&sqlCode=" + 'tags_act',
        dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        type: 'GET',
        success: function(data){
          if ((typeof data !== undefined ) &&
               (data.length == 0 || data[0].acceso == undefined)) {

                 jimte.currentTags ={};
                 var myTagsData = {};
                 $.each( data, function( key, val ) {
                    myTagsData[val.etiqueta] = null;
                    //console.log(key + "/" + myTagsData[val.etiqueta]);
                 });
                 jimte.currentTags = myTagsData;
                 $('.chips-autocomplete').chips({
                    data: [],
                    autocompleteOptions: {
                        data: myTagsData
                    },
                    placeholder: 'Ingrese Etiquetas',
                    secondaryPlaceholder: '+Etiqueta',
                    limit: 10,
                    minLength: 1
                  });

          }else {
            jimte.alertMe(l("%denied", data[0].acceso) + " " +
                          l("%userNotFound", data[0].motivo), l("%iniciarSesion", "No se pudo Actualizar"));

          }
        },
        error: function(xhr, status, error) {
            //alert(xhr.responseText + "\nCon el error:\n" + error);
            console.log(xhr.responseText + "\nCon el error:\n" + error);
        }
      })
    }

    check_actas(){
      //if(this.Token == "localhost"){
      //  return;
      //}
      this.getEstadosActa();
      this.getActasProgreso();
      this.getTiposActa();
      this.getLugares();
      this.getEtiquetas();
      this.getAsistentes();
      $("#loader").hide();
      return;
    }


    check_tables() {
        var self = $(this);
        var url = this.configPath + this.tables;
        //console.log("check_tables:" + url);
        //$('#configurarTablas').show();
        //$('#configurar').removeClass("oculto");

        //call header
        $.ajax({
          url: url,
          dataType: "json",
          cache: false,
          processData: false,
          contentType: false,
          context: this,
          type: 'GET',
          success: function(data) {

            //var tablas = [];
            $("#tabla").empty();
            var option = $('<option></option>').attr("value",
              ""
            ).text("Seleccione Tabla");
            $("#tabla").append(option);

            var tablas = [];
            $.each( data.tablas, function( key, val ) {
              /*var attribs = [];
              console.log(val);
              $.each( val, function( key2, val2 ) {
                console.log(key2 + ' = "' + val2 + '"');
                //attribs.push(key2 + ' = "' + val2 + '"');
              });
              /*tablas.push( "<meta " + attribs.join(" ") + ">" );
              */

              /*console.log(key + "/" + val.tiporol.inc  + "/" + jimte.currentUser.tiporol);
              console.log(key + "/" + val.rol.inc  + "/" + jimte.currentUser.rol);*/
              if( val.estado == "A"){
                //Las no activas no van en el select
                //y hay que verificar si está autorizado ese usuario por tipo rol y
                // rol a que le aparezca la tabla.
                var authorizaTabla = false;
                if( val.tiporol.inc  == "*" && val.tiporol.exc.indexOf(jimte.currentUser.tiporol) == -1 ) {
                  if( val.rol.inc == "*" && val.rol.exc.indexOf(jimte.currentUser.rol) == -1 ) {
                    authorizaTabla = true;
                  }
                }

                if(	!authorizaTabla ) {
                  if( val.tiporol.inc.indexOf(jimte.currentUser.tiporol) > -1  ) {
                    if( val.rol.inc.indexOf(jimte.currentUser.rol) > -1 ||
                        (val.rol.inc == "*" && val.rol.exc.indexOf(jimte.currentUser.rol) == -1) ) {
                      authorizaTabla = true;
                    }
                  }
                }

                if(authorizaTabla){
                  var option = $('<option></option>').attr("value",
                    val.api
                  ).text(val.descripcion ).attr("data-icono",
                                      val.icon);
                  $("#tabla").append(option);

                }
              }

            });

            $('#tabla').formSelect();
          },
          error: function(xhr, status, error) {
              //alert('buildHeader failed: ' + xhr.responseText + "\nWith error:\n" + error);
              console.log('check_tablas failed: ' + xhr.responseText + "\nWith error:\n" + error);
          },
          error2: function(){
            //alert("buildHeader: error with server communication");
            console.log("check_tablas: error with server communication");
          }
        })
    }

// - //
    buildInnerPage2(key) {
        var self = $(this);
        var url = this.includesPath + key + ".html";
        $.ajax({
          url: url,
          dataType: "html",
          cache: false,
          processData: false,
          contentType: false,
          context: this,
          type: 'GET',
          success: function(data) {
            $("main")[0].innerHTML = data;

          },
          error: function(xhr, status, error) {
              //alert('buildInnerPage failed: ' + xhr.responseText + "\nWith error:\n" + error);
              console.log('buildInnerPage failed: ' + xhr.responseText + "\nWith error:\n" + error);
          },
          error2: function(){
            //alert("buildInnerPage: error with server communication");
            console.log("buildInnerPage: error with server communication");
          }
        })
    }

    buildButtons(prev, art, next, botonera) {
        var brs = false;
        var buttons = "";

        if(prev || next ){
            buttons = "<div class='row'>" +
                      "<div class='col-xs-6 col-sm-6 col-md-6 col-lg-6' " +
                      "style='text-align:center'>";

            if(prev != 14 && prev != -1 && prev != '0-1' && prev != ""){
                  //buttons += "<a href='?art=" + prev + "'>Anterior</a>";
                  buttons += "<a onclick='javscript:jimte.buildCentral(" + prev + ");'>Anterior</a>";
                  brs = true;
            }
            buttons += "</div>";

            //buttons += "<a class='reload' onclick='javscript:jimte.buildCentral(" + ((art*1)+1) + ");'>Recargar</a>";
          //  buttons += "<a class='reload' onclick='javscript:jimte.buildCentral(" + next + ");'>Recargar</a>";

            buttons += "<div class='col-xs-6 col-sm-6 col-md-6 col-lg-6' " +
                       "style='text-align:center' >";
            if(next !=15 && next != 0){
                  //buttons += "<a href='?art=" + next + "'>Siguiente</a>";
                  buttons += "<a onclick='javscript:jimte.buildCentral(" + next + ");'>Siguiente</a>";
                  brs = true;
            }
            buttons += "</div>";
            buttons +="</div>";
            if(brs) {
                buttons += "<br>";
            }
        }
/*
        let botoneraHtml = "<div class='row'>" +
                  "      <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12' style='background-color:#333;'>" +
                  "        <a target='orden' href='?art=001' class='btn-lg'>Nosotros</a>" +
                  "        <a target='orden' href='?art=007' class='btn-lg'>Presencia</a>" +
                  "        <a target='orden' href='?art=15&next=16' class='btn-lg'>Proceso</a>" +
                  "        <a target='orden' href='?art=gallery' class='btn-lg'>Galería</a>" +
                  "        <a target='orden' href='?art=news' class='btn-lg'>Noticias</a>" +
                  "       <a target='orden' href='?art=contacto' class='btn-lg'>Contacto</a>" +
                  "     </div>" +
                  " </div>";

        if(botonera){
          buttons += botoneraHtml;
        } else {
          buttons = botoneraHtml + buttons;
        }*/
      return buttons;
    }

    buildCentral(paramArt) {

        //$("#centralSpot")[0].innerHTML = "<img id='loader' class='image-responsive' style='display:none' src='images/loading_article.png'>";
        //$("#loader").animate({ 'opacity':0.5 }, 'slow');
          //$("#imageCar")[0].innerHTML = this.carousel[this.carouselIndex - 1];
          //$("#loader").animate({'opacity':0}, 'slow');
          location.href="#topNavigation";

        var self = $(this);
        //let url = this.articlesPath + this.currentLang + '_' + this.central;
        var url = this.articlesPath + this.central;
        var buttonsTop = "";
        var buttonsBottom = "";
        var prev = "";
        var next = "";
        //console.log("buildCentral paramArt:" + paramArt);
        if(paramArt != undefined){
          this.params.art = ("000" + "" + paramArt).substr(("000" + "" + paramArt).length -3);
          this.currentArt = this.params.art.substring(0,3);
        }
        //console.log("buildCentral this.params.art:" + this.params.art);

        if(this.params.art != undefined){
            var nomart = "article" + this.params.art.substring(0,3) + ".json";
            url = this.articlesPath + nomart;

            prev = (this.currentArt * 1 ) - 1;
            //console.log("buttons:" + prev + " / " + this.currentArt + " / " + next);

          	prev = ("000" + "" + prev).substr(("000" + "" + prev).length -3);
            //console.log("buttons:" + prev + " / " + this.currentArt + " / " + next);

          	//$prev = ($art == 14 ? '' : $prev);
          	next = (this.currentArt * 1 ) + 1;
          	next = (""+next).substring('000' + next, -3);

            //console.log("buttons:" + prev + " / " + this.currentArt + " / " + next);
            //console.log("url:"+url);
          //console.log(params);
        } else {
            prev = "0-1";
            this.currentArt = "000";
            next = "001";

            //console.log("buttons:" + prev + " / " + this.currentArt + " / " + next);

        }
        buttonsTop = this.buildButtons(prev, this.currentArt, next, false);
        buttonsBottom = this.buildButtons(prev, this.currentArt, next, true);

        //console.log(url);

        //ajax call
        $.ajax({
          url: url,
          dataType: "json",
          cache: false,
          processData: false,
          contentType: false,
          context: this,
          type: 'GET',
          success: function(data) {
            //$("#centralSpot")[0].innerHTML = data;
              var url2 = this.layoutPath + data.layout;
              $.ajax({
                url: url2,
                dataType: "html",
                cache: false,
                processData: false,
                contentType: false,
                context: this,
                type: 'GET',
                success: function(data2) {
                  //art_permalink
                  data2 = data2.replace(/art_permalink/g,
                          window.location.href );
                  //Si trae video
                  if(data.video != undefined ){
                    data2 = data2.replace(/art_src_video/g,
                            data.video );
                  }
                  if(data.video == "" ){
                    data2 = data2.replace(/art_src_video/g,
                            data.video );
                  }

                  var qimgArtPath = this.imagesArticlePath;
                  var qcurArt = this.currentArt;

                  //procesaImages
                  data2 = data2.replace(/SARTWEB/g,
                          this.imagesArticlePath + "SARTWEB_" + this.currentArt);

                  data2 = data2.replace(/<!--expand:art_deta1-->/g,
                          "<div class='row' id='art_deta1'></div>");

                  data2 = data2.replace(/<!--expand:art_deta2-->/g,
                          "<div class='row' id='art_deta2'></div>");

                  data2 = data2.replace(/<!--expand:art_deta3-->/g,
                          "<div class='row' id='art_deta3'></div>");

                  data2 = data2.replace(/<!--expand:art_deta4-->/g,
                          "<div class='row' id='art_deta4'></div>");

                  data2 = data2.replace(/<!--expand:art_deta5-->/g,
                          "<div class='row' id='art_deta5'></div>");

                  data2 = data2.replace(/<!--expand:art_deta6-->/g,
                          "<div class='row' id='art_deta6'></div>");

                  data2 = data2.replace(/<!--expand:art_deta7-->/g,
                          "<div class='row' id='art_deta7'></div>");

                  //
                  $("#centralSpot")[0].innerHTML = buttonsTop +
                                                   data2 +
                                                   buttonsBottom;

                  $.each( data, function( key, val ) {
                      //console.log(key + " / " + val);

                      if($("#art_" + key)[0] != undefined){

                        if(key == "video"){
                          if(val != ""){
                            val = "<iframe src='" + val + "' frameborder='0' " +
                                  "allowfullscreen></iframe>";

                          } else {
                            $("#art_" + key).css("display","none");
                          }
                        }

                        $("#art_" + key)[0].innerHTML = val;
                      }

                      //layoutgroups, extraDetails
                      if(data.layout == 'layoutgroups.php'){
                        if(key.substring(0, 4) == 'deta'){
                          if($("#extraDetails")[0] != undefined){

                            if(key.substring(6, 10) == 'imgs'){

                            } else {
                              $("#extraDetails" )[0].innerHTML += val;
                            }

                            var imagenes = "";
                            imagenes +=
                              "<img onerror='this.style.display=\"none\";' src='" +
                              qimgArtPath + "SARTWEB_" + qcurArt + "_" + key + "_1.jpg'/ style='float:left;' ><br>" +
                              "<img onerror='this.style.display=\"none\";' src='" +
                              qimgArtPath + "SARTWEB_" + qcurArt + "_" + key + "_2.jpg'/ style='float:left;' ><br>" +
                              "<img onerror='this.style.display=\"none\";' src='" +
                              qimgArtPath + "SARTWEB_" + qcurArt + "_" + key + "_3.jpg'/ style='float:left;' ><br>" +
                              "<img onerror='this.style.display=\"none\";' src='" +
                              qimgArtPath+ "SARTWEB_" + qcurArt+ "_" + key + "_4.jpg'/ style='float:left;' ><br>" +
                              "<img onerror='this.style.display=\"none\";' src='" +
                              qimgArtPath + "SARTWEB_" + qcurArt+ "_" + key + "_5.jpg'/ style='float:left;' ><br>";

                              $("#extraDetails" )[0].innerHTML += imagenes;
                          }
                        }
                      }

                  });
                },
                error: function(xhr, status, error) {
                    //alert('buildCentralInside failed: ' + xhr.responseText + "\nWith error:\n" + error);
                    console.log('buildCentralInside failed: ' + xhr.responseText + "\nWith error:\n" + error);
                },
                error2: function(){
                  //alert("buildCentralInside: error with server communication");
                  console.log("buildCentralInside: error with server communication");
                }
              })
            //$("#centralSpot")[0].innerHTML = data;

            /*$( "<ul/>", {
              "class": "my-new-list",
              html: items.join( "" )
            }).appendTo( "body" );*/


          },
          error: function(xhr, status, error) {
          //alert('buildCentral failed: ' + "\nWith error:\n" + error+ xhr.responseText );
          console.log('buildCentral failed: ' + "\nWith error:\n" + error+ xhr.responseText );
          //window.location.href = ".";
          },
          error2: function(){
          //alert("buildCentral: error with server communication");
          console.log("buildCentral: error with server communication");
          //window.location.href = ".";

          }
        })
    }

    parseQueryString( queryString ) {
        var params = {}, queries, temp, i, l;
        queryString = queryString.substring(1); //removing the ? character
        // Split into key/value pairs
        queries = queryString.split("&");
        // Convert the array of strings into an object
        for ( i = 0, l = queries.length; i < l; i++ ) {
            temp = queries[i].split('=');
            params[temp[0]] = temp[1];

            //fix for currentArt 000 format
            if(temp[0] == 'art'){
                temp[1] = "000" + temp[1];
                temp[1] = temp[1].substr(temp[1].length - 3);

                params[temp[0]] = temp[1];;
                //console.log(temp[0] + " / " + params[temp[0]]);
            }
        }
        return params;
    };

    alertMe(message, title) {
        if (typeof title === 'undefined'){
          title = "Atención:";
        }
        $('#standardAlert .modal-content h4').html(title);
        $('#standardAlert .modal-content p').html(message);
        $('#standardAlert').modal('open');
    }

    changeTipoActa(obj) {
      //console.log("changeActa " + obj.value);

      //$("#creaacta").hide();

      //$("#loader").show();

      //load planilla
      //$("#repomesa").show();

      //Aqui buscar las actas según el tipo elegido
      //this.loadActa(obj.value);
    }

    changeActa(obj) {
      //console.log("changeActa " + obj.value);
      if(obj.value == "add"){
        //Limpiar el acta pues se está adicionando
        this.cleanActa();

      } else {
        //this.getEtiquetas();
        this.getActaId(obj.value);

      }

      $("#creaacta").show();

      //$("#loader").show();
      //load planilla
      //$("#repomesa").show();

      //Aqui buscar las actas según el tipo elegido
      //this.loadActa(obj.value);
    }

    explodeCandi(candi, mode, prefix) {
      var candiAR = candi.split("-");
      candiAR[0] = candiAR[0] * 1;
      candiAR[1] = candiAR[1] * 1;
      var candiTHs = new Array(candiAR[1] - candiAR[0] + 1);
      var exploded = "";
      for(var ime = 0;ime < candiTHs.length; ime++) {
          candiTHs[ime] = candiAR[0] + ime;
          if(mode == "th"){
            exploded = "" + candiTHs[ime];

            candiTHs[ime] = "<th>" +
                          exploded +
                          "</th>";
          }
          if(mode == "td"){
            exploded = ("000" + "" + candiTHs[ime]).substr(("000" + "" + candiTHs[ime]).length -3);

            candiTHs[ime] = "<td>" +
                            '<input class="center-align validate" type="number" ' +
                            'min="0" max="600" step="1" id="' + prefix + '_' + exploded + '" ' +
                            'name="' + prefix + '_' + exploded + '">' +
                            "</td>";
          }
      }
      //console.log(candiTHs);
      return candiTHs.join("");
    }

    loadActa(planilla) {
        var self = $(this);
        var url = this.configPath + planilla + ".json";
        /*console.log(url);*/

        //call header
        $.ajax({
          url: url,
          dataType: "json",
          cache: false,
          processData: false,
          contentType: false,
          context: this,
          type: 'GET',
          success: function(data) {
            /*if (data.msg=="OK") {
              this.poblarCalendario(data.sections)
            }else {
              alert(data.msg)
              window.location.href = 'index.html';
            }*/
            var myPlan = [];
            myPlan.push("<div class='col s12'>");

            $.each( data, function( key, val ) {

              var attribs = "";
              var candi = "";
              var candi1 = "";
              var candi2 = "";
              var candi3 = "";
              var candi4 = "";
              var candiTh = "";
              var candiTh1 = "";
              var candiTh2 = "";
              var candiTh3 = "";
              var candiTh4 = "";
              if(val.attribs != undefined){
                $.each( val.attribs, function( key1, val1 ) {
                  if(!key1.startsWith("candi")){
                    attribs += ' ' + key1 + '= "' + val1 + '" ';
                  } else {
                    if(key1 == 'candi'){
                      candi = val1;
                      candiTh = jimte.explodeCandi(val1, "th", "");
                    }
                    if(key1 == 'candi1'){
                      candi1 = val1;
                      candiTh1 = jimte.explodeCandi(val1, "th", "");
                    }
                    if(key1 == 'candi2'){
                      candi2 = val1;
                      candiTh2 = jimte.explodeCandi(val1, "th", "");
                    }
                    if(key1 == 'candi3'){
                      candi3 = val1;
                      candiTh3 = jimte.explodeCandi(val1, "th", "");
                    }
                    if(key1 == 'candi4'){
                      candi4 = val1;
                      candiTh4 = jimte.explodeCandi(val1, "th", "");
                    }
                  }
                });
              }
              myPlan.push("<table id='" + key + "' " + attribs + ">");

              var head = "";
              if(val.head != undefined){
                $.each( val.head, function( key1, val1 ) {
                  if(val1.th != undefined){
                    if(val1.colspan != undefined){
                      head += '<th colspan="' + val1.colspan + '">' +
                              val1.th +
                              '</th>';
                    } else {
                      head += '<th class="center-align">' + val1.th + '</th>';
                    }
                  }
                  if(val1.th_candi != undefined){
                    head += candiTh;
                  }
                  if(val1.td != undefined){
                    head += '<td>' + val1.td + '</td>';
                  }
                  if(val1.td_tr != undefined){
                    head += '<td>' +
                    '<input class="center-align validate" type="number" ' +
                    'min="0" max="600" step="1" id="' + val1.td_tr + '" ' +
                    'name="' + val1.td_tr + '"></td></tr>';
                  }
                  if(val1.tr_th != undefined){
                    if(val1.colspan != undefined){
                      head += '<tr><th colspan="' + val1.colspan + '">' +
                              val1.tr_th +
                              '</th></tr>';

                    } else {
                      head += '<tr><th>' + val1.th + '</th></tr>';
                    }
                  }
                });
                if(head.startsWith("<tr>")){
                  myPlan.push(head);
                } else {
                  myPlan.push("<tr>" + head + "</tr>");
                }
              }

              var body = "";
              if(val.body != undefined){
                $.each( val.body, function( key1, val1 ) {
                  if(val1.nom != undefined){
                    var rowspan = "";
                    if(val1.rowspan != undefined){
                      rowspan = 'rowspan="' + val1.rowspan + '" ' ;
                    }
                    body += '<tr><td ' + rowspan + '>' + val1.nom + '</td>' +
                            '<td ' + rowspan + '>' +
                            '<input class="center-align validate" type="number" ' +
                            'min="0" max="600" step="1" id="' + key1 + '_000" ' +
                            'name="' + key1 + '_000"></td>';
                    if(rowspan != ""){
                      body += "<td>";
                      if(candi1 != ""){
                          body += '<table><tr>' + candiTh1 + '</tr><tr>' +
                                  jimte.explodeCandi(candi1, "td", key1) +
                                  '</tr></table>'
                      }
                      if(candi2 != ""){
                          body += '<table><tr>' + candiTh1 + '</tr><tr>' +
                                  jimte.explodeCandi(candi2, "td", key1) +
                                  '</tr></table>'
                      }
                      if(candi3 != ""){
                          body += '<table><tr>' + candiTh1 + '</tr><tr>' +
                                  jimte.explodeCandi(candi3, "td", key1) +
                                  '</tr></table>'
                      }
                      body = "</td></tr>";
                    } else {
                      if(candi != ""){
                         body += jimte.explodeCandi(candi, "td", key1) + "</tr>";
                      }
                    }

                  }
                });
                if(body.startsWith("<tr>")){
                  myPlan.push(body);
                } else {
                  myPlan.push("<tr>" + body + "</tr>");
                }

              }

              //console.log(key + ' = "' + val + '"');
              /*$.each( val, function( key2, val2 ) {
                //console.log(key2 + ' = "' + val2 + '"');
                var head = [];
                $.each( val2, function( key3, val3 ) {
                  //console.log(key3 + ' = "' + val3 + '"');
                    $.each( val3, function( key4, val4 ) {
                      //console.log(key4 + ' = "' + val4 + '"');
                    });

                });

                //attribs.push(key2 + ' = "' + val2 + '"');
              });*/
              //metas.push( "<meta " + attribs.join(" ") + ">" );

              myPlan.push("</table>");

            });
            myPlan.push("</div>");

            //console.log(metas.join(""));
            $("#creaacta_content")[0].innerHTML = myPlan.join("") ;

            $("#creaacta").show();
            $("#loader").hide();
            $("#creaacta_content").show();


/*
            var links = [];
            $.each( data.links, function( key, val ) {
              links.push( "<link rel='" + val.rel + "' " +
                          "href='" + val.href + "'>");
            });

            var scripts = [];
            $.each( data.scripts, function( key, val ) {
              scripts.push( "<script src='" + val.src + "'></script>" );
            });
            $("head")[0].innerHTML = metas.join("") +
                                    links.join("") +
                                    scripts.join("");

            document.title = data.title;
            $("#headerImg_all").attr("src", this.imagesPath + data.img_all);
            $("#headerImg_xs").attr("src", this.imagesPath + data.img_xs);
            $("html").attr("lang", data.defaultLang);
            $("#org")[0].innerHTML = data.org;
            $("#org_URL").attr("href", data.URL);
            $("#org_URL")[0].innerHTML = data.URL;
            this.thisURL = data.thisURL;
            this.currentLang = data.defaultLang;
            this.currentMode = data.defaultMode;
            this.displayMode();
*/
            /*$( "<ul/>", {
              "class": "my-new-list",
              html: items.join( "" )
            }).appendTo( "body" );*/


          },
          error: function(xhr, status, error) {
              //alert('buildHeader failed: ' + xhr.responseText + "\nWith error:\n" + error);
              console.log('buildHeader failed: ' + xhr.responseText + "\nWith error:\n" + error);
          },
          error2: function(){
            //alert("buildHeader: error with server communication");
            console.log("buildHeader: error with server communication");
          }
        })
    }

    validaActa(tipo) {
        var title = "No se pudo Guardr el Acta, falta:";
        var message = "";
        var guardar = true;

        var tipo_de_acta = $("#tipo_de_acta").val();
        var acta_a_elaborar = $("#acta_a_elaborar").val();
        var temaacta = $("#temaacta").val();
        var lugar_reunion = $("#lugar_reunion").val();
        var fecacta = $("#fecacta").val();
        var horacta = $("#horacta").val();
        var objetivos = $("#objetivos").val();
        var conclusiones = $("#conclusiones").val();
        //var Asistentes (validar checkboxes) = $("#conclusiones").val();
        if( tipo_de_acta == null || tipo_de_acta == ""){
          message += "- El tipo de acta.<br>";
          guardar = false;
        }
        if( acta_a_elaborar == null || acta_a_elaborar == ""){
          message += "- El número de acta o elegir que sea Nueva.<br>";
          guardar = false;
        }
        if( temaacta == null || temaacta == ""){
          message += "- El Tema principal del acta.<br>";
          guardar = false;
        }
        if( lugar_reunion == null || lugar_reunion == ""){
          message += "- El Lugar de la Reunión relacionada con el acta.<br>";
          guardar = false;
        }
        if( fecacta == null || fecacta == ""){
          message += "- La Fecha del acta.<br>";
          guardar = false;
        }
        if( horacta == null || horacta == ""){
          message += "- La Hora del acta.<br>";
          guardar = false;
        }
        if (guardar){
          jimte_table.sendActa(tipo, acta_a_elaborar);
        } else {
           $('#standardAlert .modal-content h4').html(title);
           $('#standardAlert .modal-content p').html(message);
           $('#standardAlert').modal('open');

        }

    }

    sendMail() {

          var form_data = new FormData();
          form_data.append("id", jimte.currentUser.id );
          form_data.append("tiporol", jimte.currentUser.tiporol );
          form_data.append("rol", jimte.currentUser.rol );
          form_data.append("grupo", jimte.currentUser.grupo );
          form_data.append("table", this.table );
          form_data.append("token", jimte.token );

          form_data.append("mail_to", $("#mail_to").val() );
          form_data.append("mail_sb", $("#mail_sb").val() );
          form_data.append("mail_tx", $("#mail_tx").val() );

          $.ajax({
            url: jimte.serverPath + 'index.php/mails',
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            data: form_data,
            type: 'POST',
            success: function(data){
              //console.log( "sendAdd success - data: " + data );

              //&& data.length > 0
              if ((typeof data !== undefined ) &&
                   (data.length == 0 || data[0].acceso == undefined)) {
                     M.toast(
                               {html:'Se Envió email!',
                               displayLenght: 3000,
                               classes: 'rounded'}
                             );
              } else {
                jimte.alertMe(l("%denied", data[0].acceso) + " " +
                              l("%userNotFound", data[0].motivo), l("%iniciarSesion", "No se pudo Actualizar"));

              }

            },
            error: function(xhr, status, error) {
                //alert(xhr.responseText + "\nCon el error:\n" + error);
                if(xhr.responseText.startsWith("Error: SQLSTATE[HY000]")){
                  jimte.alertMe("Al parecer No hay conexión con la base de datos, Por favor Reintente más tarde. \nSi el problema persiste por favor repórtelo al Administrador.", "Adicionando Registro");
                }
                if(xhr.responseText.startsWith("Error: SQLSTATE[23000]")){
                  jimte.alertMe("Ya existe un registro con esa llave en la base de datos, Por favor verifique.", "Adicionando Registro");
                }
                jimte_table.notWorking("addTable");
                console.log(xhr.responseText + "\nCon el error:\n" + error);
            }
          })

    }

    changeLanguage(obj) {
        // this.currentLang = obj.value;
         // t(this.currentLang, "body");
         window.location.href = "./?lang=" + obj.value;
         //alert(status);
       //if(status=="1")
      //   $("#icon_class, #background_class").hide();// hide multiple sections
    }


}

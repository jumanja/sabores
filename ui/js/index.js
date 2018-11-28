$(document).ready(function() {

    $('.sidenav').sidenav();
    $('.modal').modal();

    //$('select').material_select();
    $('select').formSelect();

   $('.tabs').tabs();

   //$('.datepicker').pickadate({
   $('.timepicker').timepicker({
     defaultTime: "now"
   });

   $('.datepicker').datepicker({
     i18n:{
       months: [
    'Enero',
    'Febrero',
    'Marzo',
    'Abril',
    'Mayo',
    'Junio',
    'Julio',
    'Agosto',
    'Septiembre',
    'Octubre',
    'Noviembre',
    'Diciembre'
      ],
      monthsShort:
      [
        'Ene',
        'Feb',
        'Mar',
        'Abr',
        'May',
        'Jun',
        'Jul',
        'Ago',
        'Sep',
        'Oct',
        'Nov',
        'Dec'
      ] ,
      weekdays:[
        'Domingo',
        'Lunes',
        'Martes',
        'Miércoles',
        'Jueves',
        'Viernes',
        'Sábado'
      ],
      weekdaysShort:
      [
        'Dom',
        'Lun',
        'Mar',
        'Mié',
        'Jue',
        'Vie',
        'Sáb'
      ],
      weekdaysAbbrev:	['D','L','M','M','J','V','S']
    },
       selectMonths: true, // Creates a dropdown to control month
       selectYears: 15, // Creates a dropdown of 15 years to control year,
       defaultDate: new Date(),
       setDefaultDate: true,
       today: 'Hoy',
       clear: 'Limpiar',
       close: 'Ok',
       format: 'yyyy/mm/dd',
       closeOnSelect: false // Close upon selecting a date,
     });

     $('.button-collapse').sidenav({
       closeOnClick: true
     });

     $('.dropdown-button').dropdown({
      inDuration: 300,
      outDuration: 225,
      constrainWidth: false, // Does not change width of dropdown to that of the activator
      hover: true, // Activate on hover
      gutter: 0, // Spacing from edge
      belowOrigin: false, // Displays dropdown below the button
      alignment: 'left', // Displays dropdown with edge aligned to the left of button
      stopPropagation: false // Stops event propagation
    });
/*
    $('.chips-autocomplete').chips({
      autocompleteOptions: {
        data: {
          'Apple': null,
          'Microsoft': null,
          'Google': null
        },
        limit: Infinity,
        minLength: 1
      }
    });
*/

 });

 //localizations
 if (location.hash) {
 	String.locale = location.hash.substr(1);
 }

// var localize = function (string, fallback) {
 var l = function (string, fallback) {
 	var localized = string.toLocaleString();
 	if (localized !== string) {
 		return localized;
 	} else {
 		return fallback;
 	}
 };

 var t = function (locale, parent) {
   String.locale = locale;
    $(parent + ' [translate="yes"]').each(function() {
        // `this` is the element
        /*
        NOT WORKING in SAFARI for MAC
        let txt = l("%" + this.id, this.innerText);
        */
        var txt = l("%" + this.id, this.innerText);
        //console.log(this.id + " / " + txt);
        // )
        this.innerText = txt;

    });
   document.documentElement.lang = String.locale || document.documentElement.lang;
   //console.log("translation of parent: "+ parent + " to " + String.locale + " completed!")
 }

//Load jimte library
var jimte = new JimteMan();
var jimte_table = new JimteTab();

//Now attempt the localization

                 /*
 var info = document.getElementById("info").firstChild,
 title = document.getElementById("title").firstChild;

 info.nodeValue = l("%info", info.nodeValue);
 document.title = title.nodeValue = l("%title", title.nodeValue);
 document.documentElement.dir = l("%locale.dir", document.documentElement.dir);

 document.documentElement.lang = String.locale || document.documentElement.lang;

console.log("locali done");
*/



$(function(){

});

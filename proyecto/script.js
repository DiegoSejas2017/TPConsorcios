// Code goes here

$(document).ready(function(){
  var table = $('#example').DataTable({"language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
        }});
  
  $('#btn-export').on('click', function(){
      $('<table>').append(table.$('tr').clone()).table2excel({
          exclude: ".excludeThisClass",
          name: "Worksheet Name",
          filename: "Estadisticas" //do not include extension

      });
  });      
})

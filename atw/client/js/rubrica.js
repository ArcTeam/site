postData("utenti.php", {dati:{trigger:'getRubrica'}}, function(data){
  console.log(data);
  $.each(data, function(i,v){
    switch (v.id_classe) {
      case 1: tdClass = 'table-primary'; break;
      default: tdClass = '';
    }
    let tr = $("<tr/>").appendTo("table.table>tbody");
    $("<td/>",{text:v.classe, class:tdClass}).appendTo(tr)
    $("<td/>",{text:v.nome, class:tdClass}).appendTo(tr)
    let mailTd = $("<td/>",{class:tdClass}).appendTo(tr)
    $("<a/>",{href:"mailto:"+v.email, text:v.email, title:'scrivi mail'}).appendTo(mailTd)
    $("<td/>",{text:v.mobile}).appendTo(tr)
    $("<td/>",{text:v.telefono}).appendTo(tr)
    $("<td/>",{text:v.indirizzo}).appendTo(tr)
    $("<td/>",{text:v.cod_fisc}).appendTo(tr)
    $("<td/>",{text:v.piva}).appendTo(tr)
    let webTd = $("<td/>").appendTo(tr)
    $("<a/>",{href:v.web, text:v.web, title:'apri risorsa in una nuova scheda', target: '_blank'}).appendTo(webTd)
    $("<td/>",{text:v.note}).appendTo(tr)
  });
  $(".table").footable({
    "paging": {
      "enabled": true,
      "size": 20
    },
    "filtering": {
      "enabled": true,
      "position": "center",
      "placeholder": "cerca in tutte le colonne"
    }
  });
})

FooTable.MyFiltering = FooTable.Filtering.extend({
  construct: function(instance){
    this._super(instance);
    this.statuses = ["altro","azienda", "cooperativa", "ditta", "ente di ricerca", "ente pubblico", "ente religioso", "funzionario", "libero professionista", "soprintendenza", "università"];
    this.def = 'Tutti i tipi';
    this.$status = null;
  },
  $create: function(){
    this._super();
    var self = this,
      $form_grp = $('<div/>', {'class': 'form-group'})
        .append($('<label/>', {'class': 'sr-only', text: 'Status'}))
        .prependTo(self.$form);

    self.$status = $('<select/>', { 'class': 'form-control' })
      .on('change', {self: self}, self._onStatusDropdownChanged)
      .append($('<option/>', {text: self.def}))
      .appendTo($form_grp);
    console.log(self.statuses);
    $.each(self.statuses, function(i, status){
      self.$status.append($('<option/>').text(status));
    });
  },
  _onStatusDropdownChanged: function(e){
    var self = e.data.self,
      selected = $(this).val();
    if (selected !== self.def){
      self.addFilter('status', selected, ['status']);
    } else {
      self.removeFilter('status');
    }
    self.filter();
  },
  draw: function(){
    this._super();
    var status = this.find('status');
    if (status instanceof FooTable.Filter){
      this.$status.val(status.query.val());
    } else {
      this.$status.val(this.def);
    }
  }
});
FooTable.components.register('filtering', FooTable.MyFiltering);

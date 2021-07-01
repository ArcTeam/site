getList({userObj});
$(".backBtn").on('click', function() { $.redirectPost("workPage.php", {id:postId});});
$('[name=submit]').on('click', function (e) {
  form = $("#formOre");
  isvalidate = $(form)[0].checkValidity()
  if (isvalidate) {
    e.preventDefault()
    dati={};
    dati.scavo = $("[name=scavo]").val();
    dati.ore = $("[name=ore]").val();
    dati.data = $("[name=data]").val();
    dati.operatore = $("[name=compilatore]").val();
    dati.trigger="addOre";
    postData("lavoro.php", {dati}, function(data){
      if (data === true) {
        $(".toast").removeClass('[class^="bg-"]').addClass('bg-success');
        $(".toast>.toast-body").text('ore inserite correttamente');
        $(".toast").toast({delay:3000});
        $(".toast").toast('show');
        setTimeout(function(){
          $.redirectPost('workPage.php', {id: dati.scavo});
        },3000);
      }else {
        $(".toast").removeClass('[class^="bg-"]').addClass('bg-danger');
        $("#headerTxt").html('Errore nella query');
        $(".toast>.toast-body").html(data);
        $(".toast").toast({delay:3000});
        $(".toast").toast('show');
      }
    })
  }
})

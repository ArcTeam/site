var p = document.getElementById("newPwd");
var cp = document.getElementById("checkPwd");
function validatePassword(){
    if(p.value.length < 6){
        p.setCustomValidity("La password deve contenere almeno 6 caratteri!");
    }else{
        p.setCustomValidity('');
        if(p.value != cp.value) {
            cp.setCustomValidity("Attenzione, le password non coincidono!");
        } else {
            cp.setCustomValidity('');
        }
    }
}
p.onchange = validatePassword;
cp.onkeyup = validatePassword;

function renderImage(file) {
    var reader = new FileReader();
    reader.onload = function(event) {
        preview = event.target.result;
        $('#myImg').html("<img class='preview' src='" + preview + "' />");
        $("#uploadMsg").text("L'anteprima dell'immagine è puramente indicativa, possibili distorsioni verranno eliminate al salvataggio");
    }
    reader.readAsDataURL(file);
}

$(document).ready(function(){
    var i = $("input[name='sessionImg']").val();
    $("#myImg").css({"background-image":"url("+i+")"});
    $("button[name='triggerUpload']").on("click", function(){ $("input[name=updateImg]").click(); });
    $("input[name=updateImg]").on("change", function() {
        var file= this.files[0];
        if(file.size>=2*1024*1024) {
            $("#uploadMsg").text("Attenzione! La dimensione massima permessa per un'immagine è di 2MB mentre l'immagine che hai caricato è di "+formatBytes(file.size));
            $("#socialForm").get(0).reset();
            return;
        }
        if(!file.type.match('image/*')) {
            $("#uploadMsg").text("Attenzione! possono essere caricate solo immagini mentre tu stai cercando di caricare un file di tipo "+file.type);
            $("#socialForm").get(0).reset();
            return;
        }
        renderImage(file);
    });

    //tag
    $.each(tagpresarr, function(k,v) { tags.push(v); });
    console.log(tags);
    prefilled=tags;
    $(".tm-input").tagsManager({
        prefilled: prefilled,
        hiddenTagListName: 'tagList',
        hiddenTagListId: 'tagList',
        deleteTagsOnBackspace: false,
        AjaxPush: 'inc/addTag.php',
    })
    .autocomplete({source:dataList});

    $("button[name='newSocialAdd']").on("click", function(){
        var type = $("select[name='newSocialType']");
        var ico = type.find(':selected').data('ico')
        var url = $("input[name='newSocialUrl']");
        if(!type.val()){
            type.addClass('error');
            return false;
        }else{
            type.removeClass('error');
        }
        if(!url.val()){
            url.addClass('error');
             return false;
        }else{
            if(!isUrl(url.val())){
                url.addClass('error');
                $("#newSocialUrlMsg").html(" link non valido <i class='fa fa-question-circle' aria-hidden='true' title='Controlla che non ci siano errori di battitura, che il link sia completo e che sia presente un protocollo iniziale (es. http://, https://, ftp://); un esempio di link valido è https://www.arc-team.com'></i> ");
                 return false;
            }else{
                url.removeClass('error');
                $("#newSocialUrlMsg").text("");
            }
        }
        $("#newSocialListUl").append("<li class='newSocialVal' data-type='"+type.val()+"' data-url='"+url.val()+"'><i class='fa "+ico+"'></i> "+url.val()+" <i class='fa fa-times remove'></i><input type='hidden' name='tipo[]' value='"+type.val()+"'><input type='hidden' name='link[]' value='"+url.val()+"'></li>");
        $("select[name='newSocialType'] option:first").prop('selected',true);
        url.val('');
        $(".remove").on("click", function(){ $(this).parent().remove(); });


    });

    $('.delSocial').on("click", function(){
        $(this).next().show();
        $(".delSocialAnnulla").on("click", function(){ $(this).closest('span').hide(); });
        $(".delSocialConfirm").on("click", function(){
            var id = $(this).data('id');
            //$("#social"+id).remove();
            $.ajax({
                type: "POST",
                url: "inc/delSocial.php",
                data: {id:id},
                success: function(data){
                    if(data == 'ok'){  $("#social"+id).remove(); }else{ console.log('la riga social'+id+' non è stata eliminata: '+data); }
                    //console.log(data);
                }
            });
        });
    });
});

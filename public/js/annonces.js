$('#add-image').click(function(){
    //je récupère la valeur du compteur qui est normalement à 0
    const index = +$('#widgets-counter').val(); //si je ne rajoute pas un + ça retourne une chaine de caratère et donc le prochain index sera 01.Avec le plus ça devent un nbre
    
    //je récupere le prototype des entrées
    const template = $('#advert_images').data('prototype').replace(/__name__/g, index);
    //J'injecte ce code au sein de la div
    $('#advert_images').append(template);
    $("#widgets-counter").val(index + 1);
    handleDeleteButton();
});

function handleDeleteButton(){
    $('button[data-action="delete"]').click(function(){
        const target = this.dataset.target; //this:btn sur lequel clik, dataset=ts les attr data qq chose et target parceque que je veux acceder à l'attr target
    $(target).remove();
   
    });

}

function updateCounter(){
    const count = +$('#advert_images div.form-group').length;
        $('#widgets-counter').val(count);
}

updateCounter();
handleDeleteButton();

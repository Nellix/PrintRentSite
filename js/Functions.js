/**
 * Created by AnielloMalinconico on 03/06/16.
 */

function HomePageNavbar_Login()
{
    $('#Loginbtn').value().set("CIAO");
    $('#Loginbtn').show();

};


function loadImages(){
        $('#show1 img:gt(0)').hide();

        setInterval(function(){$('#show1 :first-child').fadeOut().next('img').fadeIn().end().appendTo('#show1');}, 5000);
    };



function loadImg() {
    setInterval(rotateImage, 5000);
    var images = new Array('img/stampante2.jpg', 'img/stampante3.jpg',"img/stampante1.jpg");
    var index = 1;

    function rotateImage() {
        $('.dumbCrossFade img').fadeOut(1000, function () {
            $(this).attr('src', images[index]);
            $(this).fadeIn('slow', function () {
                if (index == images.length - 1) {
                    index = 0;
                }
                else {
                    index++;
                }
            });
        });
    }
}


function ParseDate() {

    var datainizio = $('#datainizio').val();
    console.log(datainizio);


}

function addPrenotazione() {

    $('#divPrenotazione').show();
    $('#rimPrenotazione').hide();
    console.log("showed");


}

function RemovePrenotazione() {

    $('#rimPrenotazione').show();
    $('#divPrenotazione').hide();
    console.log("showed");


}


function OpenPopUp(stringa) {
    //----- OPEN

       $('#popupdiv').show();
     //   $('#ppopup').val("sda");
         $('#ppopup').show();

}


function ClosenPopUp(stringa) {
    //----- OPEN

    $('#popupdiv').fadeOut(350);
    $('#ppopup').val(stringa);

}

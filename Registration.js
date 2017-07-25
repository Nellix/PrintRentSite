/**
 * Created by AnielloMalinconico on 02/06/16.
 */
$(function () {
    $('.button-checkbox').each(function () {

        // Settings
        var $widget = $(this),
            $button = $widget.find('button'),
            $checkbox = $widget.find('input:checkbox'),
            color = $button.data('color'),
            settings = {
                on: {
                    icon: 'glyphicon glyphicon-check'
                },
                off: {
                    icon: 'glyphicon glyphicon-unchecked'
                }
            };

        // Event Handlers
        $button.on('click', function () {
            $checkbox.prop('checked', !$checkbox.is(':checked'));
            $checkbox.triggerHandler('change');
            updateDisplay();
        });
        $checkbox.on('change', function () {
            updateDisplay();
        });

        // Actions
        function updateDisplay() {
            var isChecked = $checkbox.is(':checked');

            // Set the button's state
            $button.data('state', (isChecked) ? "on" : "off");

            // Set the button's icon
            $button.find('.state-icon')
                .removeClass()
                .addClass('state-icon ' + settings[$button.data('state')].icon);

            // Update the button's color
            if (isChecked) {
                $button
                    .removeClass('btn-default')
                    .addClass('btn-' + color + ' active');
            }
            else {
                $button
                    .removeClass('btn-' + color + ' active')
                    .addClass('btn-default');
            }
        }

        // Initialization
        function init() {

            updateDisplay();

            // Inject the icon if applicable
            if ($button.find('.state-icon').length == 0) {
                $button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i>');
            }
        }
        init();
    });
});


function ClickRegistrazione() {

    var val = $('#email').val();
    var p1 = $('#password').val();
    var p2 = $('#password_confirmation').val();
    var nome = $('#nome').val();
    var cognome = $('#cognome').val();

    if(val.indexOf('@')==-1)
    {
        window.alert("Devi inserire una email valida");
    }else if(p1 != p2)
    {
        window.alert("Le due password non coincidono");
    }else if(nome.length==0 || cognome.length==0)
    {
        window.alert("Nome e Cognome non inseriti");
    }
    else
    {
      //  document.forms['formRegistration'].method="post";
      //  document.forms['formRegistration'].stop();
    }



}



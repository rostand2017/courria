$(function() {
    altair_tour.init();

    $('#restartTour').click(function() {
        altair_tour.init();
    })

});

altair_tour = {
    init: function() {

        // This tour guide is based on EnjoyHint plugin
        // for more info/documentation please check https://github.com/xbsoftware/enjoyhint

        // initialize instance
        var enjoyhint_instance = new EnjoyHint({});
        var enjoyhint_script_steps = [];
        var CheminComplet = window.location.href;
        var CheminRepertoire  = CheminComplet.substring(18);
       // alert(CheminRepertoire);
        if(CheminRepertoire == "compte"){ enjoyhint_script_steps.push(
            {
                "next #header_main": 'Hello, In this short tour guide I\'ll show you<br>' +
                'some features/components<br>' +
                'This is the main header.<br>' +
                'Click "Next" to proceed.'
            },
            {
                "next #full_screen_toggle" : "Here you can activate fullscreen.",
                shape : 'circle',
                radius: 30,
                showSkip: false
            },
            {
                "click #main_search_btn" : "Click this icon to show search form.",
                shape : 'circle',
                radius: 30,
                showSkip: false
            },
            {
                "next #header_main" : "This is the main search form.",
                showSkip: false
            },
            {
                "next #style_switcher_toggle" : "When you click on that icon '<i class='material-icons'>&#xE8B8;</i>'<br>" +
                "you will activate style switcher.<br>" +
                "There you can change <span class='md-color-red-500'>c</span><span class='md-color-light-blue-500'>o</span><span class='md-color-red-500'>l</span><span class='md-color-orange-500'>o</span><span class='md-color-pink-500'>r</span><span class='md-color-light-green-500'>s</span> and few other things.",
                shape : 'circle',
                radius: 30,
                showSkip: false
            },
            {
                "next #sidebar_main": "This is the primary sidebar.<br>" +
                "Click 'Next' to find out how to close this sidebar.<br>",
                showSkip: false
            },
            {
                "click #do_operation": "Click this icon to do an operation.",
                shape : 'circle',
                radius: 30,
                showSkip: false
            },
            {
                "next #view_operations": "Here an operation."
            },

            {
                "key #sidebar_main_toggle" : "Click this icon to close primary sidebar.",
                shape : 'circle',
                radius: 30,
                "skipButton" : {text: "Finish"}
            }
        )}
        if(CheminRepertoire == "compte/mandataires"){
            enjoyhint_script_steps.push({
                    "next #recherche" : "Here you can do a contact research by name account number, phone or email and by date.",
                },
                {
                    "click #addMandataire": "this icon  add a mandatory.",
                    shape : 'circle',
                    radius: 30,
                    showSkip: false
                },
                {
                    "next #contForm": "this icon  add a mandatory."
                },

                {
                    "next #update_mandataire": "from this icon you can update mandatory's informations .",
                    shape : 'circle',
                    radius: 30,
                    showSkip: false
                },
                {
                    "next #update_cni": "from this icon you can update pictures of mandatory's cni.",
                    shape : 'circle',
                    radius: 30,
                    showSkip: false
                },
                {
                    "next #detail_mandataire": "this icon display mandatory's details .",
                    shape : 'circle',
                    radius: 30,
                    showSkip: false
                },
                {
                    "next #remove_mandataire": "this icon remove a mandatory.",
                    shape : 'circle',
                    radius: 30,
                    showSkip: false
                })
        }
        if(CheminRepertoire == "compte/contacts"){
            enjoyhint_script_steps.push(
                {
                    "next #recherche" : "Here you can do a contact research by name account number, phone or email and by date.",
                },
                {
                    "next #add_contact": "this icon  add a contact.",
                    shape : 'circle',
                    radius: 30,
                    showSkip: false
                },
                {
                    "next #remove_contact": "this icon remove a contact.",
                    shape : 'circle',
                    radius: 30,
                    showSkip: false
                }
            );
        }
        // config
      /*  var enjoyhint_script_steps = [
            {
                "next #header_main": 'Hello, In this short tour guide I\'ll show you<br>' +
                'some features/components<br>' +
                'This is the main header.<br>' +
                'Click "Next" to proceed.'
            },
            {
                "next #full_screen_toggle" : "Here you can activate fullscreen.",
                shape : 'circle',
                radius: 30,
                showSkip: false
            },
            {
                "click #main_search_btn" : "Click this icon to show search form.",
                shape : 'circle',
                radius: 30,
                showSkip: false
            },
            {
                "next #header_main" : "This is the main search form.",
                showSkip: false
            },
            {
                "next #style_switcher_toggle" : "When you click on that icon '<i class='material-icons'>&#xE8B8;</i>'<br>" +
                "you will activate style switcher.<br>" +
                "There you can change <span class='md-color-red-500'>c</span><span class='md-color-light-blue-500'>o</span><span class='md-color-red-500'>l</span><span class='md-color-orange-500'>o</span><span class='md-color-pink-500'>r</span><span class='md-color-light-green-500'>s</span> and few other things.",
                shape : 'circle',
                radius: 30,
                showSkip: false
            },
            {
                "next #sidebar_main": "This is the primary sidebar.<br>" +
                "Click 'Next' to find out how to close this sidebar.<br>",
                showSkip: false
            },
            // ************      Tour contact      ***********
            {
                "next #recherche" : "Here you can do a contact research by name account number, phone or email and by date.",
            },
            {
                "next #add_contact": "this icon  add a contact.",
                shape : 'circle',
                radius: 30,
                showSkip: false
            },
            {
                "next #remove_contact": "this icon remove a contact.",
                shape : 'circle',
                radius: 30,
                showSkip: false
            },
            // ************      fin  Tour contact      ***********


            // ************      Tour mandataire      ***********
            

            // ************      fin  Tour mandataire      ***********
            {
                "click #do_operation": "Click this icon to do an operation.",
                shape : 'circle',
                radius: 30,
                showSkip: false
            },
            {
                "next #view_operations": "Here an operation."
            },

            {
                "key #sidebar_main_toggle" : "Click this icon to close primary sidebar.",
                shape : 'circle',
                radius: 30,
                "skipButton" : {text: "Finish"}
            }

        ];*/

        // set script config
        enjoyhint_instance.set(enjoyhint_script_steps);

        // run Enjoyhint script
        enjoyhint_instance.run();

        $('#restartTour').click(function() {
            console.log(enjoyhint_instance);
            // run Enjoyhint script
            enjoyhint_instance.run();
        })


    }
};
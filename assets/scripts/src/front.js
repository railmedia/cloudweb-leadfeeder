document.addEventListener('DOMContentLoaded', function() {

    if( cwlfosa.cwlfosa_paragraph_selector && cwlfosa.cwlfosa_image_selector && cwlfosa.cwlfosa_leadfeeder_tracker_script_id ) {

        const interval = setInterval( async () => {

            if( window.discover ) {

                clearInterval(interval);

                let domain   = window.discover.data.company.domain;
                let branche  = window.discover.data.company.industries.name;
                let min 	 = window.discover.data.company.employees_range.min;
                let max 	 = window.discover.data.company.employees_range.max;

                let elementP = document.querySelectorAll( cwlfosa.cwlfosa_paragraph_selector );
                let elementI = document.querySelectorAll( cwlfosa.cwlfosa_image_selector );

                if( elementP.length && domain && min && cwlfosa.cwlfosa_paragraph_template ) {
                    elementP[0].innerHTML = cwlfosa.cwlfosa_paragraph_template.replace( '%branch%', branche ).replace( '%minmax%', min );
                }

                if( elementP.length && domain && min && max && cwlfosa.cwlfosa_paragraph_template ) {
                    elementP[0].innerHTML = cwlfosa.cwlfosa_paragraph_template.replace( '%branch%', branche ).replace( '%minmax%', min + '-' + max );
                }

                if( elementI.length && domain ) {

                    //Get here

                    await fetch('wp-json/cwlfosa/v1/gdfi', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-WP-Nonce': cwlfosa.nonce
                        },
                        body: JSON.stringify({
                            domain: domain
                        })
                    }).then( res => {
                        return res.json();
                    }).then( async body => {

                        if( body.image ) {
                            elementI[0].src = body.response;
                        }

                        await fetch('wp-json/cwlfosa/v1/ddfi', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-WP-Nonce': cwlfosa.nonce
                            },
                            body: JSON.stringify({
                                imgName: body.imgName
                            })
                        });

                    });
                }

            }

        }, 500);

    }

});
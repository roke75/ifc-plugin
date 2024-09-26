jQuery(document).ready(function($){
    if ($(this).find('div[id^="ifc-word-cloud-"]').length) {
        var container = $(this).find('div[id^="ifc-word-cloud-"]');
        var questionId = container.attr('id').split('-').pop();

        // Funktio sanapilven renderöimiseksi
        function renderWordCloud(wordData) {
            console.log('Rendering word cloud with data:', wordData);

            // Sortataan sanat aakkosjärjestykseen varmistamaan yhtenäinen vertailu
            wordData.sort(function(a, b) {
                if (a.text < b.text) return -1;
                if (a.text > b.text) return 1;
                return 0;
            });

            // Muutetaan data merkkijonoksi vertailua varten
            var newDataStr = JSON.stringify(wordData);

            // Haetaan viimeksi renderöity data containerin data-attribuutista
            var lastDataStr = container.data('lastWordCloudData') || '';

            // Verrataan uutta dataa viimeiseen dataan
            if (newDataStr !== lastDataStr) {
                // Data on muuttunut, joten renderöidään pilvi uudelleen

                // Tyhjennetään pilven sisältö
                container.empty();

                // Alustetaan jQCloud uudelleen
                container.jQCloud(wordData, {
                    width: 600,
                    height: 400,
                    autoResize: false,
                    colors: ['#1abc9c', '#2ecc71', '#3498db', '#9b59b6', '#34495e'],
                });

                // Päivitetään viimeksi renderöity data
                container.data('lastWordCloudData', newDataStr);

                console.log('Word cloud rendered.');
            } else {
                // Data ei ole muuttunut, ei tehdä mitään
                console.log('Word cloud data unchanged, skipping render.');
            }
        }

        // Alkuperäinen sanapilven lataus
        $.ajax({
            url: ifc_ajax_obj.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'ifc_get_word_cloud_data',
                nonce: ifc_ajax_obj.nonce,
                question_id: questionId
            },
            success: function(response) {
                console.log('AJAX get_word_cloud_data response:', response);
                if(response.success) {
                    renderWordCloud(response.data.word_cloud_data);
                } else {
                    console.log('Error: ' + response.data.message);
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX-error: ' + status + ' - ' + error);
            }
        });

        // Päivitä sanapilvi 5 sekunnin välein
        setInterval(function(){
            $.ajax({
                url: ifc_ajax_obj.ajax_url,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'ifc_update_word_cloud',
                    nonce: ifc_ajax_obj.nonce,
                    question_id: questionId
                },
                success: function(response) {
                    console.log('AJAX update_word_cloud response:', response);
                    if(response.success) {
                        renderWordCloud(response.data.word_cloud_data);
                    } else {
                        console.log('Error: ' + response.data.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.log('AJAX-error: ' + status + ' - ' + error);
                }
            });
        }, 5000);

    } else {
        var lastID = 0;
        var questionID = $('#ifc-answers').data('question-id');

        function updateAnswers() {
            $.ajax({
                url : ifc_ajax_obj.ajax_url,
                type : 'POST',
                dataType: 'json',
                data : {
                    action : 'ifc_update_answers',
                    nonce : ifc_ajax_obj.nonce,
                    last_id : lastID,
                    question_id : questionID
                },
                success : function( response ) {
                    if (response.status === 'success') {
                        // Add new answers
                        if (response.answers.length > 0) {
                            $.each(response.answers, function(index, answerHtml) {
                                $('#answers-row').append(answerHtml);
                            });
                            // Update last ID
                            lastID = response.latest_id;
                        }
                    } else {
                        console.log('Error: ' + response.message);
                    }
                },
                error: function( xhr, status, error ) {
                    console.log('AJAX-error: ' + status + ' - ' + error);
                }
            });
        }

        // Päivitetään vastaukset heti sivun latautuessa
        updateAnswers();

        // Päivitetään vastaukset 5 sekunnin välein
        setInterval(updateAnswers, 5000);
    }
});

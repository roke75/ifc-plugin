jQuery(document).ready(function($){
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
                            $('#ifc-answers').append(answerHtml);
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
});

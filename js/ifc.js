// js/ifc.js

jQuery(document).ready(function($){
    if ($('.ifc-word-cloud-container').length > 0) {
        $('.ifc-word-cloud-container').each(function(){
            var container = $(this).find('div[id^="ifc-word-cloud-"]');
            var questionId = container.attr('id').split('-').pop();

            function renderWordCloud(wordData) {
                console.log('Rendering word cloud with data:', wordData);

                wordData.sort(function(a, b) {
                    if (a.text < b.text) return -1;
                    if (a.text > b.text) return 1;
                    return 0;
                });

                var newDataStr = JSON.stringify(wordData);

                var lastDataStr = container.data('lastWordCloudData') || '';

                if (newDataStr !== lastDataStr) {
                    container.empty();

                    container.jQCloud(wordData, {
                        width: 600,
                        height: 400,
                    });

                    container.data('lastWordCloudData', newDataStr);

                    console.log('Word cloud rendered.');
                } else {
                    console.log('Word cloud data unchanged, skipping render.');
                }
            }

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
        });
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
                        if (response.answers.length > 0) {
                            $.each(response.answers, function(index, answerHtml) {
                                $('#answers-row').append(answerHtml);
                            });
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

        if (questionID > 0) {
            updateAnswers();
            setInterval(updateAnswers, 5000);
        }
    }
});

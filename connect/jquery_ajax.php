<script>
    $(document).ready(function() { // lorsque la page est chargée, j'effectue les instructions ci-dessous

        $('#post_form').on('submit', function(event) {  
            event.preventDefault();

            if ($('#post_content').val() == '') {
                alert('Vous avez oublié de rédiger votre tweet !!');
            } else {
                var form_data = $(this).serialize(); //  pour pouvoir selectionner plusieurs elements de mon formulaire
                $.ajax({
                    url: "connect/data.php",
                    method: "POST",
                    data: form_data,
                    beforeSend: function() {
                        $('#share_post').attr('disabled', 'disabled');
                    },
                    success: function(data) {
                        $('#dynamic_field').html('<textarea name="post_content" id="post_content" maxlength="140" class="form-control" placeholder="Quoi de neuf ?"></textarea>');
                        $('#post_type').val('text'); // le type de mon input
                        $('#post_form')[0].reset(); // formulaire reset
                        fetch_post();
                        $('#share_post').attr('disabled', false);
                    }
                })
            }
            setInterval(fetch_post(), 1000);
        });

        fetch_post();

        function fetch_post() {
            var action = 'fetch_post';
            $.ajax({
                url: "connect/data.php",
                method: "POST",
                data: {
                    action: action
                },
                success: function(data) {
                    $('#post_list').html(data);
                }
            })
        }

        fetch_user();

        function fetch_user() {
            var action = 'fetch_user';
            $.ajax({
                url: "connect/data.php",
                method: "POST",
                data: {
                    action: action
                },
                success: function(data) {
                    $('#user_list').html(data);
                }
            });
        }

        $(document).on('click', '.action_button', function() { // mon listener 
            console.log(sender_id, action);
            var sender_id = $(this).data('id_follower'); // sender id dans ma balise button

            var action = $(this).data('action'); // je vais chercher l'action follow spécifiée dans ma balise button
            console.log(sender_id, action);

            $.ajax({
                url: "connect/data.php",
                method: "POST",
                data: {
                    id_follower: sender_id,
                    action: action
                }, // à gauche la data, à droite la variable
                success: function(data) {
                    console.log('success');
                    fetch_user();
                    fetch_post();
                }
            })
        });

        var post_id;
        var user_id;

        $(document).on('click', '.post_comment', function() {
            post_id = $(this).attr('id');
            user_id = $(this).data('user_id');
            var action = 'fetch_comment';
            $.ajax({
                url: "connect/data.php",
                method: "POST",
                data: {
                    tweet_id: post_id,
                    user_id: user_id,
                    action: action
                },
                success: function(data) {
                    $('#old_comment' + post_id).html(data);
                    $('#comment_form' + post_id).slideToggle('slow');
                }
            })

        });

        $(document).on('click', '.submit_comment', function() {
            var comment = $('#comment' + post_id).val();
            var action = 'submit_comment';
            var receiver_id = user_id;
            if (comment != '') {
                $.ajax({
                    url: "connect/data.php",
                    method: "POST",
                    data: {
                        tweet_id: post_id,
                        receiver_id: receiver_id,
                        comment: comment,
                        action: action
                    },
                    success: function(data) {
                        $('#comment_form' + post_id).slideUp('slow');
                        fetch_post();
                    }
                })
            }
        });

        $(document).on('click', '.repost', function() {
            var post_id = $(this).data('post_id');
            var action = 'retweet';
            $.ajax({
                url: "connect/data.php",
                method: "POST",
                data: {
                    tweet_id: post_id,
                    action: action
                },
                success: function(data) {
                    alert(data);
                    fetch_post();
                }
            })
        });

        $(document).on('click', '.like_button', function() {
            var post_id = $(this).data('post_id');
            var action = 'like';
            $.ajax({
                url: "connect/data.php",
                method: "POST",
                data: {
                    tweet_id: post_id,
                    action: action
                },
                success: function(data) {
                    alert(data);
                    fetch_post();
                }
            })
        });

        function fetch_post_like_user_list() {
            var fetch_data = '';
            var element = $(this);
            var post_id = element.data('post_id');
            var action = 'like_user_list';
            $.ajax({
                url: "connect/data.php",
                method: "POST",
                async: false,
                data: {
                    post_id: post_id,
                    action: action
                },
                success: function(data) {
                    fetch_data = data;
                }
            });
            return fetch_data;
        }

        $("#notif").on('click', () => {
            var action = 'update_notification_status';
            $.ajax({
                url: "connect/data.php",
                method: "post",
                data: {
                    action: action
                },
                success: function(data) {
                    $('#total_notification').remove();
                }
            })
        })
    });
</script>
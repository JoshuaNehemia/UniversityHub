<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../../SCRIPTS/jquery-3.7.1.min.js"></script>
    <title>Form with Delay</title>
</head>

<body>

    <form id="myForm">
        <input type="text" id="send" name="message_data1" placeholder="Enter text">
        <input type="text" id="send" name="message_data2" placeholder="Enter text">
        <input type="submit" id="submitBtn" value="Submit">
    </form>

    <div id="statusMessage" style="margin-top: 10px; font-weight: bold; font-family: sans-serif;"></div>

<script>
    $(document).ready(function() {

        $('form').on('submit', function(e) {
            e.preventDefault();

            // Elements
            var $form = $(this);
            var $status = $('#statusMessage');
            var $btn = $('#submitBtn');
            var formData = $form.serialize();

            // 1. IMMEDIATE UI UPDATES
            $status.text("Sending... (Waiting 2 seconds)");
            $status.css('color', 'blue');
            
            // Disable button so user can't click again
            $btn.prop('disabled', true);
            $btn.val('Wait...');

            // 2. TIMER (DELAY)
            setTimeout(function() {
                
                // The actual request happens after 2000 milliseconds (2 seconds)
                $.post('controller.php', formData)
                    .done(function(response) {
                        console.log("Server response:", response);
                        $status.text("Text is "+ formData);
                        $status.css('color', 'green');
                    })
                    .fail(function() {
                        $status.text("Error: Connection failed.");
                        $status.css('color', 'red');
                    })
                    .always(function() {
                        // Re-enable button
                        $btn.prop('disabled', false);
                        $btn.val('Submit');
                    });

            }, 500); // <--- 2000ms = 2 seconds delay

        });

    });
</script>
</body>
</html>
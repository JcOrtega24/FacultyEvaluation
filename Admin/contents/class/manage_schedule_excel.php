<?php
    include '../../db_connect.php';
?>

<div class="container-fluid">
    <form action="" id="manage-class" enctype="multipart/form-data" method="post">
        <div class="form-group">
            <label for="subject" class="control-label">Upload Excel</label><br>
            <input type="file" name="excelFile" accept=".xlsx, .xls">
        </div>
    </form>
</div>
<div id="msg" class="form-group"></div>

<script>
    $(document).ready(function() {
        $('#manage-class').on('submit', function(e) {
            e.preventDefault();

            var fileInput = document.querySelector('input[type="file"]');
            var file = fileInput.files[0];
            var formData = new FormData();
            formData.append('excelFile', file);

            var reader = new FileReader();
            reader.onload = function(e) {
                formData.append('excelData', e.target.result);

                $.ajax({
                    url: 'ajax.php?action=save_schedule_excel',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        alert_toast(response); // Assuming alert_toast function is defined elsewhere
                        setTimeout(function(){
                            location.reload();	
                        }, 750); 
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        alert_toast('Error uploading file.'); // Assuming alert_toast function is defined elsewhere
                    }
                });
            };
            reader.readAsBinaryString(file); // Read as binary string
        });
    });
</script>

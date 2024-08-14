<!DOCTYPE html>
<html lang="en">
<style>
  .card {
    width: 80vw;
    max-width: 400px;
  }

  .login-logo {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }

  .logo-img {
    width: 100%;
    height: auto;
    max-width: 120px;
    margin-bottom: 5px;
  }

  .title-login {
    font-size: 1.6rem; 
    margin-bottom: 0px;
  }

  .login-box {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }

  .card {
    overflow: hidden;
    border: 0 !important;
    border-radius: 15px !important;
  }

  .input-group input{
    width: 100%;
    height: 40px;
    padding-left: 40px;
    border: 1px solid #00000020;
    border-radius: 10px;
    outline: none;
    background: transparent;
  }

  .input-group input:focus, .input-group input:valid{
    border: 1px solid #007bff;
  }

  .input-group span{
    position: absolute;
    top: 8px;
    padding-left: 15px;
    /*color: #007bff;*/
  }

  .input-group button[type="submit"]{
    border-radius: 10px;
    font-size: 90%;
    letter-spacing: .1rem;
    transition: 0.5s;
    padding: 8px;
  }

  .warning-symbol {
    color: red; /* Set color to red */
    position: absolute;
    right: 10px; /* Adjust the positioning as needed */
    top: 50%;
    transform: translateY(-50%);
    margin-top: 13px;
  }
  .is-invalid {
    border-color: red !important; /* Set border color to red */
  }

  @media screen and (max-width: 460px) {
    .title-login {
      font-size: 1.4rem;
    }
  }

  @media screen and (max-width: 400px) {
    .title-login {
      font-size: 1.2rem;
    }
  }

  @media screen and (max-width: 340px) {
    .title-login {
      font-size: 1rem;
    }
  }

  @media screen and (max-width: 290px) {
    .title-login {
      font-size: 0.8rem;
    }
  }
</style>
<?php 
  session_start();
  include('db_connect.php');

  if(isset($_SESSION['login_id'])){
    header("location:index.php?page=home");
  }
?>
<?php include 'header.php' ?>
<body class="hold-transition login-page">
  <div class="login-box">
    <div class="card">
      <div class="card-body login-card-body">
        <div class="login-logo">
          <div class="row">
            <img src="assets/img/logo.png" class="logo-img">
          </div>
          <div class="row">
            <h5 class="title-login"><b>Arellano University <br> Faculty Evaluation System</b></h5>
          </div>
        </div>
        <hr>
        <form action="" id="login-form" novalidate>
          <div class="input-group mb-3">
            <span><i class="fas fa-envelope"></i></span>
            <input type="studentid" class="" name="studentid" required placeholder="Student ID"> 
          </div>
          <div class="input-group mb-3">
            <span><i class="fas fa-lock"></i></span>
            <input type="password" class="" name="password" required placeholder="Password">
          </div>
          <div class="input-group">
              <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
        </form>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
</body>
<script>
  $(document).ready(function(){
    $('#login-form').submit(function(e){
      e.preventDefault();
      start_load();

      $(this).find('.alert-danger').remove();
      $(this).find('.warning-symbol').remove();
      $(this).find('.invalid-feedback').remove();

      var isValid = true;

      var inputs = this.querySelectorAll('input[required]');

      inputs.forEach(function(input) {
          // Check if the input field is invalid
          if (!input.checkValidity()) {
              isValid = false; // Set isValid to false if any required field is empty

              // Add red border to the invalid input
              $(input).addClass('is-invalid');

              // Create and append warning symbol
              var warningSymbol = document.createElement('span');
              warningSymbol.innerHTML = '<i class="fas fa-exclamation-circle"></i>';
              warningSymbol.classList.add('warning-symbol');
              input.parentNode.appendChild(warningSymbol);

              // Show invalid-feedback message
              var feedbackMessage = document.createElement('div');
              feedbackMessage.innerHTML = 'Please fill out this field.';
              feedbackMessage.classList.add('invalid-feedback');
              input.parentNode.appendChild(feedbackMessage);
          }
      });

      if (isValid) {
          $.ajax({
              url:'ajax.php?action=login_student',
              method:'POST',
              data:$(this).serialize(),
              error:function(xhr, status, error){
                  console.log(error);
                  end_load();
              },
              success:function(resp){
                  if(resp == 1){
                    location.href ='index.php?page=home';
                  }else if(resp == 2){
                    $('#login-form').prepend('<div class="alert alert-danger">This account is disabled.</div>');
                  }else{
                    $('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>');
                  }
                  end_load();
              }
          });
      } else {
          end_load();
      }

      $(this).addClass('was-validated');
    });

    $('#login-form input[required]').on('input', function() {
        $(this).removeClass('is-invalid');
        $(this).siblings('.warning-symbol').remove();
        $(this).siblings('.invalid-feedback').remove();
    });
  });
</script>
<?php include 'footer.php' ?>
</html>

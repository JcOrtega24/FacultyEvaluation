<style>
  .user-img {
      border-radius: 50%;
      height: 25px;
      width: 25px;
      object-fit: cover;
  }
  .navbar {
    background-color: #fff;
    background: #004a99;
    height: 80px;
  }
  .logo-img {
    width: auto;
    height: auto;
    max-width: 65px;
    max-height: 65px;
  }
  .navbar img {
    margin-left: 25px;
    float: left;  
  }
  .navbar .logo h1 {      
    font-family: Trajan Pro;
    text-transform: uppercase;
    margin-top: 16px;     
    margin-left: 8px;
    color: white; 
    font-size: 2rem; 
    letter-spacing: 0.9px;
    line-height: 1;
    font-weight: 400;
  }
  .nav-account {
    font-family: Trajan Pro;
    margin-top: 16px; 
    font-size: 1.0rem;
  }

  @media screen and (max-width: 840px) {
    .navbar {
      height: 65px;
    }

    .navbar .logo h1 {    
      font-size: 1.40rem; 
      letter-spacing: 0.2px;   
      margin-top: 16px;
      margin-left: 4px;
      word-break: break-all;
    }

    .navbar img {     
      margin-left: 18px;
      float: right;
      position: relative;
    }

    .logo-img {
      max-width: 55px;
      max-height: 55px;
    }

    .nav-account {
      margin-top: 17px;
      font-size: 0.8rem;
    }
  }  

  @media screen and (max-width: 620px) {
    .navbar {
      height: 55px;
    }

    .navbar .logo h1 {      
      font-size: 1.10rem;  
      margin-top: 14px;
      word-break: break-all;
    }

    .navbar img {     
      float: right;
      position: relative;
    }

    .logo-img {
      max-width: 45px;
      max-height: 45px;
    }

    .nav-account {
      margin-top: 18px;
      font-size: 0.6rem;
    }
  }

  @media screen and (max-width: 500px) {
    .navbar {
      height: 45px;
    }

    .navbar .logo h1 {      
      font-size: 0.70rem;  
      margin-top: 12px;
      word-break: break-all;
    }

    .navbar img {     
      margin-left: 12px;
      float: right;
      position: relative;
    }

    .logo-img {
      max-width: 34px;
      max-height: 34px;
    }

    .nav-account {
      margin-top: 16px; 
      font-size: 0.50rem;
    }
  }

  @media screen and (max-width: 360px) {
    .navbar {
      height: 35px;
    }
    .navbar .logo h1 {      
      font-size: 0.60rem;  
      margin-top: 10px;
      word-break: break-all;
    }

    .navbar img {     
      margin-left: 12px;
      float: right;
      position: relative;
    }

    .logo-img {
      max-width: 30px;
      max-height: 30px;
    }

    .nav-account {
      font-size: 0.40rem;
    }
  }

  @media screen and (max-width: 320px) {
    .navbar .logo h1 {      
      font-size: 0.45rem;  
      margin-top: 12px;
      word-break: break-all;
    }

    .navbar img {     
      margin-left: 12px;
      float: right;
      position: relative;
    }

    .logo-img {
      max-width: 30px;
      max-height: 30px;
    }

    .nav-account {
      margin-top: 19px; 
      font-size: 0.35rem;
    }
  }
</style>
<!-- Students Navbar -->
  <nav class="navbar navbar-expand navbar-primary navbar-dark ">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <img src="assets/img/logo.png" class="logo-img">
      </li>
      <li>
        <div class="logo">
          <a href="./"><h1>Faculty Evaluation System</h1></a>
        </div>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
     <li class="nav-item dropdown">
            <a class="nav-link"  data-toggle="dropdown" aria-expanded="true" href="javascript:void(0)">
              <span>
                <div class="nav-account d-felx badge-pill">
                  <span><b><p><?php echo ucwords($_SESSION['login_firstname']." ".$_SESSION['login_lastname']) ?></b></span>
                  <span class="fa fa-angle-down ml-2"></span>
                </div>
              </span>
            </a>
            <div class="dropdown-menu" aria-labelledby="account_settings" style="left: -2.5em;">
            <a class="dropdown-item" href="javascript:void(0)" id="manage_account"><i class="fa fa-cog"></i> Manage Account</a>
              <a class="dropdown-item" href="ajax.php?action=logout_student"><i class="fa fa-power-off"></i> Logout</a>
            </div>
      </li>
    </ul>
  </nav>

  <!-- /.navbar -->
  <script>
     $('#manage_account').click(function(){
        uni_modal('Manage Account','contents/manage_account.php?id=<?php echo $_SESSION['login_id'] ?>')
      })
  </script>

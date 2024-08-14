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
    height: 55px;
  }
  .logo-img {
    width: auto;
    height: auto;
    max-width: 60px;
    max-height: 60px;
  }
  .navbar img {
    margin-left: 25px;
    float: left;  
  }
  .navbar .logo h1 {      
    font-family: Trajan Pro;
    text-transform: uppercase;
    margin-top: 8px;     
    margin-left: 10px;
    color: white; 
    font-size: 2rem; 
    letter-spacing: 1.5px;
    line-height: 1;
  }
  .nav-account {
    margin-top: 2px;
    font-size: .9rem;
  }
</style>
<!-- Admin Navbar -->
  <nav class="main-header navbar navbar-expand navbar-primary navbar-dark ">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <!mg src="assets/img/logo.png" class="logo-img">
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
              <a class="dropdown-item" href="ajax.php?action=logout_admin"><i class="fa fa-power-off" style="color: black;"></i> Logout</a>
            </div>
      </li>
    </ul>
  </nav>

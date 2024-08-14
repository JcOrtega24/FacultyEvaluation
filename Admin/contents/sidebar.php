  <style>
    aside {
      background: #004a99;
      color: white;
    }
    aside h4,
    .nav-item i,
    .nav-item p {
        color: white;
    }
    .nav-link.active {
      background-color: #da291c; /* Change to the desired background color */    
      border-radius: 5px;  
    }
    aside h4 {
      font-size: 1.5rem; 
      letter-spacing: 2.0px;
      line-height: .9;
      text-transform: uppercase;
      font-family: Trajan Pro;
    }
    aside .logo-img {
      width: auto;
      height: auto;
      max-width: 60px;
      max-height: 60px;
      margin-left: 2px;
    }
    .banner {
      height: 20px;
    }
    .banner .brand-link {
      display: flex;
      align-items: center;
    }
    .banner .logo-img {
      margin-right: 4px; 
    }
    .banner .brand-name {
      margin: 0; 
    }
    .sidebar {
      margin-top: 60px;
    }
  </style>
  <aside class="main-sidebar elevation-2">
    <div class="banner">
      <a href="./" class="brand-link">
        <img src="assets/img/logo.png" class="logo-img">
        <h4 class="brand-name">Arellano<br>University</h4>
      </a>
    </div>
    <div class="sidebar">
      <nav class="mt-2">  
        <ul class="nav nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
         <li class="nav-item dropdown">
            <a href="./" class="nav-link nav-home">
              <i class="nav-icon fas fa-table"></i>
              <p>
                Dashboard
              </p>
            </a>  
          </li>
          <li class="nav-item dropdown">
            <a href="./index.php?page=schoolyear_list" class="nav-link nav-schoolyear_list">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                School Year
              </p>
            </a>
          </li>
          <li class="nav-item dropdown">
            <a href="./index.php?page=subject_list" class="nav-link nav-subject_list">
              <i class="nav-icon fas fa-book"></i>
              <p>
                Subjects
              </p>
            </a>
          </li> 
          <li class="nav-item">
            <a href="./index.php?page=faculty_list" class="nav-link nav-faculty_list">
              <i class="nav-icon fas fa-user-friends"></i>
              <p>
                Faculty
              </p>
            </a>
          </li>
          <li class="nav-item dropdown">
            <a href="./index.php?page=class_list" class="nav-link nav-class_list">
              <i class="nav-icon fas fa-th-list"></i>
              <p>
                Classes
              </p>
            </a>
          </li> 
          <li class="nav-item">
            <a href="./index.php?page=student_list" class="nav-link nav-student_list">
              <i class="nav-icon fa fa-users"></i>
              <p>
                 Students
              </p>
            </a>
          </li>
          <li class="nav-item dropdown">
            <a href="./index.php?page=criteria" class="nav-link nav-criteria">
              <i class="nav-icon fas fa-file"></i>
              <p>
                Criteria
              </p>
            </a>
          </li>
          <li class="nav-item dropdown">
            <a href="./index.php?page=questionnaire" class="nav-link nav-questionnaire">
              <i class="nav-icon fas fa-file-alt"></i>
              <p>
                Questionnaire
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="./index.php?page=evaluation_status" class="nav-link nav-evaluation_status">
              <i class="nav-icon fas fa-list"></i>
              <p>
                Evaluation Status
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link nav-report">
            <i class="nav-icon fas fa-list-alt"></i>
              <p>
              Evaluation Report
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.php?page=department_report" class="nav-link nav-department_report tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Department Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=faculty_report" class="nav-link nav-faculty_report tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Faculty Report</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="./index.php?page=user_list" class="nav-link nav-user_list">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Users
              </p>
            </a>
          </li>
          <!--li class="nav-item" style="position: fixed; bottom: 0;">
            <a href="ajax.php?action=logout_admin" class="nav-link">
              <i class="nav-icon fas fa-power-off"></i>
              <p>
                Logout
              </p>
            </a>
          </li-->
        </ul>
      </nav>
    </div>
  </aside>
  <script>
  	$(document).ready(function(){
      var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
  		var s = '<?php echo isset($_GET['s']) ? $_GET['s'] : '' ?>';
      if(s!='')
        page = page+'_'+s;
  		if($('.nav-link.nav-'+page).length > 0){
             $('.nav-link.nav-'+page).addClass('active')
  			if($('.nav-link.nav-'+page).hasClass('tree-item') == true){
            $('.nav-link.nav-'+page).closest('.nav-treeview').siblings('a').addClass('active')
  				$('.nav-link.nav-'+page).closest('.nav-treeview').parent().addClass('menu-open')
  			}
        if($('.nav-link.nav-'+page).hasClass('nav-is-tree') == true){
          $('.nav-link.nav-'+page).parent().addClass('menu-open')
        }
  		}
  	})
  </script>
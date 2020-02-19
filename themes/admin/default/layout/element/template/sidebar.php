   <div class="sidebar" data-color="<?= $this->setting->data_color; ?>" data-background-color="<?= $this->setting->background_color; ?>" data-image="<?= $this->setting->background_image; ?>">
       <div class="logo">
           <a href="<?= base_url() ?>" class="simple-text logo-mini">
               <img src="<?= base_url("assets/img/setting/favicon.png") ?>" width="30px" />
           </a>
           <a href="<?= base_url() ?>" class="simple-text logo-normal">
               <?= $this->setting->site_name ?>
           </a>
       </div>

       <div class="sidebar-wrapper">
           <div class="user">
               <div class="photo">
                   <img src="<?= getProfile($user['image'], 'thumbnail') ?>">
               </div>
               <div class="user-info">
                   <a data-toggle="collapse" href="#collapseExample" class="username">
                       <span>
                           <?= $user['name'] ?>
                           <b class=" caret"></b>
                       </span>
                   </a>
                   <div class="collapse" id="collapseExample">
                       <ul class="nav">
                           <li class="nav-item <?= ($this->router->fetch_class() == 'user' && $this->router->fetch_method() == 'profile') ? 'active' : ''; ?>">
                               <a class="nav-link" href="<?= base_url("admin/user/profile") ?>">
                                   <span class="sidebar-mini"><i class="material-icons">person</i></span>
                                   <span class="sidebar-normal">Account</span>
                               </a>
                           </li>
                           <li class="nav-item <?= ($this->router->fetch_class() == 'user' && $this->router->fetch_method() == 'changepassword') ? 'active' : ''; ?>">
                               <a class="nav-link" href="<?= base_url("admin/user/changepassword") ?>">
                                   <span class="sidebar-mini"><i class="material-icons">person</i></span>
                                   <span class="sidebar-normal">Change Password</span>
                               </a>
                           </li>
                           <li class="nav-item">
                               <a class="nav-link text-danger" id="dataOut" href="#" data-toggle="modal" data-url="<?= base_url('admin/auth/logout') ?>" data-target="#logout">
                                   <span class="sidebar-mini text-danger"><i class="material-icons text-danger">settings_power</i></span>
                                   <span class="sidebar-normal">Logout</span>
                               </a>
                           </li>

                       </ul>
                   </div>
               </div>
           </div>
           <ul class="nav">
               <?php if ($this->session->userdata("role_id") == "10") : ?>
                   <li class="nav-item <?= ($this->router->fetch_class() == 'dashboard') ? 'active' : ''; ?>">
                       <a class="nav-link" href="<?= base_url('admin/dashboard') ?>">
                           <i class="material-icons">dashboard</i>
                           <p> Dashboard </p>
                       </a>
                   </li>
               <?php endif ?>
               <!-- QUERY MENU -->
               <?php foreach ($menu_all as $m) : ?>
                   <?php
                    if ($m['menu'] == "user") {
                        continue;
                    } ?>
                   <li class="nav-item">
                       <a class="nav-link" data-toggle="collapse" href="#<?= $m['menu']; ?>">
                           <?= @$m['icon']; ?>
                           <p> <?= $m['menu']; ?>
                               <b class="caret"></b>
                           </p>
                       </a>
                       <div class="collapse <?= $this->menu == $m['id_menu'] ? 'show' : '' ?>" id="<?= $m['menu']; ?>">
                           <ul class="nav">
                               <?php foreach ($this->menu_m->get_submenuIdMenu($m['id_menu'])->result_array() as $row) : ?>
                                   <li class="nav-item <?= is_active(strtolower($row['class']), strtolower($row['method'])) ?>">
                                       <a class="nav-link" href="<?= base_url() . $row['url']; ?>">
                                           <span class="sidebar-mini"> <?= $row['icon']; ?> </span>
                                           <span class="sidebar-normal"> <?= $row['title'] ?> </span>
                                       </a>
                                   </li>
                               <?php endforeach ?>
                           </ul>
                       </div>
                   </li>
               <?php endforeach; ?>

           </ul>
       </div>
   </div>
   <script>
       $(document).ready(function() {
           let menu = $('.nav-item.active');
           menu.parents('li.nav-item').addClass('active')
               .children('a.nav-link').attr('aria-expanded', "true");
           menu.parents('div.collapse').addClass('show');
       });
   </script>
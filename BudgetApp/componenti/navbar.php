      <!-- Navbar -->
      <nav class="navbar navbar-dark bg-dark fixed-top">
          <div class="container-fluid">
              <a class="navbar-brand" href="<?php echo $GLOBALS['Gestione'] ?>">Budget App</a>
              <span class="navbar-text"><?php $curPageName = substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1);
                                        $curPageName = str_replace(".php", "", "$curPageName");
                                        echo $curPageName; ?></span>
              <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
              </button>
              <div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
                  <div class="offcanvas-header">
                      <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">Budget App</h5>
                      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                  </div>
                  <div class="offcanvas-body">
                      <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                          <li class="nav-item">
                              <a class="nav-link active" aria-current="page" href="<?php echo $GLOBALS['Homepage'] ?>">Home</a>
                          </li>
                          <li class="nav-item">
                              <a class="nav-link" href="<?php echo $GLOBALS['Transazioni'] ?>">Transazioni Totali</a>
                          </li>
                          <li class="nav-item">
                              <a class="nav-link" href="<?php echo $GLOBALS['TransazioniMese'] ?>">Transazioni Mese</a>
                          </li>
                          <li class="nav-item ">
                              <a class="nav-link" href="<?php echo $GLOBALS['TransazioniPending'] ?>">Transazioni Pending <span class="badge text-bg-danger m-1" id="count"></span></a>
                          </li>
                          <li class="nav-item ">
                              <a class="nav-link" href="<?php echo $GLOBALS['Gestione'] ?>">Gestione</a>
                          </li>
                      </ul>
                  </div>
              </div>
          </div>
      </nav>
      <!-- Navbar -->

      <!-- Jquery CDN -->
      <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
      <!-- End of Jquery CDN -->

      <script>
          $(document).ready(function() {
              var pathname = window.location.pathname;
              pathname = pathname.split("/")
              pathloadnotifica = "/" + pathname[1] + "/" + "php/" + "count/counttransazionipending.php"

              var dt;
              $.get(pathloadnotifica, function(data) {
                  dt = data;
                  if (dt == 0) {

                      $("#count").removeClass("badge text-bg-danger m-1").addClass("badge text-bg-secondary m-1")
                    
                  }
              });


              $("#count").load(pathloadnotifica);
          })
          $.ajax({
              type: 'POST',
              url: './php/disoperazioneric.php',
              data: {
                  ajax: 1,
              },
              success: function(response) {}
          });
      </script>
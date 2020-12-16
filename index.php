<?php

// Iniciamos las variables de sesión con @ para que no nos devuelva warnings si la sesión ya estaba iniciada
@session_start();

date_default_timezone_set('Europe/Madrid');
// Requerimos fichero de configuración esencial de directorios y constantes
require_once("./config.php");

// Requerimos seteado de variables para que no estén vacías y salte warning
require_once("./initial_vars.php");

// Requerimos el fichero de configuración de variables de conexión
require_once($dirs['bdConfig']);

// Requerimos la clase Asysteco
require_once($dirs['class'] . 'Asysteco.php');

// iniciamos la clase y la guardamos en $class
$class = new Asysteco;

// Iniciamos la conexión a la base de datos
$class->bdConex($insti_host, $insti_user, $insti_pass, $insti_db);

// Establecemos UTF8 como cotejamiento de caracteres
if (!$class->conex->set_charset("utf8")) {
  printf("Error cargando el conjunto de caracteres utf8: %s\n", $class->conex->error);
  exit();
}

// Comprobamos si existen horarios para actualizar
if (!$class->horarioTemporalAHorarioReal()) {
  $ERR_MSG = $class->ERR_ASYSTECO;
  $ERR_MSG .= "
    <br>
    <span class='glyphicon glyphicon-warning-sign'> </span> Contacta urgentemente con los administradores de la plataforma.
    <br>
    <a href='mailto:admin@asysteco.com?subject=Urgente%20ASYSTECO%20Horarios_Temporales&body=Ha%20surgido%20un%20problema%20al%20generar%20los%20horarios%20desde%20temporales%20en%20$Titulo.'>Enviar correo urgente</a>";
}

// Comprobamos si está seteada la variable ACTION en la URL (Método GET)

if (isset($_GET['ACTION'])) {
  switch ($_GET['ACTION']) {
    default:
      if (isset($_POST['Iniciales']) || isset($_POST['pass'])) {
        require_once($dirs['inc'] . 'login_valida.php');
      }
      if ($class->isLogged($Titulo)) {
        if ($class->compruebaCambioPass()) {
          if ($_SESSION['Perfil'] === 'Admin') {
            $act_home = 'active';
            $scripts = '<link rel="stylesheet" href="css/profesores.css">';
            if (isset($_POST['boton']) && $class->validRegisterProf()) {
              header('Location: index.php?ACTION=profesores');
            }
            include_once($dirs['Interfaces'] . 'header.php');
            include_once($dirs['Interfaces'] . 'top-nav.php');
            include_once($dirs['Profesores'] . 'profesores.php');
            include($dirs['Interfaces'] . 'errors.php');
            include($dirs['Interfaces'] . 'footer.php');
          } elseif ($_SESSION['Perfil'] === 'Profesor') {
            $act_qr = 'active';
            include_once($dirs['Interfaces'] . 'header.php');
            include_once($dirs['Interfaces'] . 'top-nav.php');
            include_once($dirs['Qr'] . 'generate_code.php');
            include($dirs['Interfaces'] . 'errors.php');
            include($dirs['Interfaces'] . 'footer.php');
          } else {
            die('<h1 style="color:red;">Error de proceso...</h1>');
          }
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        include_once($dirs['inc'] . 'login_form.php');
      }
      break;

    case 'admin-login':
      include_once($dirs['Login'] . 'admin-login.php');
      break;

    case 'logout':
      include_once($dirs['Login'] . 'logout.php');
      break;

    case 'cambio_pass':
      if ($class->isLogged($Titulo)) {
        if ($class->compruebaCambioPass()) {
          $act_usuario = 'active';
          $act_changePass = 'active';

          $scripts = '<link rel="stylesheet" href="css/change-pass-style.css">';
          include_once($dirs['Valida'] . 'valida_new_pass.php');
          include_once($dirs['Interfaces'] . 'header.php');
          include_once($dirs['Interfaces'] . 'top-nav.php');
          include_once($dirs['Login'] . 'new_pass.php');
          include_once($dirs['Interfaces'] . 'errors.php');
          include_once($dirs['Interfaces'] . 'footer.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['Interfaces'] . 'msg_modal.php');
      }
      break;

    case 'primer_cambio':
      $scripts = '<link rel="stylesheet" href="css/login-style.css">';
      include_once($dirs['Valida'] . 'valida_primer_cambio.php');
      include_once($dirs['Interfaces'] . 'header.php');
      include_once($dirs['Interfaces'] . 'top-nav.php');
      include_once($dirs['Login'] . 'primer_cambio.php');
      include_once($dirs['Interfaces'] . 'errors.php');
      include_once($dirs['Interfaces'] . 'footer.php');
      break;

    case 'lectivos':
      if ($class->isLogged($Titulo)) {
        if ($class->compruebaCambioPass()) {
          if ($response = $class->query("SELECT COUNT(*) as num FROM $class->marcajes")) {
            $act_cal_escolar = 'active';
            $marcajes = $response->fetch_assoc();
            if ($marcajes['num'] > 0) {
              $scripts = '<link rel="stylesheet" href="css/form.css">';
              include_once($dirs['Interfaces'] . 'header.php');
              include_once($dirs['Interfaces'] . 'top-nav.php');
              echo '<div class="container" style="margin-top: 75px;">';
                echo "<div class='row'>";
                  echo "<div class='col-xs-12'>";
                    echo '<h1>Calendario escolar</h1>';
                    include_once($dirs['Horarios'] . 'calendario.php');
                  echo "</div>";
                echo "</div>";
              echo "</div>";
              include_once($dirs['Interfaces'] . 'errors.php');
              include_once($dirs['Interfaces'] . 'footer.php');
            } else {
              $scripts = '<link rel="stylesheet" href="css/form.css">';
              $extras = "
                  $(function (){
                      $('#datepicker_ini').datepicker();
                  });
                  $(function (){
                      $('#datepicker_fin').datepicker();
                  });
                  $(function (){
                      $('#datepicker_ini_fest').datepicker();
                  });
                  $(function (){
                      $('#datepicker_fin_fest').datepicker();
                  });
                ";
              include_once($dirs['Valida'] . 'valida-lectivos.php');
              include_once($dirs['Interfaces'] . 'header.php');
              include_once($dirs['Interfaces'] . 'top-nav.php');
              include_once($dirs['Horarios'] . 'lectivos.php');
              include_once($dirs['public'] . 'js/lectivos.js');
              include_once($dirs['Interfaces'] . 'errors.php');
              include_once($dirs['Interfaces'] . 'footer.php');
            }
          } else {
            $ERR_MSG = $class->ERR_ASYSTECO;
          }
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['Interfaces'] . 'msg_modal.php');
      }
      break;

    case 'qrcoder':
      if ($class->isLogged($Titulo)) {
        if ($class->compruebaCambioPass()) {
          $act_qr = 'active';
          include_once($dirs['Interfaces'] . 'header.php');
          include_once($dirs['Interfaces'] . 'top-nav.php');
          include_once($dirs['Qr'] . 'generate_code.php');
          include_once($dirs['Interfaces'] . 'errors.php');
          include_once($dirs['Interfaces'] . 'footer.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['Interfaces'] . 'msg_modal.php');
      }
      break;

    case 'registrarse':
      if ($class->isLogged($Titulo)) {
        if ($class->compruebaCambioPass()) {
          if (isset($_POST['Nombre']) || isset($_POST['Iniciales']) || isset($_POST['pass1']) || isset($_POST['pass2'])) {
            include_once($dirs['Valida'] . 'register_valida.php');
          } else {
            include_once($dirs['Form'] . 'register_form.php');
          }
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['Interfaces'] . 'msg_modal.php');
      }
      break;

    case 'horarios':
      require_once($dirs['Horarios'] . 'IndexCase.php');
    break;

    case 'asistencias':
      if ($class->isLogged($Titulo)) {
        if ($class->compruebaCambioPass()) {
          $act_asistencia = 'active';
          $scripts = '<link rel="stylesheet" href="css/asistencias.css">';
          $extras = "
              $(function (){
                  $('#busca_asiste').datepicker();
              });
            ";
          include_once($dirs['Interfaces'] . 'header.php');
          include_once($dirs['Interfaces'] . 'top-nav.php');

          switch ($_GET['OPT'] ?? '') {
            case 'all':
              if ($_SESSION['Perfil'] === 'Admin') {
                include_once($dirs['Fichaje'] . 'contenido-asistencias-all.php');
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['Interfaces'] . 'msg_modal.php');
              }
              break;

            case 'sesion':
              $_GET['ID'] = $_SESSION['ID'];
              include_once($dirs['Fichaje'] . 'contenido-asistencias.php');
              break;

            default:
              include_once($dirs['Fichaje'] . 'contenido-asistencias.php');
              break;
          }

          include_once('js/filtro_asistencias.js');
          include_once($dirs['Interfaces'] . 'errors.php');
          include_once($dirs['Interfaces'] . 'footer.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['Interfaces'] . 'msg_modal.php');
      }
      break;

    case 'profesores':
      require_once($dirs['Profesores'] . 'IndexCase.php');
      break;

    case 'marcajes':
      if ($class->isLogged($Titulo)) {
        if ($class->compruebaCambioPass()) {
          switch ($_GET['OPT']) {
            case 'update':
              include_once($dirs['Horarios'] . 'update-marcajes.php');
              break;

            default:
              header('Location: index.php');
              break;
          }
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['Interfaces'] . 'msg_modal.php');
      }
      break;

    case 'guardias':
      if ($class->isLogged($Titulo)) {
        if ($class->compruebaCambioPass()) {
          $act_guardias = 'active';
          $scripts = '<link rel="stylesheet" href="css/qr-reader.css">';

          if (!$options['QR-reader']) {
            $scripts .= '
              <script type="text/javascript" src="js/jsqrcode/grid.js"></script>
              <script type="text/javascript" src="js/jsqrcode/version.js"></script>
              <script type="text/javascript" src="js/jsqrcode/detector.js"></script>
              <script type="text/javascript" src="js/jsqrcode/formatinf.js"></script>
              <script type="text/javascript" src="js/jsqrcode/errorlevel.js"></script>
              <script type="text/javascript" src="js/jsqrcode/bitmat.js"></script>
              <script type="text/javascript" src="js/jsqrcode/datablock.js"></script>
              <script type="text/javascript" src="js/jsqrcode/bmparser.js"></script>
              <script type="text/javascript" src="js/jsqrcode/datamask.js"></script>
              <script type="text/javascript" src="js/jsqrcode/rsdecoder.js"></script>
              <script type="text/javascript" src="js/jsqrcode/gf256poly.js"></script>
              <script type="text/javascript" src="js/jsqrcode/gf256.js"></script>
              <script type="text/javascript" src="js/jsqrcode/decoder.js"></script>
              <script type="text/javascript" src="js/jsqrcode/qrcode.js"></script>
              <script type="text/javascript" src="js/jsqrcode/findpat.js"></script>
              <script type="text/javascript" src="js/jsqrcode/alignpat.js"></script>
              <script type="text/javascript" src="js/jsqrcode/databr.js"></script>
              ';
          }

          include_once($dirs['Interfaces'] . 'header.php');
          include_once($dirs['Interfaces'] . 'top-nav.php');
          include($dirs['Interfaces'] . 'home.php');
          include($dirs['Interfaces'] . 'errors.php');
          include($dirs['Interfaces'] . 'footer.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['Interfaces'] . 'msg_modal.php');
      }
      break;

    case 'cp':
      echo '
        <!DOCTYPE html>
        <html lang="es">
        <head>
          <title>Inicio</title>
          <meta charset="utf-8">
          <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
          <link rel="stylesheet" href="css/bootstrap-3.4.1/css/bootstrap.min.css">
          <link rel="stylesheet" href="css/asysteco.css">
          <link rel="stylesheet" href="js/jquery-ui/jquery-ui.min.css">
          <link rel="shortcut icon" href="resources/img/asysteco.ico" type="image/x-icon">
          <script src="js/jquery.min.js"></script>
          <script src="js/bootstrap.min.js"></script>
          <script src="js/jquery-ui/jquery-ui.min.js"></script>
          <script src="js/datepicker_common.js"></script>
          <script src="js/flecha.js"></script>
          <link rel="stylesheet" href="css/qr-reader.css">
          
          <script>
            var userAgent = navigator.userAgent.toLowerCase();
            var isSupportedBrowser = (/armv.* raspbian chromium/i).test(userAgent);
            if(! isSupportedBrowser)
            {
              location.href = "index.php";
            }
          </script>';

      if (!$options['QR-reader']) {
        echo '
              <script type="text/javascript" src="js/jsqrcode/grid.js"></script>
              <script type="text/javascript" src="js/jsqrcode/version.js"></script>
              <script type="text/javascript" src="js/jsqrcode/detector.js"></script>
              <script type="text/javascript" src="js/jsqrcode/formatinf.js"></script>
              <script type="text/javascript" src="js/jsqrcode/errorlevel.js"></script>
              <script type="text/javascript" src="js/jsqrcode/bitmat.js"></script>
              <script type="text/javascript" src="js/jsqrcode/datablock.js"></script>
              <script type="text/javascript" src="js/jsqrcode/bmparser.js"></script>
              <script type="text/javascript" src="js/jsqrcode/datamask.js"></script>
              <script type="text/javascript" src="js/jsqrcode/rsdecoder.js"></script>
              <script type="text/javascript" src="js/jsqrcode/gf256poly.js"></script>
              <script type="text/javascript" src="js/jsqrcode/gf256.js"></script>
              <script type="text/javascript" src="js/jsqrcode/decoder.js"></script>
              <script type="text/javascript" src="js/jsqrcode/qrcode.js"></script>
              <script type="text/javascript" src="js/jsqrcode/findpat.js"></script>
              <script type="text/javascript" src="js/jsqrcode/alignpat.js"></script>
              <script type="text/javascript" src="js/jsqrcode/databr.js"></script>';
        echo '</head>
            <body>';

        echo '<div class="container-fluid" style="margin-top:50px">';
        echo "<div class='row'>";
        echo "<div id='qreader' class='col-xs-12'>";
        include($dirs['Qr'] . 'qr-webcam-admin-login.php');
        echo "</div>";
        echo "</div>";
        echo "</div>";
        include_once($dirs['public'] . 'js/qr-webcam-admin-login.js');
      } else {
        echo '<div class="container-fluid" style="margin-top:50px">';
        echo "<div class='row'>";
        echo "<div id='qreader' class='col-xs-12' style='margin-top: 20vh;'>";
        include($dirs['Qr'] . 'qr-reader-admin-login.php');
        echo "</div>";
        echo "</div>";
        echo "</div>";
        include_once($dirs['public'] . 'js/qr-reader-admin-login.js');
      }
      include($dirs['Interfaces'] . 'errors.php');
      include($dirs['Interfaces'] . 'footer.php');
      break;

    case 'notificaciones':
      if ($class->isLogged($Titulo) && $_SESSION['Perfil'] == 'Admin') {
        if ($class->compruebaCambioPass()) {
          $act_usuario = 'active';
          $act_notification = 'active';

          include_once($dirs['Interfaces'] . 'header.php');
          include_once($dirs['Interfaces'] . 'top-nav.php');
          include_once($dirs['Interfaces'] . 'notificaciones.php');
          include_once($dirs['Interfaces'] . 'errors.php');
          include_once($dirs['Interfaces'] . 'footer.php');
        } else {
          header('Location: index.php');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['Interfaces'] . 'msg_modal.php');
      }
      break;

    case 'admon':
      if ($class->isLogged($Titulo) && $_SESSION['Perfil'] == 'Admin') {
        if ($class->compruebaCambioPass()) {
          $act_usuario = 'active';
          $act_admon  = 'active';
          
          $extras = "        
              $(function (){
                  $('#fechainicio').datepicker();
              });
            ";
          $style = "
            input[type=text], #select_profesor {
              width: 25%;
              display: inline-block;
            }
            ";
          if (!isset($_GET['OPT'])) {
            $_GET['OPT'] = '';
          }
          switch ($_GET['OPT']) {
            case 'select':
              $action = $_GET['action'];
              $element = $_GET['element'];

              if (isset($action) && $action === 'export') {
                if ($element === 'marcajes') {
                  include_once($dirs['Exportar'] . 'export_marcajes.php');
                } elseif ($element === 'asistencias') {
                  include_once($dirs['Exportar'] . 'export_asistencias.php');
                } elseif ($element === 'faltas') {
                  include_once($dirs['Exportar'] . 'export_faltas.php');
                } elseif ($element === 'horarios') {
                  include_once($dirs['Exportar'] . 'export_horarios.php');
                } elseif ($element === 'profesores') {
                  include_once($dirs['Exportar'] . 'export_profesores.php');
                } elseif ($element === 'fichajes') {
                  include_once($dirs['Exportar'] . 'export_fichajes.php');
                } else {
                  $MSG = 'error-export';
                }
              } elseif (isset($action) && $action === 'select') {
                if ($element === 'marcajes') {
                  include_once($dirs['Listar'] . 'list_marcajes.php');
                } elseif ($element === 'asistencias') {
                  include_once($dirs['Listar'] . 'list_asistencias.php');
                } elseif ($element === 'faltas') {
                  include_once($dirs['Listar'] . 'list_faltas.php');
                } elseif ($element === 'horarios') {
                  include_once($dirs['Listar'] . 'list_horarios.php');
                } elseif ($element === 'fichajeDiario') {
                  include_once($dirs['Listar'] . 'list_fichaje_diario.php');
                } elseif ($element === 'fichajeFechaFilter') {
                  include_once($dirs['Listar'] . 'list_fichaje_fecha.php');
                } else {
                  $MSG = 'error-export';
                }
              }
              break;

            default:
              include_once($dirs['Interfaces'] . 'header.php');
              include_once($dirs['Interfaces'] . 'top-nav.php');
              include_once($dirs['Interfaces'] . 'menu_admon.php');
              include_once($dirs['Interfaces'] . 'errors.php');
              include_once($dirs['Interfaces'] . 'footer.php');
              break;
          }
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['Interfaces'] . 'msg_modal.php');
      }
      break;

    case 'fichar-asist':
      if ($class->isLogged($Titulo) && $_SESSION['Perfil'] == 'Admin') {
        if ($class->compruebaCambioPass()) {
          include_once($dirs['Fichaje'] . 'fichar-asistencia.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['Interfaces'] . 'msg_modal.php');
      }
      break;

    case 'fichar-manual':
      if ($class->isLogged($Titulo) && $_SESSION['Perfil'] === 'Admin') {
        if ($class->compruebaCambioPass()) {
          $extras = "
            $(function (){
              $('#add-fecha').datepicker({minDate: -5, maxDate: 0});
            });
          ";
          $scripts = '<link rel="stylesheet" href="css/profesores-sustituir.css">';
          include_once($dirs['Interfaces'] . 'header.php');
          include_once($dirs['Interfaces'] . 'top-nav.php');
          include_once($dirs['Fichaje'] . 'fichar-manual.php');
          include_once($dirs['Interfaces'] . 'errors.php');
          include_once($dirs['Interfaces'] . 'footer.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['Interfaces'] . 'msg_modal.php');
      }
      break;

    case 'fichar-mysql-manual':
      if ($class->isLogged($Titulo) && $_SESSION['Perfil'] === 'Admin') {
        if ($class->compruebaCambioPass()) {
          include_once($dirs['Fichaje'] . 'fichar-mysql-manual.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['Interfaces'] . 'msg_modal.php');
      }
      break;

    case 'plantilla-horarios':
      if ($class->isLogged($Titulo) && $_SESSION['Perfil'] === 'Admin') {
        if ($class->compruebaCambioPass()) {
          require_once($dirs['Exportar'] . 'export-plantilla-horarios.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['Interfaces'] . 'msg_modal.php');
      }
      break;

      case 'backup-centro':
        if ($class->isLogged($Titulo) && $_SESSION['Perfil'] == 'Admin') {
          if ($class->compruebaCambioPass()) {
            include_once($dirs['Exportar'] . 'backup-centro.php');
          } else {
            header('Location: index.php?ACTION=primer_cambio');
          }
        } else {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['Interfaces'] . 'msg_modal.php');
        }
      break;

    case 'plantilla-profesores':
      if ($class->isLogged($Titulo) && $_SESSION['Perfil'] === 'Admin') {
        if ($class->compruebaCambioPass()) {
          require_once($dirs['Exportar'] . 'export-plantilla-profesores.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['Interfaces'] . 'msg_modal.php');
      }
      break;

    case 'download_admin_guide':
      if ($class->isLogged($Titulo) && $_SESSION['Perfil'] === 'Admin') {
        if ($class->compruebaCambioPass()) {
          require_once($dirs['Exportar'] . 'export-guide-admin.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['Interfaces'] . 'msg_modal.php');
      }
      break;

    case 'download_profesor_guide':
      if ($class->isLogged($Titulo)) {
        if ($class->compruebaCambioPass()) {
          require_once($dirs['Exportar'] . 'export-guide-profesor.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['Interfaces'] . 'msg_modal.php');
      }
      break;

    case 'clean_tmp':
      if ($class->isLogged($Titulo)) {
        if ($class->compruebaCambioPass()) {
          include_once($dirs['Helper'] . 'clean_tmp.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['Interfaces'] . 'msg_modal.php');
      }
      break;
  }
} else {
  if (isset($_POST['Iniciales']) || isset($_POST['pass'])) {
    require_once($dirs['Login'] . 'login_valida.php');
  }
  if ($class->isLogged($Titulo)) {
    if ($class->compruebaCambioPass()) {
      if ($_SESSION['Perfil'] === 'Admin') {
        $act_home = 'active';
        $scripts = '<link rel="stylesheet" href="css/profesores.css">';
        if (isset($_POST['boton']) && $class->validRegisterProf()) {
          header('Location: index.php?ACTION=profesores');
        }
        include_once($dirs['Interfaces'] . 'header.php');
        include_once($dirs['Interfaces'] . 'top-nav.php');
        include_once($dirs['Profesores'] . 'profesores.php');
        include($dirs['Interfaces'] . 'errors.php');
        include($dirs['Interfaces'] . 'footer.php');
      } elseif ($_SESSION['Perfil'] === 'Profesor') {
        $act_qr = 'active';
        include_once($dirs['Interfaces'] . 'header.php');
        include_once($dirs['Interfaces'] . 'top-nav.php');
        include_once($dirs['Qr'] . 'generate_code.php');
        include($dirs['Interfaces'] . 'errors.php');
        include($dirs['Interfaces'] . 'footer.php');
      } else {
        die('<h1 style="color:red;">Error de proceso...</h1>');
      }
    } else {
      header('Location: index.php?ACTION=primer_cambio');
    }
  } else {
    include_once($dirs['Login'] . 'login_form.php');
  }
}

<?php

// Iniciamos las variables de sesión con @ para que no nos devuelva warnings si la sesión ya estaba iniciada
@session_start();

date_default_timezone_set('Europe/Madrid');

// Requerimos fichero de configuración esencial de directorios y constantes
require_once("./horario.php");
require_once("./config.php");
require_once("./dirs.php");
require_once("./initial_vars.php");

// Requerimos el fichero de configuración de variables de conexión
require_once($dirs['bdConfig']);

// Requerimos la clase Asysteco
require_once($dirs['class'] . 'Asysteco.php');

// iniciamos la clase y la guardamos en $class
$class = new Asysteco;

// Iniciamos la conexión a la base de datos
try {
  if (!$class->bdConex($insti_host, $insti_user, $insti_pass, $insti_db)) {
    throw new Exception($class->ERR_ASYSTECO);
  }

  // Establecemos UTF8 como cotejamiento de caracteres
  if (!$class->conex->set_charset("utf8")) {
    throw new Exception('Error al conectar con el servicio, inténtelo más tarde o contacte con los administradores.');
  }
} catch (Exception $e) {
  echo $e->getMessage();
  exit;
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
            include($dirs['Interfaces'] . 'footer.php');
          } elseif ($_SESSION['Perfil'] === 'Profesor') {
            $act_qr = 'active';
            include_once($dirs['Interfaces'] . 'header.php');
            include_once($dirs['Interfaces'] . 'top-nav.php');
            include_once($dirs['Qr'] . 'generate_code.php');
            include($dirs['Interfaces'] . 'footer.php');
          } elseif ($_SESSION['Perfil'] === 'Personal') {
            $act_qr = 'active';
            include_once($dirs['Interfaces'] . 'header.php');
            include_once($dirs['Interfaces'] . 'top-nav.php');
            include_once($dirs['Qr'] . 'generate_code.php');
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
      break;

    case 'admin-login':
      include_once($dirs['Login'] . 'admin-login.php');
      break;

    case 'logout':
      include_once($dirs['Login'] . 'logout.php');
      break;

    case 'cambio_pass':
        include_once($dirs['ChangePass'] . 'IndexCase.php');
      break;

    case 'primer_cambio':
      include_once($dirs['FirstPassChange'] . 'IndexCase.php');
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
              include_once($dirs['Horarios'] . 'calendario.php');
              include_once($dirs['Interfaces'] . 'footer.php');
            } else {
              $scripts = '<link rel="stylesheet" href="css/form.css">';
              $extras = "
                  $(function (){
                      $('#datepicker_ini').datepicker({
                        beforeShowDay: $.datepicker.noWeekends
                    });
                  });
                  $(function (){
                      $('#datepicker_fin').datepicker({
                        beforeShowDay: $.datepicker.noWeekends
                    });
                  });
                  $(function (){
                      $('#datepicker_ini_fest').datepicker({
                        beforeShowDay: $.datepicker.noWeekends
                    });
                  });
                  $(function (){
                      $('#datepicker_fin_fest').datepicker({
                        beforeShowDay: $.datepicker.noWeekends
                    });
                  });
                ";
              include_once($dirs['Valida'] . 'valida-lectivos.php');
              include_once($dirs['Interfaces'] . 'header.php');
              include_once($dirs['Interfaces'] . 'top-nav.php');
              include_once($dirs['Horarios'] . 'lectivos.php');
              include_once($dirs['public'] . 'js/lectivos.js');
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

          switch ($_GET['OPT'] ?? '') {
            case 'all':
              if ($_SESSION['Perfil'] === 'Admin') {
                $scripts .= '<script src="js/filtro_asistencias.js"></script>';
                $extras = "$(function (){ $('#busca_asiste').datepicker({
                    beforeShowDay: $.datepicker.noWeekends
                  });
                });";
                include_once($dirs['Interfaces'] . 'header.php');
                include_once($dirs['Interfaces'] . 'top-nav.php');
                include_once($dirs['Fichaje'] . 'contenido-asistencias-all.php');
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['Interfaces'] . 'msg_modal.php');
              }
              break;

            case 'sesion':
              $_GET['ID'] = $_SESSION['ID'];
              $scripts .= '<script src="js/filtro_asistencias.js"></script>';
              $scripts .= '<script src="js/update_marcajes.js"></script>';
              $extras = "$(function (){ $('#busca_asiste').datepicker({
                  beforeShowDay: $.datepicker.noWeekends
                });
              });";
              include_once($dirs['Interfaces'] . 'header.php');
              include_once($dirs['Interfaces'] . 'top-nav.php');
              include_once($dirs['Fichaje'] . 'contenido-asistencias.php');
              break;

            default:
              include_once($dirs['Fichaje'] . 'contenido-asistencias.php');
              break;
          }
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
          include($dirs['Interfaces'] . 'home.php');
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
      require_once($dirs['CP'] . 'IndexCase.php');
    break;

    case 'notificaciones':
      if ($class->isLogged($Titulo) && $_SESSION['Perfil'] == 'Admin') {
        if ($class->compruebaCambioPass()) {
          $act_usuario = 'active';
          $act_notification = 'active';

          include_once($dirs['Interfaces'] . 'header.php');
          include_once($dirs['Interfaces'] . 'top-nav.php');
          include_once($dirs['Interfaces'] . 'notificaciones.php');
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
      require_once($dirs['Admon'] . 'IndexCase.php');
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
              $('#add-fecha').datepicker({
                minDate: -7,
                maxDate: 0,
                beforeShowDay: $.datepicker.noWeekends
              });
            });
          ";
          $scripts = '<link rel="stylesheet" href="css/profesores-edit.css">';
          $scripts .= '<link rel="stylesheet" href="css/login-style.css">';
          //$scripts = '<link rel="stylesheet" href="css/profesores-sustituir.css">';
          include_once($dirs['Interfaces'] . 'header.php');
          include_once($dirs['Interfaces'] . 'top-nav.php');
          include_once($dirs['Fichaje'] . 'fichar-manual.php');
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
    
    case 'download':
      require_once($dirs['Downloads'] . 'IndexCase.php');
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
        include($dirs['Interfaces'] . 'footer.php');
      } elseif ($_SESSION['Perfil'] === 'Profesor') {
        $act_qr = 'active';
        include_once($dirs['Interfaces'] . 'header.php');
        include_once($dirs['Interfaces'] . 'top-nav.php');
        include_once($dirs['Qr'] . 'generate_code.php');
        include($dirs['Interfaces'] . 'footer.php');
      } elseif ($_SESSION['Perfil'] === 'Personal') {
        $act_qr = 'active';
        include_once($dirs['Interfaces'] . 'header.php');
        include_once($dirs['Interfaces'] . 'top-nav.php');
        include_once($dirs['Qr'] . 'generate_code.php');
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

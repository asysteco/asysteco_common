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
      require_once($dirs['Login'] . 'admin-login.php');
      break;

    case 'logout':
      require_once($dirs['Login'] . 'logout.php');
      break;

    case 'cambio_pass':
      require_once($dirs['ChangePass'] . 'IndexCase.php');
      break;

    case 'primer_cambio':
      require_once($dirs['FirstPassChange'] . 'IndexCase.php');
      break;

    case 'lectivos':
      require_once($dirs['Lectivos'] . 'IndexCase.php');
      break;

    case 'qrcoder':
      require_once($dirs['Qr'] . 'IndexCase.php');
      break;

    case 'horarios':
      require_once($dirs['Horarios'] . 'IndexCase.php');
    break;

    case 'asistencias':
      require_once($dirs['Asistencias'] . 'IndexCase.php');
      break;

    case 'profesores':
      require_once($dirs['Profesores'] . 'IndexCase.php');
      break;

    case 'marcajes':
      require_once($dirs['Marcajes'] . 'IndexCase.php');
      break;

    case 'guardias':
      require_once($dirs['Guardias'] . 'IndexCase.php');
      break;

    case 'cp':
      require_once($dirs['CP'] . 'IndexCase.php');
    break;

    case 'notificaciones':
      require_once($dirs['Notificaciones'] . 'IndexCase.php');
      break;

    case 'admon':
      require_once($dirs['Admon'] . 'IndexCase.php');
      break;

    case 'fichar-asist':
      require_once($dirs['FicharQr'] . 'IndexCase.php');
      break;

    case 'fichar-manual':
      require_once($dirs['FicharManual'] . 'IndexCase.php');
      break;
    
    case 'download':
      require_once($dirs['Downloads'] . 'IndexCase.php');
    break;

    case 'clean_tmp':
      require_once($dirs['CleanTmp'] . 'IndexCase.php');
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
